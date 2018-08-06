<?php

namespace App\Http\Controllers\Ajax;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function switchLogin(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $active = $request->input('active');
            if($id==1)
                return 'NOT';
            $user = User::find($id);
            $user->active = $active;
            if($user->update())
                return 'OK';
            else
                return 'ERR';
        }
    }
}
