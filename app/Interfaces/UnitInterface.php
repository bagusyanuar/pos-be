<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Schemas\Unit\UnitQuery;
use App\Schemas\Unit\UnitSchema;

interface UnitInterface
{
    public function create(UnitSchema $schema): ServiceResponse;
    public function findAll(UnitQuery $queryParams): ServiceResponse;
    public function findByID($id): ServiceResponse;
    public function update($id, UnitSchema $schema): ServiceResponse;
    public function delete($id): ServiceResponse;
}
