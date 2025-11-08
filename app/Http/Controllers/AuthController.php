<?php

namespace App\Http\Controllers;

use App\Commons\Libs\Http\APIResponse;
use App\Schemas\Auth\LoginSchema;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends CustomController
{
    /** @var AuthService $service */
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new AuthService();
    }

    public function login()
    {
        $schema = (new LoginSchema())->hydrateSchemaBody($this->jsonBody());
        $response = $this->service->login($schema);
        return APIResponse::fromService($response);
    }
}
