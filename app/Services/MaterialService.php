<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Interfaces\MaterialInterface;
use App\Models\Material;
use App\Schemas\Material\MaterialQuery;
use App\Schemas\Material\MaterialSchema;
use Illuminate\Support\Facades\DB;

class MaterialService implements MaterialInterface
{
    public function create(MaterialSchema $schema): ServiceResponse
    {
        try {
            DB::beginTransaction();
            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();

            $data = [
                'unit_id' => $schema->getUnitId(),
                'name' => $schema->getName()
            ];
            Material::create($data);
            DB::commit();
            return ServiceResponse::statusCreated("successfully create material");
        } catch (\Throwable $th) {
            DB::rollBack();
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findAll(MaterialQuery $queryParams): ServiceResponse
    {
        try {
            $queryParams->hydrateQuery();
            $query = Material::with(['unit'])
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
            return ServiceResponse::statusOK("successfully get materials", $data);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findByID($id): ServiceResponse
    {
        try {
            $material = Material::with(['unit'])
                ->where('id', '=', $id)
                ->first();
            if (!$material) {
                return ServiceResponse::notFound("material not found");
            }
            return ServiceResponse::statusOK("successfully get material", $material);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function update($id, MaterialSchema $schema): ServiceResponse
    {
        try {
            DB::beginTransaction();
            $material = Material::with(['units'])
                ->where('id', '=', $id)
                ->first();
            if (!$material) {
                return ServiceResponse::notFound("material not found");
            }

            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();

            $data = [
                'unit_id' => $schema->getUnitId(),
                'name' => $schema->getName()
            ];

            $material->update($data);
            DB::commit();
            return ServiceResponse::statusOK("successfully update material");
        } catch (\Throwable $th) {
            DB::rollBack();
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function delete($id): ServiceResponse
    {
        try {
            $material = Material::with([])
                ->where('id', '=', $id)
                ->first();
            if (!$material) {
                return ServiceResponse::notFound("material not found");
            }

            $material->delete();
            return ServiceResponse::statusOK("successfully delete material");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }
}
