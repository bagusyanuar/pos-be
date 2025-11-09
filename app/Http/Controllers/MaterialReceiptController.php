<?php

namespace App\Http\Controllers;

use App\Commons\Libs\Http\APIResponse;
use App\Schemas\MaterialReceipt\MaterialReceiptSchema;
use App\Services\MaterialReceiptService;
use Illuminate\Http\Request;

class MaterialReceiptController extends CustomController
{
    /** @var MaterialReceiptService $service */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new MaterialReceiptService();
    }

    public function create()
    {
        $schema = (new MaterialReceiptSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->create($schema);
        return APIResponse::fromService($response);
    }
}
