<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    public function index(){
        if(view()->exists('refs.persons')){
            $persons = Person::all();
            $data = [
                'title' => 'Физлица',
                'head' => 'Физические лица',
                'persons' => $persons,
            ];

            return view('refs.persons',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('personals')){//вызываем event
            $msg = 'Попытка создания новой записи в справочнике физических лиц!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'string' => 'Значение поля должно быть строковым!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'fio' => 'required|string|max:100',
                'inn' => 'nullable|max:12',
                'snils' => 'nullable|max:11',
                'gender' => 'in:male,female',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('personAdd')->withErrors($validator)->withInput();
            }

            $person = new Person();
            $person->fill($input);
            if($person->save()){
                $msg = 'Запись о физлице '. $input['fio'] .' была успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/persons')->with('status',$msg);
            }
        }
        if(view()->exists('refs.person_add')){
            $data = [
                'title' => 'Новая запись'
            ];
            return view('refs.person_add', $data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        $model = Person::find($id);
        if($request->isMethod('delete')){
            if(!Role::granted('personals')){
                $msg = 'Попытка удаления записи о физическом лице '.$model->fio;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $model->delete();
            $msg = 'Запись о физическом лице '. $model->fio .' была удалена!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/persons')->with('status',$msg);
        }
        if(!Role::granted('personals')){
            $msg = 'Попытка редактирования записи о физическом лице '. $model->fio;
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'string' => 'Значение поля должно быть строковым!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'fio' => 'required|string|max:100',
                'inn' => 'nullable|max:12',
                'snils' => 'nullable|max:11',
                'gender' => 'in:male,female',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('personEdit',['id'=>$model->id])->withErrors($validator)->withInput();
            }
            $model->fill($input);
            if($model->update()){
                $msg = 'Данные записи о физическом лице '. $model->fio .' обновлены!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/persons')->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Currency
        if(view()->exists('refs.person_edit')){
            $data = [
                'title' => 'Редактирование записи '.$old['fio'],
                'data' => $old
            ];
            return view('refs.person_edit',$data);
        }
        abort(404);
    }
}
