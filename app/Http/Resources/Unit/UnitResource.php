<?php

namespace App\Http\Resources\Unit;

use App\Commons\Libs\Resource\BaseResource;
use Illuminate\Http\Request;

class UnitResource extends BaseResource
{
    protected function transformData(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
