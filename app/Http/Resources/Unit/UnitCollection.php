<?php

namespace App\Http\Resources\Unit;

use App\Commons\Libs\Resource\BaseCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UnitCollection extends BaseCollection
{
    protected $baseResource = UnitResource::class;
}
