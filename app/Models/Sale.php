<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //указываем имя таблицы
    protected $table = 'sales';

    protected $fillable = ['user_id', 'doc_num', 'org_id', 'firm_id', 'buhcode_id', 'currency_id', 'contract_id', 'comment'];

    public function sale_table()
    {
        return $this->hasMany('App\Models\SaleTable','id','sale_id');
    }

    public function firm()
    {
        return $this->belongsTo('App\Models\Firm','firm_id','id');
    }

    public function contract()
    {
        return $this->belongsTo('App\Models\Contract','contract_id','id');
    }

    public function buhcode()
    {
        return $this->belongsTo('App\Models\Buhcode','buhcode_id','id');
    }

    public function organisation()
    {
        return $this->belongsTo('App\Models\Organisation','org_id','id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_id','id');
    }
}
