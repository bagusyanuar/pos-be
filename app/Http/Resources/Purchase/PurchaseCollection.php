<?php

namespace App\Http\Resources\Purchase;

use App\Commons\Libs\Resource\BaseCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseCollection extends BaseCollection
{
    protected $baseResource = PurchaseResource::class;
}
