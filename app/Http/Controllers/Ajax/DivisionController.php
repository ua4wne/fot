<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи в справочнике подразделений!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            return 'NO';
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = new Division();
            $model->fill($input);
            if($model->save()) {
                $msg = 'Подразделение '. $input['name'] .' было успешно добавлено!';
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
            $id = $request->input('id_dvsn');
            $model = Division::find($id);
            if(!Role::granted('ref_doc_edit')){
                $msg = 'Попытка редактирования подразделения '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            if($model->update()) {
                $msg = 'Запись о подразделении '. $input['name'] .' была обновлена!';
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
            $model = Division::find($id);
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления подразделения '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Подразделение '. $model->name .' было удалено!';
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
