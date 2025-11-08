<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Commons\Libs\JWT\JWTAuth;
use App\Commons\Libs\JWT\JWTClaims;
use App\Interfaces\AuthInterface;
use App\Models\User;
use App\Schemas\Auth\LoginSchema;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthInterface
{
    public function login(LoginSchema $schema): ServiceResponse
    {
        try {
            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();

            $user = User::with([])
                ->where('email', '=', $schema->getEmail())
                ->first();
            if (!$user) {
                return ServiceResponse::notFound('user not found');
            }

            $isPasswordValid = Hash::check($schema->getPassword(), $user->password);
            if (!$isPasswordValid) {
                return ServiceResponse::unauthorized('password did not match');
            }

            $payload = $this->createAuthToken($user);

            return ServiceResponse::statusOK("successfully login", $payload);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function createAuthToken(User $user): array
    {
        $jwtClaims = new JWTClaims($user->id, $user->email, $user->username, [], []);
        $accessToken = JWTAuth::encode($jwtClaims);
        $refreshToken = JWTAuth::encodeRefreshToken($user->email);
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }
}
