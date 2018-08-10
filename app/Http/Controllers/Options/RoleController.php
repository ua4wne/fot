<?php

namespace App\Http\Controllers\Options;

use App\Models\Action;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class RoleController extends Controller
{
    public function index(){
        if(!User::hasRole('admin')){
            abort(503);
        }
        if(view()->exists('options.roles')){
            $title='Список ролей';
            $roles = Role::paginate(env('PAGINATION_SIZE')); //all();
            $actions = Action::all();
            $data = [
                'title' => $title,
                'head' => 'Список системных ролей',
                'roles' => $roles,
                'actions' => $actions,
            ];
            return view('options.roles',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!User::hasRole('admin')){
            abort(503);
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'unique' => 'Значение поля должно быть уникальным!',
            ];
            $validator = Validator::make($input,[
                'code' => 'required|max:70|unique:roles',
                'name' => 'required|max:100',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('roleAdd')->withErrors($validator)->withInput();
            }

            $role = new Role();
            $role->fill($input);
            if($role->save()){
                $msg = 'Системная роль '. $input['name'] .' была успешно добавлена!';
                return redirect('/roles')->with('status',$msg);
            }
        }
        if(view()->exists('options.role_add')){
            $data = [
                'title' => 'Новая запись',
            ];
            return view('options.role_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        if(!User::hasRole('admin')){
            abort(503);
        }
        $model = Role::find($id);
        if($request->isMethod('delete')){
            $model->delete();
            $msg = 'Системная роль '. $model->name .' была удалена!';
        }
        return redirect('/roles')->with('status',$msg);
    }

    public static function granted($code){
        // получить id текущего залогиненного юзера
        $user_id = Auth::id();
        //получаем все роли текущего юзера
        $roles = User::find($user_id)->roles;
        $full = Action::where('code','=','admin')->first()->id; //находим id действия с полными правами
        if($roles){
            //проверяем разрешения ролей
            foreach($roles as $role){
                //находим действия для роли
                $actions = Role::find($role->id)->actions;
                foreach ($actions as $action){
                    if($action->code=='admin'||$action->code==$code)
                        return TRUE;
                }
            }
        }
        return FALSE;
    }
}
