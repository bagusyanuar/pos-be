<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Interfaces\MaterialInventoryInterface;
use App\Models\MaterialInventory;

class MaterialInventoryService implements MaterialInventoryInterface
{
    public function findAll(MaterialInventory $queryParams): ServiceResponse
    {
        try {
            $queryParams->hydrateQuery();
            $query = MaterialInventory::with(['unit'])
                ->when($queryParams->getParam(), function ($q) use ($queryParams) {
                    /** @var Builder $q */
                    return $q->where('name', 'LIKE', "%{$queryParams->getParam()}%");
                })
                ->orderBy('name', 'ASC');
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
            return ServiceResponse::statusOK("successfully get materials", $data);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function addToInventoryBatch($items)
    {
        foreach ($items as $item) {
            $materialId = $item['material_id'];
            $qty = $item['qty'];
            $inventory = MaterialInventory::with([])
                ->where('id', '=', $materialId)
                ->first();
            $currentQty = 0;
            if ($inventory) {
                $currentQty = $inventory->qty;
                $newQty = $currentQty + $qty;
                $inventory->update([
                    'qty' => $newQty
                ]);
            } else {
                MaterialInventory::create([
                    'material_id' => $materialId,
                    'qty' => $qty
                ]);
            }
        }
    }
}
