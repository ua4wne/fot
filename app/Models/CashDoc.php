<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashDoc extends Model
{
    //указываем имя таблицы
    protected $table = 'cash_docs';

    protected $fillable = ['user_id', 'doc_num', 'direction', 'operation_id', 'buhcode_id', 'org_id', 'firm_id', 'amount', 'contract', 'comment'];

    public function operation()
    {
        return $this->belongsTo('App\Models\Operation');
    }

    public function buhcode()
    {
        return $this->belongsTo('App\Models\Buhcode');
    }

    public function organisation()
    {
        return $this->belongsTo('App\Models\Organisation','org_id','id');
    }

    public function firm()
    {
        return $this->belongsTo('App\Models\Firm');
    }

}
