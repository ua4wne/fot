<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Firm;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class FirmController extends Controller
{
    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $id = $request->input('id');
            $group_id = $request->input('group_id');
            $model = Firm::find($id);
            if(!Role::granted('ref_doc_edit')){
                $msg = 'Попытка редактирования контрагента '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            if($model->update()) {
                $msg = 'Данные контрагента '. $input['name'] .' были обновлены!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                $group = Group::find($group_id)->name;
                $arr = array('id'=>$id,'type'=>$request->input('type'),'name'=>$request->input('name'),'fname'=>$request->input('full_name'),
                            'group'=>$group,'inn'=>$request->input('inn'),'kpp'=>$request->input('kpp'));
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
            $model = Firm::find($id);
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления контрагента '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Контрагент '. $model->name .' был удален!';
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
