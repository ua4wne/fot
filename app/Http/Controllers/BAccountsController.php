<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class BAccountsController extends Controller
{
    public function index(){
        if(view()->exists('refs.bacc')){
            $bacc = BankAccount::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Банковские счета',
                'head' => 'Банковские счета организаций',
                'bacc' => $bacc,
            ];

            return view('refs.bacc',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания нового банковского счета!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'integer' => 'Значение поля должно быть числовым!',
            ];
            $validator = Validator::make($input,[
                'org_id' => 'required|integer',
                'bank_id' => 'required|integer',
                'is_main' => 'required|integer',
                'account' => 'required|string|max:25',
                'currency' => 'required|string|max:5',
                'date_open' => 'nullable',
                'date_close' => 'nullable',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('baccAdd')->withErrors($validator)->withInput();
            }

            $acc = new BankAccount();
            $acc->fill($input);
            //dump($acc);
            if($acc->save()){
                $msg = 'Банковский счет '. $input['account'] .' был успешно добавлен!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/bacc')->with('status',$msg);
            }
        }
        if(view()->exists('refs.bacc_add')){
            //выбираем все организации
            $objects = Organisation::all();
            $orgsel = array();
            foreach ($objects as $object){
                $orgsel[$object->id] = $object->name;
            }
            //выбираем все банки
            $banks = Bank::all();
            $banksel = array();
            foreach ($banks as $bank){
                $banksel[$bank->id] = $bank->name;
            }
            $data = [
                'title' => 'Новая запись',
                'orgsel' => $orgsel,
                'banksel' => $banksel
            ];
            return view('refs.bacc_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = BankAccount::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления банковского счета '.$model->account;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Банковский счет '. $model->account .' был удален!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/bacc')->with('status',$msg);
        }
        if(!Role::granted('ref_doc_edit')){
            $msg = 'Попытка редактирования банковского счета '.$model->account;
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'integer' => 'Значение поля должно быть числовым!',
            ];
            $validator = Validator::make($input,[
                'org_id' => 'required|integer',
                'bank_id' => 'required|integer',
                'is_main' => 'required|integer',
                'account' => 'required|string|max:25',
                'currency' => 'required|string|max:5',
                'date_open' => 'nullable',
                'date_close' => 'nullable',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('baccEdit',['id'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные счета '. $model->account .' обновлены!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/bacc')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели BankAccount
        if(view()->exists('refs.bacc_edit')){
            //выбираем все организации
            $objects = Organisation::all();
            $orgsel = array();
            foreach ($objects as $object){
                $orgsel[$object->id] = $object->name;
            }
            //выбираем все банки
            $banks = Bank::all();
            $banksel = array();
            foreach ($banks as $bank){
                $banksel[$bank->id] = $bank->name;
            }
            $data = [
                'title' => 'Редактирование счета '.$old['account'],
                'data' => $old,
                'orgsel' => $orgsel,
                'banksel' => $banksel
            ];
            return view('refs.bacc_edit',$data);
        }
        abort(404);
    }
}
