<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    //указываем имя таблицы
    protected $table = 'advances';

    protected $fillable = ['user_id', 'created_at', 'doc_num', 'person_id', 'amount', 'currency_id', 'org_id', 'comment'];

    public function advance_table()
    {
        return $this->hasMany('App\Models\AdvanceTable','id','advance_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Models\Person','person_id','id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_id','id');
    }

    public function organisation()
    {
        return $this->belongsTo('App\Models\Organisation','org_id','id');
    }
}
