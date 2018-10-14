<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    //указываем имя таблицы
    protected $table = 'persons';

    protected $fillable = ['fio', 'inn', 'snils', 'gender'];

    public function advances()
    {
        return $this->hasMany('App\Models\Advance','person_id','id');
    }
}
