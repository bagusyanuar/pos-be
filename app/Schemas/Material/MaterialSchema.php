<?php

namespace App\Schemas\Material;

use App\Commons\Libs\Http\BaseSchema;

class MaterialSchema extends BaseSchema
{
    private $name;
    private $unitId;

    protected function rules()
    {
        return [
            'name' => 'required|string|unique:units,name',
            'unit_id' => 'required|uuid'
        ];
    }

    public function hydrateBody()
    {
        $name = $this->body['name'];
        $unitId = $this->body['unit_id'];
        $this->setName($name)
            ->setUnitId($unitId);
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of unitId
     */
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * Set the value of unitId
     *
     * @return  self
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;

        return $this;
    }
}
