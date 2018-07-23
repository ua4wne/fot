<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = new Group();
            $model->fill($input);
            if($model->save()) {
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
            $model->fill($input);
            if($model->update()) {
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
            if($model->delete()) {
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}
