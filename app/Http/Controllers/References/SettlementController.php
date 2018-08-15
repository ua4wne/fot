<?php

namespace App\Http\Controllers\References;

use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    public function index(){
        if(view()->exists('refs.settlements')){
            $settls = Settlement::paginate(env('PAGINATION_SIZE'));
            $data = [
                'title' => 'Виды расчетов',
                'head' => 'Виды расчетов',
                'settls' => $settls,
            ];

            return view('refs.settlements',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания нового вида расчетов!';
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
                'name' => 'required|unique:settlements|max:100',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('settlementAdd')->withErrors($validator)->withInput();
            }

            $settl = new Settlement();
            $settl->fill($input);
            if($settl->save()){
                $msg = 'Новый вид расчета '. $input['name'] .' был успешно добавлен!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/settlements')->with('status',$msg);
            }
        }
        if(view()->exists('refs.settl_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.settl_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Settlement::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления вида расчета '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Вид расчета '. $model->name .' был удален!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
        }
        return redirect('/settlements')->with('status',$msg);
    }
}
