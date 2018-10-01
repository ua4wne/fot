<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    //указываем имя таблицы
    protected $table = 'statements';

    protected $fillable = ['user_id', 'doc_num', 'direction', 'operation_id', 'buhcode_id', 'org_id', 'bacc_id', 'firm_id', 'amount', 'contract', 'purpose', 'comment'];

    public function operation()
    {
        return $this->belongsTo('App\Models\Operation');
    }

    public function buhcode()
    {
        return $this->belongsTo('App\Models\Buhcode');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organisation');
    }

    public function firm()
    {
        return $this->belongsTo('App\Models\Firm');
    }
}
