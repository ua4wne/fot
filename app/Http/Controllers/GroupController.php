<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Validator;

class GroupController extends Controller
{
    public function index(){
        if(view()->exists('refs.group')){
            $groups = Group::orderBy('parent_id', 'asc')->paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Группы',
                'head' => 'Группы контрагентов',
                'groups' => $groups,
            ];

            return view('refs.group',$data);
        }
        abort(404);
    }

    public function create(Request $request){

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'integer' => 'Значение поля должно содержать только цифры!'
            ];
            $validator = Validator::make($input,[
                'name' => 'required|max:100',
                'parent_id' => 'nullable|integer',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('groupAdd')->withErrors($validator)->withInput();
            }

            $group = new Group();
            $group->fill($input);
            if($group->save()){
                $msg = 'Группа '. $input['name'] .' была успешно добавлена!';
                return redirect('/groups')->with('status',$msg);
            }
        }
        if(view()->exists('refs.group_add')){
            //выбираем все группы
            $objects = Group::all();
            $grpsel = array();
            foreach ($objects as $object){
                $grpsel[$object->id] = $object->name;
            }
            $data = [
                'title' => 'Новая запись',
                'grpsel' => $grpsel
            ];
            return view('refs.group_add', $data);
        }
        abort(404);
    }
}
