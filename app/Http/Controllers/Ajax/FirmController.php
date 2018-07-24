<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Firm;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FirmController extends Controller
{
    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $id = $request->input('id');
            $group_id = $request->input('group_id');
            $model = Firm::find($id);
            $model->fill($input);
            if($model->update()) {
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
}
