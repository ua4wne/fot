<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;
use Validator;

class OrganizationController extends Controller
{
    public function index(){
        if(view()->exists('refs.orgs')){
            $orgs = Organisation::paginate(15); //all();
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
            //$model = Currency::find($id);
            $model->delete();
            $msg = 'Организация '. $model->name .' была удалена!';
            return redirect('/organization')->with('status',$msg);
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
}
