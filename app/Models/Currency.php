<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //указываем имя таблицы
    protected $table = 'currency';

    protected $fillable = ['name', 'dcode', 'scode', 'cource', 'multi'];
}
