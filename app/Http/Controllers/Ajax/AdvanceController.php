<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Advance;
use App\Models\AdvanceTable;
use App\Models\Contract;
use App\Models\Firm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AdvanceController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $model = new Advance();
            if(!Role::granted('finance')){
                $msg = 'Попытка создания документа авансового отчета!'. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            $model->user_id = Auth::id();
            if($model->save()) {
                $msg = 'Добавлен новый авансовый отчет. Документ №'. $model->doc_num .' ID документа '.$model->id;
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return $model->id;
            }
            else{
                return null;
            }
        }
    }

    public function addPosition(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $firm = $input['firm_id'];
            if(isset($firm))
                $input['firm_id'] = Firm::where('name', $firm)->first()->id;
            $id = $input['id_doc'];
            $model = new AdvanceTable();
            $model->advance_id = $id;
            $model->fill($input);
            if($model->save()) {
                $advance = Advance::find($id);
                $advance->amount = $advance->amount + $model->amount;
                $advance->update();
                $content = '<tr id="'.$model->id.'" class="advance_pos">
                    <td>'.$model->text.'</td>
                    <td>'.$firm.'</td>
                    <td>'.$model->contract->name.'</td>                
                    <td>'.$model->comment.'</td>
                    <td>'.$model->amount.'</td>
                    <td>'.$model->buhcode->code.'</td>                  
                    <td style="width:70px;">
                        <div class="form-group" role="group">
                            <button class="btn btn-danger btn-sm pos_delete" type="button" title="Удалить позицию"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        </div>
                    </td>
                </tr>';
                return $content;
            }
            else{
                return null;
            }
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Advance::find($id);
            if(!Role::granted('finance')){
                $msg = 'Попытка удаления документа авансового отчета '. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            AdvanceTable::where(['advance_id'=>$id])->delete(); //удаляем связанные записи
            if($model->delete()) {
                $msg = 'Авансовый отчет '. $model->doc_num .' был удален!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function deletePosition(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = AdvanceTable::find($id);
            if(!Role::granted('finance')){
                $msg = 'Попытка удаления позиции документа авансового отчета '. $model->advance->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }
}
