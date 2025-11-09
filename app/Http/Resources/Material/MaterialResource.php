<?php

namespace App\Http\Resources\Material;

use App\Commons\Libs\Resource\BaseResource;
use Illuminate\Http\Request;

class MaterialResource extends BaseResource
{
    protected function transformData(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($this->relationLoaded('unit')) {
            $unit = $this->getRelation('unit');
            $data['unit'] = $unit ? [
                'id' => $unit->id,
                'name' => $unit->name
            ] : null;
        }
        return $data;
    }
}
