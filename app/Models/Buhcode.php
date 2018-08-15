<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buhcode extends Model
{
    //указываем имя таблицы
    protected $table = 'buhcodes';

    protected $fillable = ['code', 'text', 'show'];
}
