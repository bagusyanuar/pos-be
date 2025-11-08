<?php

namespace App\Interfaces;

use App\Commons\Libs\Http\ServiceResponse;
use App\Schemas\Auth\LoginSchema;

interface AuthInterface
{
    public function login(LoginSchema $schema): ServiceResponse;
}
