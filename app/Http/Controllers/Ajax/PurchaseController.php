<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Firm;
use App\Models\Purchase;
use App\Models\PurchaseTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $input = $request->except(['_token','buhcode']); //параметр _token нам не нужен
            $firm = $input['firm_id'];
            if(isset($firm))
                $input['firm_id'] = Firm::where('name', $firm)->first()->id;
            $input['buhcode_id'] = $request['buhcode'];
            $model = new Purchase();
            if(!Role::granted('sale_doc_add')){
                $msg = 'Попытка создания документа поступления!'. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            $model->user_id = Auth::id();
            if($model->save()) {
                $msg = 'Добавлен новый документ поступления. Документ №'. $model->doc_num .' ID документа '.$model->id;
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
            $id = $input['id_doc'];
            $model = new PurchaseTable();
            $model->purchase_id = $id;
            $model->amount = $input['qty'] * $input['price'];
            $model->fill($input);
            if($model->save()) {

                $content = '<tr id="'.$model->id.'" class="purchase_pos">
                    <td>'.$model->nomenclature->name.'</td>
                    <td>'.$model->qty.'</td>                
                    <td>'.$model->price.'</td>
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

    public function deletePosition(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = PurchaseTable::find($id);
            if(!Role::granted('sale_doc_del')){
                $msg = 'Попытка удаления позиции документа поступления '. $model->sale->doc_num;
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

    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except(['_token','buhcode']); //параметр _token нам не нужен
            if(!Role::granted('sale_doc_edit')){
                $msg = 'Попытка редактирования документа поступления №'. $input['doc_num'];
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $id = $input['id'];
            $model = Purchase::find($id);
            if(!empty($model)){
                if(isset($input['firm_id']))
                    $input['firm_id'] = Firm::where('name', $input['firm_id'])->first()->id;
                $input['buhcode_id'] = $request['buhcode'];
                $model->fill($input);
                $model->user_id = Auth::id();
                if($model->update()) {
                    $msg = 'Был отредактирован документ поступленияи №'. $input['doc_num'] .' ID документа '.$id;
                    //вызываем event
                    event(new AddEventLogs('info',Auth::id(),$msg));
                    return $model->id;
                }
            }
            else{
                return null;
            }
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Purchase::find($id);
            if(!Role::granted('sale_doc_del')){
                $msg = 'Попытка удаления документа поступления '. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            PurchaseTable::where(['purchase_id'=>$id])->delete(); //удаляем связанные записи
            if($model->delete()) {
                $msg = 'Документ поступления №'. $model->doc_num .' был удален!';
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
