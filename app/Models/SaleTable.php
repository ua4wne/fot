<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTable extends Model
{
    //указываем имя таблицы
    protected $table = 'sale_tables';

    protected $fillable = ['sale_id', 'nomenclature_id', 'qty', 'price', 'amount', 'buhcode_id'];

    public function sale()
    {
        return $this->belongsTo('App\Models\Sale','sale_id','id');
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
