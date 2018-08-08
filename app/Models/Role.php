<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //указываем имя таблицы
    protected $table = 'roles';

    protected $fillable = ['code','name'];

    /**
     * Пользователи, принадлежащие роли.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
