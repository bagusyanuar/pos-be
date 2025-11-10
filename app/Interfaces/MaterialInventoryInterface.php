<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Models\MaterialInventory;

interface MaterialInventoryInterface
{
    public function findAll(MaterialInventory $queryParams): ServiceResponse;
}
