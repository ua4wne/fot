<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //указываем имя таблицы
    protected $table = 'groups';

    protected $fillable = ['parent_id', 'name'];
}
