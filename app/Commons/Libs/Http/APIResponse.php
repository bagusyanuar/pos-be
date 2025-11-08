<?php

namespace App\Commons\Libs\Http;

use App\Commons\Enums\HttpStatus;
use Illuminate\Support\Facades\Response;

class APIResponse
{
    public static function toJSON(HttpStatus $status, string $message = '', $data = null, $meta = null)
    {
        $response = [
            'status' => $status->value,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!is_null($meta)) {
            $response['meta'] = $meta;
        }
        return Response::json($response, $status->value);
    }

    public static function fromService(ServiceResponse $response)
    {
        return self::toJSON(
            $response->getStatus(),
            $response->getMessage(),
            $response->getData(),
            $response->getMeta()
        );
    }
}
