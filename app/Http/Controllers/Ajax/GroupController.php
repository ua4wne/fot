<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой группы контрагентов!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            return 'NO';
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = new Group();
            $model->fill($input);
            if($model->save()) {
                $msg = 'Группа контрагентов '. $input['name'] .' была успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                $row = Group::all()->last();
                $content = '<tr id="'.$row->id.'">
                                <th><a href="/groups/view/'.$row->id.'">'.$row->name.'</a></th>
                                <td style="width:110px;">
                                    <div class="form-group" role="group">
                                        <button class="btn btn-success btn-sm group_edit" type="button" data-toggle="modal" data-target="#editGroup"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button>
                                        <button class="btn btn-danger btn-sm group_delete" type="button"><i class="fa fa-trash fa-lg>" aria-hidden="true"></i></button>
                                    </div>
                                </td>
                           </tr>';
                return $content;
            }
            else{
                return 'ERR';
            }
        }
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $id = $request->input('id');
            $model = Group::find($id);
            if(!Role::granted('ref_doc_edit')){
                $msg = 'Попытка редактирования группы контрагентов '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            if($model->update()) {
                $msg = 'Группа контрагентов '. $input['name'] .' была обновлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                $arr = array('id'=>$id,'name'=>$request->input('name'));
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
            $model = Group::find($id);
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления группы контрагентов '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Группа контрагентов '. $model->name .' была удалена!';
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
