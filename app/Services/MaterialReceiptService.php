<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Interfaces\MaterialReceiptInterface;
use App\Models\MaterialInventory;
use App\Models\MaterialReceipt;
use App\Schemas\MaterialReceipt\MaterialReceiptQuery;
use App\Schemas\MaterialReceipt\MaterialReceiptSchema;
use Illuminate\Support\Facades\DB;

class MaterialReceiptService implements MaterialReceiptInterface
{
    public function create(MaterialReceiptSchema $schema): ServiceResponse
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
            return ServiceResponse::statusCreated("successfully create material receipt");
        } catch (\Throwable $th) {
            DB::rollBack();
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findAll(MaterialReceiptQuery $queryParams): ServiceResponse {}
}
