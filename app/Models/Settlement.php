<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    //указываем имя таблицы
    protected $table = 'settlements';

    protected $fillable = ['name'];

    public function contracts()
    {
        return $this->hasMany('App\Models\Contract','settlement_id','id');
    }
}
