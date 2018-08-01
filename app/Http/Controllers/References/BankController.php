<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use Validator;

class BankController extends Controller
{
    public function index(){
        if(view()->exists('refs.banks')){
            $banks = Bank::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Банки',
                'head' => 'Справочник банков',
                'banks' => $banks,
            ];

            return view('refs.banks',$data);
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
                'bik' => 'required|unique:banks|max:10',
                'swift' => 'required|unique:banks|max:15',
                'name' => 'required|unique:banks|max:70',
                'account' => 'required|unique:banks|max:30',
                'city' => 'nullable|max:50',
                'address' => 'nullable|max:100',
                'phone' => 'nullable|max:70',
                'country' => 'nullable|max:50',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('bankAdd')->withErrors($validator)->withInput();
            }

            $banks = new Bank();
            $banks->fill($input);
            if($banks->save()){
                $msg = 'Банк '. $input['name'] .' был успешно добавлен!';
                return redirect('/banks')->with('status',$msg);
            }
        }
        if(view()->exists('refs.bank_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.bank_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Bank::find($id);
        if($request->isMethod('delete')){
            //$model = Currency::find($id);
            $model->delete();
            $msg = 'Банк '. $model->name .' был удален!';
            return redirect('/banks')->with('status',$msg);
        }

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'city' => 'nullable|max:50',
                'address' => 'nullable|max:100',
                'phone' => 'nullable|max:70',
                'country' => 'nullable|max:50',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('bankEdit',['bank'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные банка '. $model->name .' обновлены!';
                return redirect('/banks')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('refs.bank_edit')){
            $data = [
                'title' => 'Редактирование банка '.$old['name'],
                'data' => $old
            ];
            return view('refs.bank_edit',$data);
        }
        abort(404);
    }
}
