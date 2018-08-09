<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Action;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{
    public function getAction(Request $request){
        if($request->isMethod('post')) {
            $id = $request->input('id');
            $actions = Role::find($id)->actions;
            $code = array();
            foreach ($actions as $action){
                array_push($code,$action->code);
            }
            return json_encode($code);
        }
    }

    public function addAction(Request $request){
        if($request->isMethod('post')){
            $role_id = $request->input('id');
            DB::table('action_role')->where('role_id', '=', $role_id)->delete(); //удаляем предыдущие разрешения для роли
            $actions = $request->input('actions');
            if(empty($actions))
                return 'NO';
            $values = array();
            foreach ($actions as $action){
                $action_id = Action::where('code',$action)->first()->id; //получили ID action
                $date = date('Y-m-d H:i:s');
                $val = array('role_id'=>$role_id,'action_id'=>$action_id,'created_at'=>$date,'updated_at'=>$date);
                array_push($values, $val);
            }
            if(DB::table('action_role')->insert($values))
                return 'OK';
            else
                return 'ERR';
        }
    }
}
