<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    //указываем имя таблицы
    protected $table = 'operations';

    protected $fillable = ['name'];
}
