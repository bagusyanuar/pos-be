<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Schemas\MaterialReceipt\MaterialReceiptQuery;
use App\Schemas\MaterialReceipt\MaterialReceiptSchema;

interface MaterialReceiptInterface
{
    public function create(MaterialReceiptSchema $schema): ServiceResponse;
    public function findAll(MaterialReceiptQuery $queryParams): ServiceResponse;
}
