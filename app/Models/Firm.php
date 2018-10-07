<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    //указываем имя таблицы
    protected $table = 'firms';

    protected $fillable = ['type', 'name', 'full_name', 'group_id', 'inn', 'kpp', 'acc_id'];

    public function contract()
    {
        return $this->hasOne('App\Models\Contract');
    }
}
