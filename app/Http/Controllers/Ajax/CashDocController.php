<?php

namespace App\Http\Controllers\Ajax;

use App\Models\CashDoc;
use App\Models\Firm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CashDocController extends Controller
{
    public function ajaxData(Request $request){
        $query = $request->get('query','');
        $firms = Firm::where('name','LIKE','%'.$query.'%')->get();
        return response()->json($firms);
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            if(isset($input['firm_id']))
                $input['firm_id'] = Firm::where('name', $input['firm_id'])->first()->id;
            $id = $request->input('id_doc');
            $model = CashDoc::find($id);
            if(!Role::granted('cash_doc_edit')){
                $msg = 'Попытка редактирования кассового документа '. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            $model->fill($input);
            $model->user_id = Auth::id();
            if($model->update()) {
                $msg = 'Данные в кассовом документе '. $model->doc_num .' были обновлены! ID документа '.$model->id;
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return 'OK';
            }
            else{
                return 'ERR';
            }
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = CashDoc::find($id);
            if(!Role::granted('cash_doc_del')){
                $msg = 'Попытка удаления кассового документа '. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Кассовый документ '. $model->doc_num .' был удален!';
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
