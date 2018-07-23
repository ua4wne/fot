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
