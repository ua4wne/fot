<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomenclatureGroup extends Model
{
    //указываем имя таблицы
    protected $table = 'nomenclature_groups';

    protected $fillable = ['name'];

    public function nomenclature()
    {
        return $this->hasMany('App\Models\Nomenclature','id','group_id');
    }
}
