<?php

namespace App\Commons\Libs\Resource;

use App\Commons\Enums\HttpStatus;
use App\Commons\Libs\Http\APIResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    protected HttpStatus $httpStatus;
    protected string $message;

    public function toArray($request)
    {
        return $this->transformData($request);
    }

    public function __construct($resource, HttpStatus|string|int|null $httpStatus = null, string|null $message = "internal server error")
    {
        parent::__construct($resource);
        if ($httpStatus instanceof HttpStatus) {
            $this->httpStatus = $httpStatus;
        } elseif (is_int($httpStatus)) {
            $this->httpStatus = HttpStatus::tryFrom($httpStatus) ?? HttpStatus::InternalServerError;
        } else {
            $this->httpStatus = HttpStatus::InternalServerError;
        }
        $this->message = $message;
    }

    public function toResponse($request): JsonResponse
    {
        try {
            $data = $this->resource ? $this->transformSafely($request) : null;
            return APIResponse::toJSON(
                $this->httpStatus,
                $this->message,
                $data,
                null
            );
        } catch (\Throwable $th) {
            return APIResponse::toJSON(
                HttpStatus::InternalServerError,
                "failed to transform resource: ({$th->getMessage()})",
            );
        }
    }

    protected function transformSafely(Request $request): array
    {
        return $this->transformData($request);
    }

    protected function transformData(Request $request): array
    {
        if ($this->resource instanceof JsonResource) {
            return $this->resource->resolve();
        }

        return $this->resource->toArray();
    }
}
