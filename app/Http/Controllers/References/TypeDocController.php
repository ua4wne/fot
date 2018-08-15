<?php

namespace App\Http\Controllers\References;

use App\Models\Typedoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class TypeDocController extends Controller
{
    public function index(){
        if(view()->exists('refs.type_docs')){
            $tdocs = Typedoc::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => 'Виды договоров',
                'head' => 'Виды договоров',
                'tdocs' => $tdocs,
            ];

            return view('refs.type_docs',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи в справочнике Виды договоров!';
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
                'name' => 'required|unique:typedocs|max:100',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('typedocAdd')->withErrors($validator)->withInput();
            }

            $tdoc = new Typedoc();
            $tdoc->fill($input);
            if($tdoc->save()){
                $msg = 'Новый тип договора '. $input['name'] .' был успешно добавлен!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/typedocs')->with('status',$msg);
            }
        }
        if(view()->exists('refs.tdoc_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.tdoc_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Typedoc::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления вида договора '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Вид договора '. $model->name .' был удален!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
        }
        return redirect('/typedocs')->with('status',$msg);
    }
}
