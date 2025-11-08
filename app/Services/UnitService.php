<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Interfaces\UnitInterface;
use App\Models\Unit;
use App\Schemas\Unit\UnitQuery;
use App\Schemas\Unit\UnitSchema;
use Illuminate\Support\Facades\Auth;

class UnitService implements UnitInterface
{
    public function create(UnitSchema $schema): ServiceResponse
    {
        try {
            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();
            $data = [
                'name' => $schema->getName()
            ];
            Unit::create($data);
            return ServiceResponse::statusCreated("successfully create unit");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findAll(UnitQuery $queryParams): ServiceResponse
    {
        try {
            $queryParams->hydrateQuery();
            $query = Unit::with([])
                ->when($queryParams->getParam(), function ($q) use ($queryParams) {
                    /** @var Builder $q */
                    return $q->where('name', 'LIKE', "%{$queryParams->getParam()}%");
                })
                ->orderBy('name', 'ASC');
            if ($queryParams->getPage() && $queryParams->getPerPage()) {
                $data = $query->paginate(
                    $queryParams->getPerPage(),
                    ['*'],
                    'page',
                    $queryParams->getPage()
                );
            } else {
                $data = $query->get();
            }
            return ServiceResponse::statusOK("successfully get units", $data);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findByID($id): ServiceResponse
    {
        try {
            $unit = Unit::where('id', '=', $id)
                ->first();
            if (!$unit) {
                return ServiceResponse::notFound("unit not found");
            }
            return ServiceResponse::statusOK("successfully get unit", $unit);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function update($id, UnitSchema $schema): ServiceResponse
    {
        try {
            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();
            $unit = Unit::where('id', '=', $id)
                ->first();
            if (!$unit) {
                return ServiceResponse::notFound("unit not found");
            }
            $unit->update([
                'name' => $schema->getName()
            ]);
            return ServiceResponse::statusOK("successfully update unit");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function delete($id): ServiceResponse
    {
        try {
            $unit = Unit::where('id', '=', $id)
                ->first();
            if (!$unit) {
                return ServiceResponse::notFound("unit not found");
            }
            $unit->delete();
            return ServiceResponse::statusOK("successfully delete unit");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }
}
