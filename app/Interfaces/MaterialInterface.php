<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Schemas\Material\MaterialQuery;
use App\Schemas\Material\MaterialSchema;

interface MaterialInterface
{
    public function create(MaterialSchema $schema): ServiceResponse;
    public function findAll(MaterialQuery $queryParams): ServiceResponse;
    public function findByID($id): ServiceResponse;
    public function update($id, MaterialSchema $schema): ServiceResponse;
    public function delete($id): ServiceResponse;
}
