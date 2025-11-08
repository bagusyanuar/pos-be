<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomController extends Controller
{
    /** @var Request $request */
    protected $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    public function formBody()
    {
        return $this->request->all();
    }

    public function jsonBody()
    {
        return $this->request->json()->all();
    }

    public function queryParams()
    {
        return $this->request->query();
    }
}
