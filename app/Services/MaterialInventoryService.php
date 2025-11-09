<?php

namespace App\Services;

use App\Models\MaterialInventory;

class MaterialInventoryService
{
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
