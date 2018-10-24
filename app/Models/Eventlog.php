<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eventlog extends Model
{
    //указываем имя таблицы
    protected $table = 'eventlogs';

    protected $fillable = ['type','user_id','text','ip'];

    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
