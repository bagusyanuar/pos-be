<?php

namespace App\Schemas\Material;

use App\Commons\Libs\Http\BaseSchema;

class MaterialSchema extends BaseSchema
{
    private $name;
    private $units;

    protected function rules()
    {
        return [
            'name' => 'required|string|unique:units,name',
            'units' => 'required|array|min:1',
            'units.*' => 'required|uuid'
        ];
    }

    public function hydrateBody()
    {
        $name = $this->body['name'];
        $units = $this->body['units'];
        $this->setName($name)
            ->setUnits($units);
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
     * Get the value of units
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set the value of units
     *
     * @return  self
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }
}
