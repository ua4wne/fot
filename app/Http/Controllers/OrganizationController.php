<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Division;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index(){
        if(view()->exists('refs.orgs')){
            $orgs = Organisation::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Организации',
                'head' => 'Справочник организаций',
                'orgs' => $orgs,
            ];

            return view('refs.orgs',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой организации!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'unique' => 'Значение поля должно быть уникальным!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|unique:orgs|max:90',
                'full_name' => 'required|unique:orgs|max:180',
                'inn' => 'nullable|max:12',
                'kpp' => 'nullable|max:9',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('orgAdd')->withErrors($validator)->withInput();
            }

            $org = new Organisation();
            $org->fill($input);
            if($org->save()){
                $msg = 'Организация '. $input['name'] .' была успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/organization')->with('status',$msg);
            }
        }
        if(view()->exists('refs.org_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.org_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Organisation::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления организации '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Организация '. $model->name .' была удалена!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/organization')->with('status',$msg);
        }
        if(!Role::granted('ref_doc_edit')){
            $msg = 'Попытка редактирования организации '. $model->name;
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'inn' => 'nullable|max:12',
                'bik' => 'nullable|max:9',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('orgEdit',['org'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные организации '. $model->name .' обновлены!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/organization')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('refs.org_edit')){
            $data = [
                'title' => 'Редактирование организации '.$old['name'],
                'data' => $old
            ];
            return view('refs.org_edit',$data);
        }
        abort(404);
    }

    public function view($id){

        if(view()->exists('refs.org_view')){
            $org = Organisation::find($id);
            $divs = $org->divisions;
            $bacc = $org->bankaccounts;
            //выбираем все банки
            $banks = Bank::all();
            $banksel = array();
            foreach ($banks as $bank){
                $banksel[$bank->id] = $bank->name;
            }
            $data = [
                'title' => 'Данные организации '.$org['name'],
                'org' => $org,
                'divs' => $divs,
                'banksel' => $banksel,
                'baccs' => $bacc
            ];
            return view('refs.org_view',$data);
        }
        abort(404);
    }
}
