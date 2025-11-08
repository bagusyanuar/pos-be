<?php

namespace App\Commons\Libs\Resource;

use App\Commons\Enums\HttpStatus;
use App\Commons\Libs\Http\APIResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class BaseCollection extends ResourceCollection
{
    protected $baseResource = BaseResource::class;
    protected HttpStatus $httpStatus;
    protected string $message;

    public function __construct($resource, HttpStatus $httpStatus = HttpStatus::InternalServerError, string $message = 'internal server error')
    {
        parent::__construct([]);
        if ($resource) {
            parent::__construct($resource);
        }
        $this->httpStatus = $httpStatus;
        $this->message = $message;
    }


    public function toResponse($request): JsonResponse
    {
        try {
            $array = $this->safelyTransform($request);
            $meta = null;
            if ($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $meta = [
                    'page'        => $this->currentPage(),
                    'per_page'    => $this->perPage(),
                    'total_rows'  => $this->total(),
                    'total_pages' => $this->lastPage(),
                ];
            }
            return APIResponse::toJSON(
                $this->httpStatus,
                $this->message,
                $array['data'] ?? [],
                $meta
            );
        } catch (\Throwable $th) {
            return APIResponse::toJSON(
                HttpStatus::InternalServerError,
                "failed to transform collection: ({$th->getMessage()})",
            );
        }
    }

    protected function safelyTransform(Request $request): array
    {
        return $this->transformData($request);
    }

    protected function transformData(Request $request): array
    {
        $baseResource = $this->baseResource;

        $items = $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $this->resource->getCollection()
            : ($this->resource instanceof Collection ? $this->resource : collect($this->resource));

        return [
            'data' => $items->map(function ($item) use ($request, $baseResource) {
                if ($item instanceof JsonResource) {
                    return $item->resolve();
                }
                $resource = new $baseResource($item, $this->httpStatus, $this->message);
                return $resource->resolve();
            })->all()
        ];
    }
}
