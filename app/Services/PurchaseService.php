<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Interfaces\PurchaseInterface;
use App\Models\MaterialReceipt;
use App\Schemas\Purchase\PurchaseQuery;
use App\Schemas\Purchase\PurchaseSchema;
use Illuminate\Support\Facades\DB;

class PurchaseService implements PurchaseInterface
{
    public function create(PurchaseSchema $schema): ServiceResponse
    {
        try {
            DB::beginTransaction();
            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();

            $items = collect($schema->getItems())->map(function ($item) {
                return [
                    ...$item,
                    'total' => $item['qty'] * $item['price'],
                ];
            });

            $amount = collect($items)->sum('total');

            $data = [
                'date' => $schema->getDate(),
                'receipt_number' => $schema->getReceiptNumber(),
                'supplier_id' => $schema->getSupplierId(),
                'note' => $schema->getNote(),
                'amount' => $amount
            ];

            # create data receipt
            $recipt = MaterialReceipt::create($data);

            # create items receipt
            $recipt->items()->createMany($items);

            # add items to inventory

            $inventoryService = new MaterialInventoryService();
            $inventoryService->addToInventoryBatch($items);

            DB::commit();
            return ServiceResponse::statusCreated("successfully create purchase");
        } catch (\Throwable $th) {
            DB::rollBack();
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findAll(PurchaseQuery $queryParams): ServiceResponse
    {
        try {
            $queryParams->hydrateQuery();
            $query = MaterialReceipt::with([
                'supplier',
                'items.material.unit',
            ])
                ->when(($queryParams->getDateStart() && $queryParams->getDateEnd()), function ($q) use ($queryParams) {
                    /** @var Builder $q */
                    return $q->whereBetween('date', [$queryParams->getDateStart(), $queryParams->getDateEnd()]);
                })
                ->when($queryParams->getParam(), function ($q) use ($queryParams) {
                    /** @var Builder $q */
                    return $q->where('receipt_number', 'LIKE', "%{$queryParams->getParam()}%");
                })
                ->orderBy('created_at', 'DESC');
            if ($queryParams->getPage() && $queryParams->getPerPage()) {
                $data = $query->paginate(
                    $queryParams->getPerPage(),
                    ['*'],
                    'page',
                    $queryParams->getPage()
                );
            } else {
                $data = $query->get();
            }
            return ServiceResponse::statusOK("successfully get purchases", $data);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findByID($id): ServiceResponse
    {
        try {
            $data = MaterialReceipt::with([
                'supplier',
                'items.material.unit',
            ])->where('id', '=', $id)
                ->first();
            if (!$data) {
                return ServiceResponse::notFound("purchase not found");
            }
            return ServiceResponse::statusOK("successfully get purchase", $data);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }
}
