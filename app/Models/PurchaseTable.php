<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTable extends Model
{
    //указываем имя таблицы
    protected $table = 'purchase_tables';

    protected $fillable = ['purchase_id', 'nomenclature_id', 'qty', 'price', 'amount', 'buhcode_id'];

    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase','purchase_id','id');
    }

    public function buhcode()
    {
        return $this->belongsTo('App\Models\Buhcode');
    }

    public function nomenclature()
    {
        return $this->belongsTo('App\Models\Nomenclature');
    }
}
