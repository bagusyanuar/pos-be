<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialReceiptItem extends Model
{
    use HasFactory, Uuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'material_receipt_id',
        'material_id',
        'qty',
        'price',
        'total',
    ];

    protected $casts = [
        'qty' => 'float',
        'price' => 'float',
        'total' => 'float',
    ];

    public function material_receipt()
    {
        return $this->belongsTo(MaterialReceipt::class, 'material_recipt_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
