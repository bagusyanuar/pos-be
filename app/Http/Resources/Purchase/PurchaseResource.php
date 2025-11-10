<?php

namespace App\Http\Resources\Purchase;

use App\Commons\Libs\Resource\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends BaseResource
{
    protected function transformData(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'date' => $this->date,
            'receipt_number' => $this->receipt_number,
            'amount' => $this->amount,
        ];

        if ($this->relationLoaded('supplier')) {
            $supplier = $this->getRelation('supplier');
            $data['supplier'] = $supplier ? [
                'id' => $supplier->id,
                'name' => $supplier->name
            ] : null;
        }

        if ($this->relationLoaded('items')) {
            $data['items'] = $this->items->map(function ($item) {
                $material = $item->relationLoaded('material') ? $item->getRelation('material') : null;
                $unit = $material ? ($material && $material->relationLoaded('unit') ? $material->getRelation('unit') : null) : null;

                return [
                    'id' => $item->id,
                    'material' => $material ? [
                        'id' => $material->id,
                        'name' => $material->name,
                        'unit' => $unit ? $unit->name : null
                    ] : null,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->total
                ];
            });
        }
        return $data;
    }
}
