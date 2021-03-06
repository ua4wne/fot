<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function index(){
        if(view()->exists('refs.division')){
            $divs = Division::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Подразделения',
                'head' => 'Список подразделений',
                'divs' => $divs,
            ];

            return view('refs.division',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания нового подразделения!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|max:100',
                'org_id' => 'required',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('divisionAdd')->withErrors($validator)->withInput();
            }

            $division = new Division();
            $division->fill($input);
            if($division->save()){
                $msg = 'Подразделение '. $input['name'] .' было успешно добавлено!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/divisions')->with('status',$msg);
            }
        }
        if(view()->exists('refs.division_add')){
            //выбираем все организации
            $objects = Organisation::all();
            $orgsel = array();
            foreach ($objects as $object){
                $orgsel[$object->id] = $object->name;
            }
            $data = [
                'title' => 'Новая запись',
                'orgsel' => $orgsel
            ];
            return view('refs.division_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Division::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления подразделения '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Подразделение '. $model->name .' было удалено!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/divisions')->with('status',$msg);
        }
        if(!Role::granted('ref_doc_edit')){
            $msg = 'Попытка редактирования подразделения '. $model->name;
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|max:100',
                'org_id' => 'required',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('divisionEdit',['id'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные подразделения '. $model->name .' обновлены!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/divisions')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('refs.division_edit')){
            //выбираем все объекты
            $objects = Organisation::all();
            $orgsel = array();
            foreach ($objects as $object){
                $orgsel[$object->id] = $object->name;
            }
            $data = [
                'title' => 'Редактирование подразделения '.$old['name'],
                'data' => $old,
                'orgsel' => $orgsel
            ];
            return view('refs.division_edit',$data);
        }
        abort(404);
    }
}
