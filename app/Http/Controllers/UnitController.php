<?php

namespace App\Http\Controllers;

use App\Commons\Libs\Http\APIResponse;
use App\Http\Resources\Unit\UnitCollection;
use App\Http\Resources\Unit\UnitResource;
use App\Schemas\Unit\UnitQuery;
use App\Schemas\Unit\UnitSchema;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UnitController extends CustomController
{
    /** @var UnitService $service */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new UnitService();
    }

    public function create()
    {
        $schema = (new UnitSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->create($schema);
        return APIResponse::fromService($response);
    }

    public function findAll()
    {
        $query = (new UnitQuery())->hydrateSchemaQuery($this->queryParams());
        $response = $this->service->findAll($query);
        return new UnitCollection($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function findByID($id)
    {
        $response = $this->service->findByID($id);
        return new UnitResource($response->getData(), $response->getStatus(), $response->getMessage());
    }

    public function update($id)
    {
        $schema = (new UnitSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->update($id, $schema);
        return APIResponse::fromService($response);
    }

    public function delete($id)
    {
        $response = $this->service->delete($id);
        return APIResponse::fromService($response);
    }
}
