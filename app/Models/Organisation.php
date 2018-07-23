<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    //указываем имя таблицы
    protected $table = 'orgs';

    protected $fillable = ['name', 'full_name', 'inn', 'kpp'];

    public function division()
    {
        return $this->hasMany('App\Models\Division','org_id','id');
    }

    public function bankaccounts()
    {
        return $this->hasMany('App\Models\BankAccount','org_id','id');
    }
}
