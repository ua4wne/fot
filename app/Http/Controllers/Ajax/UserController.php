<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
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

    public function addRole(Request $request){
        if($request->isMethod('post')){
            $user_id = $request->input('id');
            DB::table('role_user')->where('user_id', '=', $user_id)->delete(); //удаляем предыдущие роли пользователя
            $roles = $request->input('roles');
            if(empty($roles))
                return 'NO';
            $values = array();
            foreach ($roles as $role){
                $role_id = Role::where('code',$role)->first()->id; //получили ID role
                $date = date('Y-m-d H:i:s');
                $val = array('role_id'=>$role_id,'user_id'=>$user_id,'created_at'=>$date,'updated_at'=>$date);
                array_push($values, $val);
            }
            if(DB::table('role_user')->insert($values))
                return 'OK';
            else
                return 'ERR';
        }
    }

    public function getRole(Request $request){
        if($request->isMethod('post')) {
            $id = $request->input('id');
            $roles = User::find($id)->roles;
            $code = array();
            foreach ($roles as $role){
                array_push($code,$role->code);
            }
            return json_encode($code);
        }
    }
}
