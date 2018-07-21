<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    //указываем имя таблицы
    protected $table = 'bank_accounts';

    protected $fillable = ['org_id','bank_id','account','currency','date_open','date_close','is_main'];

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank','bank_id','id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organisation','org_id','id');
    }
}
