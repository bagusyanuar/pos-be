<?php

namespace App\Http\Controllers;

use App\Commons\Libs\Http\APIResponse;
use App\Http\Resources\Material\MaterialCollection;
use App\Http\Resources\Material\MaterialResource;
use App\Schemas\Material\MaterialQuery;
use App\Schemas\Material\MaterialSchema;
use App\Services\MaterialService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MaterialController extends CustomController
{
    /** @var MaterialService $service */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new MaterialService();
    }

    public function create()
    {
        $schema = (new MaterialSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->create($schema);
        return APIResponse::fromService($response);
    }

    public function findAll()
    {
        $query = (new MaterialQuery())->hydrateSchemaQuery($this->queryParams());
        $response = $this->service->findAll($query);
        return new MaterialCollection($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function findByID($id)
    {
        $response = $this->service->findByID($id);
        return new MaterialResource($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function update($id)
    {
        $schema = (new MaterialSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->update($id, $schema);
        return APIResponse::fromService($response);
    }

    public function delete($id)
    {
        $response = $this->service->delete($id);
        return APIResponse::fromService($response);
    }
}
