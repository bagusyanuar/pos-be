<?php

namespace App\Http\Controllers;

use App\Commons\Libs\Http\APIResponse;
use App\Http\Resources\Supplier\SupplierCollection;
use App\Http\Resources\Supplier\SupplierResource;
use App\Schemas\Supplier\SupplierQuery;
use App\Schemas\Supplier\SupplierSchema;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends CustomController
{
    /** @var SupplierService $service */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new SupplierService();
    }

    public function create()
    {
        $schema = (new SupplierSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->create($schema);
        return APIResponse::fromService($response);
    }

    public function findAll()
    {
        $query = (new SupplierQuery())->hydrateSchemaQuery($this->queryParams());
        $response = $this->service->findAll($query);
        return new SupplierCollection($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function findByID($id)
    {
        $response = $this->service->findByID($id);
        return new SupplierResource($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function update($id)
    {
        $schema = (new SupplierSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->update($id, $schema);
        return APIResponse::fromService($response);
    }

    public function delete($id)
    {
        $response = $this->service->delete($id);
        return APIResponse::fromService($response);
    }
}
