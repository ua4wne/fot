<?php

namespace App\Http\Controllers\References;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class TypeOperationController extends Controller
{
    public function index(){
        if(view()->exists('refs.oper_type')){
            $opers = Operation::paginate(env('PAGINATION_SIZE'));
            $data = [
                'title' => 'Виды операций',
                'head' => 'Виды операций',
                'opers' => $opers,
            ];

            return view('refs.oper_type',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи в справочнике Виды операций!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'unique' => 'Значение поля должно быть уникальным!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|unique:operations|max:100',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('operationAdd')->withErrors($validator)->withInput();
            }

            $oper = new Operation();
            $oper->fill($input);
            if($oper->save()){
                $msg = 'Операция '. $input['name'] .' была успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/operations')->with('status',$msg);
            }
        }
        if(view()->exists('refs.oper_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.oper_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Operation::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления операции '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Операция '. $model->name .' была удалена!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
        }
        return redirect('/operations')->with('status',$msg);
    }
}
