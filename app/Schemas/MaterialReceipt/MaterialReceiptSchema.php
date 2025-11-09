<?php

namespace App\Schemas\MaterialReceipt;

use App\Commons\Libs\Http\BaseSchema;

class MaterialReceiptSchema extends BaseSchema
{
    private $date;
    private $receiptNumber;
    private $supplierId;
    private $note;
    private $items;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'receipt_number' => 'required|string',
            'supplier_id' => 'nullable|uuid',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|uuid',
            'items.*.qty' => 'required|numeric|gt:0',
            'items.*.price' => 'required|numeric'
        ];
    }

    public function hydrateBody()
    {
        $date = $this->body['date'];
        $receiptNumber = $this->body['receipt_number'];
        $items = $this->body['items'];
        $supplierId = !empty(trim($this->body['supplier_id'] ?? '')) ? $this->body['supplier_id'] : null;
        $note = !empty(trim($this->body['note'] ?? '')) ? $this->body['note'] : null;
        $this->setDate($date)
            ->setReceiptNumber($receiptNumber)
            ->setSupplierId($supplierId)
            ->setNote($note)
            ->setItems($items);
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of receiptNumber
     */
    public function getReceiptNumber()
    {
        return $this->receiptNumber;
    }

    /**
     * Set the value of receiptNumber
     *
     * @return  self
     */
    public function setReceiptNumber($receiptNumber)
    {
        $this->receiptNumber = $receiptNumber;

        return $this;
    }

    /**
     * Get the value of supplierId
     */
    public function getSupplierId()
    {
        return $this->supplierId;
    }

    /**
     * Set the value of supplierId
     *
     * @return  self
     */
    public function setSupplierId($supplierId)
    {
        $this->supplierId = $supplierId;

        return $this;
    }

    /**
     * Get the value of note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the value of note
     *
     * @return  self
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the value of items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @return  self
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }
}
