<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //указываем имя таблицы
    protected $table = 'banks';

    protected $fillable = ['bik', 'swift', 'name', 'account', 'city', 'address', 'phone', 'country'];
}
