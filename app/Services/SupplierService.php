<?php

namespace App\Services;

use App\Commons\Libs\Http\ServiceResponse;
use App\Interfaces\SupplierInterface;
use App\Models\Supplier;
use App\Schemas\Supplier\SupplierQuery;
use App\Schemas\Supplier\SupplierSchema;
use App\Schemas\Unit\UnitQuery;

class SupplierService implements SupplierInterface
{
    public function create(SupplierSchema $schema): ServiceResponse
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
            Supplier::create($data);
            return ServiceResponse::statusCreated("successfully create supplier");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findAll(SupplierQuery $queryParams): ServiceResponse
    {
        try {
            $queryParams->hydrateQuery();
            $query = Supplier::with([])
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
            return ServiceResponse::statusOK("successfully get suppliers", $data);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function findByID($id): ServiceResponse
    {
        try {
            $supplier = Supplier::where('id', '=', $id)
                ->first();
            if (!$supplier) {
                return ServiceResponse::notFound("supplier not found");
            }
            return ServiceResponse::statusOK("successfully get supplier", $supplier);
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function update($id, SupplierSchema $schema): ServiceResponse
    {
        try {
            $validator = $schema->validate();
            if ($validator->fails()) {
                return ServiceResponse::unprocessableEntity($validator->errors()->toArray());
            }
            $schema->hydrateBody();
            $supplier = Supplier::where('id', '=', $id)
                ->first();
            if (!$supplier) {
                return ServiceResponse::notFound("supplier not found");
            }
            $supplier->update([
                'name' => $schema->getName()
            ]);
            return ServiceResponse::statusOK("successfully update supplier");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }

    public function delete($id): ServiceResponse
    {
        try {
            $supplier = Supplier::where('id', '=', $id)
                ->first();
            if (!$supplier) {
                return ServiceResponse::notFound("supplier not found");
            }
            $supplier->delete();
            return ServiceResponse::statusOK("successfully delete supplier");
        } catch (\Throwable $th) {
            return ServiceResponse::internalServerError($th->getMessage());
        }
    }
}
