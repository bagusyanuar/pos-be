<?php

namespace App\Http\Resources\Material;

use App\Commons\Libs\Resource\BaseCollection;
use Illuminate\Http\Request;

class MaterialCollection extends BaseCollection
{
   protected $baseResource = MaterialResource::class;
}
