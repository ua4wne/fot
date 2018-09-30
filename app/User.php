<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','login','active','image','sex'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Роли, принадлежащие пользователю.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    public static function hasRole($code){
        // получить id текущего залогиненного юзера
        $user_id = Auth::id();
        $roles = User::find($user_id)->roles;
        foreach ($roles as $role){
            if($role->code==$code)
                return TRUE;
        }
        return FALSE;
    }
}
