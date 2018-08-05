<?php

namespace App\Http\Controllers\Options;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class UserController extends Controller
{
    public function index(){
        if(view()->exists('options.users')){
            $title='Учетные записи';
            //$users = User::all();
            $users = User::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => $title,
                'head' => 'Учетные записи пользователей',
                'users' => $users,
            ];
            return view('options.users',$data);
        }
        abort(404);
    }
}
