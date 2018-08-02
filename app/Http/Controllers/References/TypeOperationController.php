<?php

namespace App\Http\Controllers\References;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

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
            $model->delete();
            $msg = 'Операция '. $model->name .' была удалена!';
        }
        return redirect('/operations')->with('status',$msg);
    }
}
