<?php

namespace App\Http\Controllers\Options;

use App\Models\Action;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ActionController extends Controller
{
    public function index(){
        if(!User::hasRole('admin')){
            abort(503);
        }
        if(view()->exists('options.actions')){
            $title='Список разрешений';
            $actions = Action::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => $title,
                'head' => 'Список разрешений в системе',
                'actions' => $actions,
            ];
            return view('options.actions',$data);
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
                'code' => 'required|max:70|unique:actions',
                'name' => 'required|max:100',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('actionAdd')->withErrors($validator)->withInput();
            }

            $action = new Action();
            $action->fill($input);
            if($action->save()){
                $msg = 'Разрешение '. $input['name'] .' было успешно добавлено!';
                return redirect('/actions')->with('status',$msg);
            }
        }
        if(view()->exists('options.action_add')){
            $data = [
                'title' => 'Новая запись',
            ];
            return view('options.action_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        if(!User::hasRole('admin')){
            abort(503);
        }
        $model = Action::find($id);
        if($request->isMethod('delete')){
            $model->delete();
            $msg = 'Разрешение '. $model->name .' было удалено!';
        }
        return redirect('/actions')->with('status',$msg);
    }
}
