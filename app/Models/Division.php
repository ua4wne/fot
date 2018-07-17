<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    //указываем имя таблицы
    protected $table = 'divisions';

    protected $fillable = ['name', 'org_id'];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organisation');
    }
}
