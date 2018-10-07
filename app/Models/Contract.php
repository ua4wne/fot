<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    //указываем имя таблицы
    protected $table = 'contracts';

    protected $fillable = ['num_doc','name', 'tdoc_id', 'org_id', 'firm_id', 'text', 'start', 'stop', 'currency_id','settlement_id'];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organisation');
    }

    public function typedoc()
    {
        return $this->belongsTo('App\Models\Typedoc');
    }

    public function firm()
    {
        return $this->belongsTo('App\Models\Firm');
    }

    public function settlement()
    {
        return $this->belongsTo('App\Models\Settlement');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }
}
