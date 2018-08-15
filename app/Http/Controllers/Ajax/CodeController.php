<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Buhcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CodeController extends Controller
{
    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $id = $request->input('id');
            $model = Buhcode::find($id);
            if(!Role::granted('ref_doc_edit')){
                $msg = 'Попытка редактирования счета '. $model->code.' в плане счетов бухучета!';
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            if($model->update()) {
                $msg = 'Данные счета '. $model->code .' были обновлены в плане счетов бухучета!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                $arr = array('id'=>$id,'text'=>$request->input('text'),'show'=>$request->input('show'));
                return json_encode($arr, JSON_UNESCAPED_UNICODE);
            }
            else{
                return 'ERR';
            }
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Buhcode::find($id);
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления счета '. $model->code.' из плана счетов бухучета!';
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Счет '. $model->code .' был удален из плана счетов бухучета!';
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
