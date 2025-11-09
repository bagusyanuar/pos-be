<?php

namespace App\Schemas\MaterialReceipt;

use App\Commons\Libs\Http\BaseSchema;

class MaterialReceiptQuery extends BaseSchema
{
    private $param;
    private $page;
    private $perPage;

    public function hydrateQuery()
    {
        $param = $this->query['param'] ?? '';
        $page = $this->query['page'] ?? '';
        $perPage = $this->query['per_page'] ?? '';
        $this->setParam($param)
            ->setPage($page)
            ->setPerPage($perPage);
    }

    /**
     * Get the value of param
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set the value of param
     *
     * @return  self
     */
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get the value of page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @return  self
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of perPage
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set the value of perPage
     *
     * @return  self
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }
}
