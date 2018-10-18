<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //указываем имя таблицы
    protected $table = 'sales';

    protected $fillable = ['user_id', 'doc_num', 'org_id', 'firm_id', 'buhcode_id', 'contract', 'comment'];

    public function sale_table()
    {
        return $this->hasMany('App\Models\SaleTable','id','sale_id');
    }

    public function amount(){
        return $this->sale_table()->sum('amount');
    }

    public function firm()
    {
        return $this->belongsTo('App\Models\Firm','firm_id','id');
    }

    public function buhcode()
    {
        return $this->belongsTo('App\Models\Buhcode','buhcode_id','id');
    }

    public function organisation()
    {
        return $this->belongsTo('App\Models\Organisation','org_id','id');
    }
}
