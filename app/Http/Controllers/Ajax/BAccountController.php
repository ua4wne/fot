<?php

namespace App\Http\Controllers\Ajax;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class BAccountController extends Controller
{
    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи банковского счета!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            return 'NO';
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = new BankAccount();
            $model->fill($input);
            if($model->save()) {
                $msg = 'Банковский счет '. $input['account'] .' был успешно добавлен!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $id = $request->input('id_acc');
            $model = BankAccount::find($id);
            if(!Role::granted('ref_doc_edit')){
                $msg = 'Попытка редактирования банковского счета '. $model->account;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            if($model->update()) {
                $msg = 'Запись о банковском счете '. $input['account'] .' была обновлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = BankAccount::find($id);
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления банковского счета '. $model->account;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Банковский счет '. $model->account .' был удален!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}
