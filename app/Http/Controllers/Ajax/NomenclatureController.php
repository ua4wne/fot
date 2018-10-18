<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Nomenclature;
use App\Models\NomenclatureGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;

class NomenclatureController extends Controller
{
    public function createGroup(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой группы номенклатуры!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            return 'ERR';
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'string' => 'Значение поля должно быть строкой!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'name' => 'required|string|max:70',
            ],$messages);
            if($validator->fails()){
                return 'Поле должно быть строкой не более 70 символов!';
            }
            $model = new NomenclatureGroup();
            $model->fill($input);
            if($model->save()) {
                $msg = 'Группа номенклатуры '. $input['name'] .' успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function create(Request $request){
        if(!Role::granted('ref_doc_add')){//вызываем event
            $msg = 'Попытка создания новой номенклатуры!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            return 'ERR';
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'string' => 'Значение поля должно быть строкой!',
                'max' => 'Значение поля должно быть не более :max символов!',
            ];
            $validator = Validator::make($input,[
                'group_id' => 'required|integer',
                'name' => 'required|string|max:70',
                'unit' => 'required|string|max:7',
            ],$messages);
            if($validator->fails()){
                return 'NO';
            }
            $model = new Nomenclature();
            $model->fill($input);
            if($model->save()) {
                $msg = 'Номенклатура '. $input['name'] .' успешно добавлена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return '<tr id="'.$model->id.'">
                                                        <td>'.$model->name.'</td>
                                                        <td>'.$model->unit.'</td>
                                                        <td style="width:70px;">
                                                            <div class="form-group" role="group">
                                                                <button class="btn btn-danger btn-sm btn_delete" type="button" title="Удалить запись"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Nomenclature::find($id);
            if(!Role::granted('ref_doc_del')){
                $msg = 'Попытка удаления номенклатуры '. $model->name;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Номенклатура '. $model->name .' была удалена!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}
