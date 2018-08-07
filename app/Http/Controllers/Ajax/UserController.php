<?php

namespace App\Http\Controllers\Ajax;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

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

    public function editLogin(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $user = User::find($input['id']);
            $user->fill($input);
            if($input['id']==1)
                $user->active = 1; //первый админ всегда активен!!!
            if($user->update())
                return 'OK';
            else
                return 'ERR';
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = User::find($id);
            if($model->delete()) {
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}
