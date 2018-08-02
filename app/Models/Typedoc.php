<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typedoc extends Model
{
    //указываем имя таблицы
    protected $table = 'typedocs';

    protected $fillable = ['name'];
}
