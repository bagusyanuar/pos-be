<?php

namespace App\Http\Controllers;

use App\Commons\Libs\Http\APIResponse;
use App\Http\Resources\Purchase\PurchaseCollection;
use App\Http\Resources\Purchase\PurchaseResource;
use App\Schemas\Purchase\PurchaseQuery;
use App\Schemas\Purchase\PurchaseSchema;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends CustomController
{
    /** @var PurchaseService $service */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new PurchaseService();
    }

    public function create()
    {
        $schema = (new PurchaseSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->create($schema);
        return APIResponse::fromService($response);
    }

    public function findAll()
    {
        $query = (new PurchaseQuery())->hydrateSchemaQuery($this->queryParams());
        $response = $this->service->findAll($query);
        return new PurchaseCollection($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function findByID($id)
    {
        $response = $this->service->findByID($id);
        return new PurchaseResource($response->getData(), $response->getStatus(), $response->getMessage());
    }
}
