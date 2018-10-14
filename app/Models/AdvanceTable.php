<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceTable extends Model
{
    //указываем имя таблицы
    protected $table = 'advance_tables';

    protected $fillable = ['advance_id', 'text', 'firm_id', 'contract_id', 'comment', 'amount', 'buhcode_id'];

    public function advance()
    {
        return $this->belongsTo('App\Models\Advance','advance_id','id');
    }

    public function buhcode()
    {
        return $this->belongsTo('App\Models\Buhcode');
    }

    public function firm()
    {
        return $this->belongsTo('App\Models\Firm');
    }

    public function contract()
    {
        return $this->belongsTo('App\Models\Contract');
    }
}
