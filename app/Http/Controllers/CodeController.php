<?php

namespace App\Http\Controllers;

use App\Models\Buhcode;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CodeController extends Controller
{
    public function index(){
        if(view()->exists('refs.bcode')){
            $codes = Buhcode::all();
            $data = [
                'title' => 'План счетов',
                'head' => 'План счетов бухгалтерского учета',
                'codes' => $codes,
            ];

            return view('refs.bcode',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи в плане счетов!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'boolean' => 'Значение поля должно быть булевым 0 или 1!',
                'unique' => 'Значение поля должно быть уникальным!',
                'string' => 'Значение поля должно быть строковым!',
            ];
            $validator = Validator::make($input,[
                'show' => 'required|boolean',
                'code' => 'required|string|max:5|unique:buhcodes',
                'text' => 'required|string|max:255',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('codeAdd')->withErrors($validator)->withInput();
            }

            $acc = new Buhcode();
            $acc->fill($input);
            if($acc->save()){
                $msg = 'Счет учета '. $input['code'] .' был успешно добавлен в план счетов бухучета!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/codes')->with('status',$msg);
            }
        }
        if(view()->exists('refs.bcode_add')){
            $data = [
                'title' => 'Новая запись',
            ];
            return view('refs.bcode_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Buhcode::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления счета '.$model->code.' из плана счетов бухучета!';
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Счет '. $model->code .' был удален из плана счетов бухучета!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/codes')->with('status',$msg);
        }
        if(!Role::granted('ref_doc_edit')){
            $msg = 'Попытка редактирования счета '.$model->code.' в плане счетов бухучета!';
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'boolean' => 'Значение поля должно быть булевым 0 или 1!',
                'string' => 'Значение поля должно быть строковым!',
            ];
            $validator = Validator::make($input,[
                'show' => 'required|boolean',
                'text' => 'required|string|max:255',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('codeEdit',['id'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные счета '. $model->code .' обновлены в плане счетов бухучета!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/codes')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели BankAccount
        if(view()->exists('refs.bcode_edit')){
            $data = [
                'title' => 'Редактирование счета '.$old['code'],
                'data' => $old,
            ];
            return view('refs.bcode_edit',$data);
        }
        abort(404);
    }
}
