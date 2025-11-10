<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Schemas\Purchase\PurchaseQuery;
use App\Schemas\Purchase\PurchaseSchema;

interface PurchaseInterface
{
    public function create(PurchaseSchema $schema): ServiceResponse;
    public function findAll(PurchaseQuery $queryParams): ServiceResponse;
    public function findByID($id): ServiceResponse;
}
