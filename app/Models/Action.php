<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //указываем имя таблицы
    protected $table = 'actions';

    protected $fillable = ['code','name'];
}
