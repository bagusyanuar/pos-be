<?php

namespace App\Commons\Libs\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

class JWTGuard implements Guard
{
    protected $request;
    protected $user;
    protected $key;
    protected $tokenExpired;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->key = config('jwt.secret');
        $this->tokenExpired = false;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (!$token) return null;

        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $claims = (array) $decoded->claims;

            $user = new \App\Models\User();
            $user->id = $decoded->sub ?? null;
            $user->email = $claims['email'] ?? null;
            $user->username = $claims['username'] ?? null;

            $this->user = $user;
            return $this->user;
        } catch (ExpiredException $e) {
            $this->tokenExpired = true;
            Log::warning("JWT expired: {$e->getMessage()}");
            return null;
        } catch (SignatureInvalidException | UnexpectedValueException $e) {
            return null;
        } catch (Exception $e) {
            Log::error("error set user JWT Guard : {$e->getMessage()}");
            return null;
        }
    }

    public function check()
    {
        return $this->user() !== null;
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->user()?->id;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function tokenExpired()
    {
        return $this->tokenExpired;
    }

    // ✅ Tambahkan ini untuk memenuhi kontrak Guard
    public function hasUser()
    {
        return !is_null($this->user);
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
}
