<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Group;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(){
        if(view()->exists('refs.group')){
            $parents = Group::where('parent_id',null)->get(); //all();
            $childs = Group::whereNotNull('parent_id')->get();
            $data = [
                'title' => 'Группы',
                'head' => 'Группы контрагентов',
                'groups' => $parents,
                'childs' => $childs
            ];
            //dd($childs);
            return view('refs.group',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой группы контрагентов!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
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
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/groups')->with('status',$msg);
            }
        }
        if(view()->exists('refs.group_add')){
            //выбираем все группы
            $objects = Group::all();
            $grpsel = array();
            foreach ($objects as $object){
                if($object->parent_id)
                    $grpsel[$object->id] = '--'.$object->name;
                else
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

    public function firm_add($id,Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания нового контрагента!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
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

            $firm = new Firm();
            $firm->group_id = $id;
            $firm->fill($input);
            if($firm->save()){
                $msg = 'Контрагент '. $input['name'] .' был успешно добавлен!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/groups/view/'.$id)->with('status',$msg);
            }
        }
        if(view()->exists('refs.group_firm_add')){
            //выбираем все группы
            $object = Group::find($id);
            $parent = Group::where('id',$object['parent_id'])->first();
            $grpsel = array();
            //foreach ($objects as $object){
                $grpsel[$object['id']] = $object['name'];
            //}
            $data = [
                'title' => 'Новая запись',
                'grpsel' => $grpsel,
                'id' => $id,
                'link' => $parent['name'].' / '.$object['name']
            ];
            return view('refs.group_firm_add', $data);
        }
        abort(404);
    }

    public function view($id){
        if(view()->exists('refs.group_firm')) {
            $model = Group::find($id);
            $group = $model->name;
            $parent = $model->parent_id;
            $grpsel = array($id=>$group);
            if($parent){
                $main = Group::find($parent)->name;
                $group = $main.' / '.$group;
                $objects = Group::where(array('parent_id'=>$parent))->get();
                $grpsel = array();
                foreach ($objects as $object){
                    $grpsel[$object->id] = $object->name;
                }
            }
            //выбираем всех контрагентов группы
            $firms = Firm::where(array('group_id' => $id))->get();
            $data = [
                'title' => $group,
                'head' => 'Контрагенты группы "' . $group.'"',
                'firms' => $firms,
                'grpsel' => $grpsel,
                'id' => $id
            ];
            return view('refs.group_firm', $data);
        }
        abort(404);
    }
}
