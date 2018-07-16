<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    //указываем имя таблицы
    protected $table = 'orgs';

    protected $fillable = ['name', 'full_name', 'inn', 'kpp'];
}
