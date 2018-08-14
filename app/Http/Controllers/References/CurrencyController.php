<?php

namespace App\Http\Controllers\references;

use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Support\Facades\Auth;
use Validator;

class CurrencyController extends Controller
{
    public function index(){
        if(view()->exists('refs.currency')){
            $money = Currency::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Валюты',
                'head' => 'Справочник валют',
                'money' => $money,
            ];

            return view('refs.currency',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи в справочнике валют!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'unique' => 'Значение поля должно быть уникальным!',
                'numeric' => 'Значение поля должно содержать только цифры!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|unique:currency|max:70',
                'dcode' => 'required|unique:currency|numeric',
                'scode' => 'required|unique:currency|max:5',
                'cource' => 'required|numeric',
                'multi' => 'nullable|numeric'
            ],$messages);
            if($validator->fails()){
                return redirect()->route('currencyAdd')->withErrors($validator)->withInput();
            }

            $money = new Currency();
            $money->fill($input);
            if($money->save()){
                $msg = 'Валюта '. $input['name'] .' была успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/currency')->with('status',$msg);
            }
        }
        if(view()->exists('refs.currency_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.currency_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Currency::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления валюты '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Валюта '. $model->name .' была удалена!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/currency')->with('status',$msg);
        }
        if(!Role::granted('ref_doc_edit')){
            $msg = 'Попытка редактирования валюты '. $model->name;
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'numeric' => 'Значение поля должно содержать только цифры!',
            ];
            $validator = Validator::make($input,[
                'cource' => 'required|numeric',
                'multi' => 'nullable|numeric'
            ],$messages);
            if($validator->fails()){
                return redirect()->route('currencyEdit',['currency'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Валюта '. $model->name .' обновлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/currency')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('refs.currency_edit')){
            $data = [
                'title' => 'Редактирование валюты '.$old['name'],
                'data' => $old
            ];
            return view('refs.currency_edit',$data);
        }
        abort(404);
    }
}
