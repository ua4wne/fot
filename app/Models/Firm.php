<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    //указываем имя таблицы
    protected $table = 'firms';

    protected $fillable = ['type', 'name', 'fio', 'group_id', 'inn', 'kpp'];
}
