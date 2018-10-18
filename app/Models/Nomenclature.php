<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    //указываем имя таблицы
    protected $table = 'nomenclatures';

    protected $fillable = ['group_id', 'name', 'unit'];

    public function nomenclature_group()
    {
        return $this->belongsTo('App\Models\NomenclatureGroup','group_id','id');
    }
}
