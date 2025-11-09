<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Schemas\Supplier\SupplierQuery;
use App\Schemas\Supplier\SupplierSchema;

interface SupplierInterface
{
    public function create(SupplierSchema $schema): ServiceResponse;
    public function findAll(SupplierQuery $queryParams): ServiceResponse;
    public function findByID($id): ServiceResponse;
    public function update($id, SupplierSchema $schema): ServiceResponse;
    public function delete($id): ServiceResponse;
}
