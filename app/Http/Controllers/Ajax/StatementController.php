<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Lib\LibController;
use App\Models\BankAccount;
use App\Models\Contract;
use App\Models\Firm;
use App\Models\Statement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class StatementController extends Controller
{
    public function findBacc(Request $request){
        if($request->isMethod('post')) {
            $input = $request->except('_token'); //параметр _token нам не нужен
            $accs = BankAccount::where(['org_id' => $input['id']])->get()->sortByDesc('is_main');
            $content = '';
            foreach ($accs as $acc) {
                $content .= '<option value="' . $acc->id . '">' . $acc->account . ' ' . $acc->bank->name . ' ' . $acc->bank->city . '</option>' . PHP_EOL;
            }
            return $content;
        }
    }

    public function findContract(Request $request){
        if($request->isMethod('post')) {
            $input = $request->except('_token'); //параметр _token нам не нужен
            $content = '';
            if(isset($input['firm']) && isset($input['org_id'])){
                $firm_id = Firm::where('name', $input['firm'])->first()->id;
                $contracts = Contract::where('org_id',$input['org_id'])->where('firm_id',$firm_id)->get();
                foreach ($contracts as $contract) {
                    $content .= '<option value="' . $contract->id . '">' . $contract->name . '</option>' . PHP_EOL;
                }
            }
            return $content;
        }
    }

    public function getParams(Request $request){
        if($request->isMethod('post')) {
            $id = $request->input('id');
            $doc = Statement::find($id);
            $params = array();
            $contract = Contract::find($doc->contract)->name;
            array_push($params,$doc->buhcode->code);
            array_push($params,$contract);

            return json_encode($params);
        }
    }

    public function edit(Request $request){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            if(isset($input['firm_id']))
                $input['firm_id'] = Firm::where('name', $input['firm_id'])->first()->id;
            $id = $request->input('id_doc');
            if($id=='new'){
                if(!Role::granted('bank_doc_add')){
                    $msg = 'Попытка создания новой банковской выписки!';
                    //вызываем event
                    event(new AddEventLogs('access',Auth::id(),$msg));
                    return 'NO';
                }
                $model = new Statement();
                $model->doc_num = LibController::GenNumberDoc('statement_docs');
            }
            else{
                $model = Statement::find($id);
                if(!Role::granted('bank_doc_edit')){
                    $msg = 'Попытка редактирования банковской выписки '. $model->doc_num;
                    //вызываем event
                    event(new AddEventLogs('access',Auth::id(),$msg));
                    return 'NO';
                }
            }

            //проверяем, что есть такой договор с фирмой
            $contract = Contract::where('org_id',$input['org_id'])->where('firm_id',$input['firm_id'])->where('name',$input['contract'])->first();
            if(empty($contract))
                $input['contract'] = null;
            else
                $input['contract'] = $contract->id;
            //проверяем, что есть такой банковский счет
            $bacc = BankAccount::where('org_id',$input['org_id'])->where('account',$input['bacc_id'])->first();
            if(empty($bacc))
                return 'NO BACC';
            else
                $input['bacc_id'] = $bacc->id;
            $model->fill($input);
            $model->user_id = Auth::id();
            if($id=='new'){
                if($model->save()) {
                    $msg = 'Новый документ '. $model->doc_num .' добавлен в банковские выписки!';
                    //вызываем event
                    event(new AddEventLogs('info',Auth::id(),$msg));
                    //return 'OK';
                    $content = '<tr id='.$model->id.'>
                        <td>'. $model->created_at .'</td>';
                    if($model->direction == 'coming'){
                        $content.='<td>' . $model->amount . '</td>
                            <td></td>';
                    }
                    else{
                        $content.='<td></td>
                            <td>' . $model->amount . '</td>';
                    }
                    $content.='<td>' . $model->purpose . '</td>
                        <td>' . $model->firm->name . '</td>
                        <td>' . $model->operation->name . '</td>
                        <td>' . $model->organisation->name . '</td>
                        <td>' . $model->bank_account->account . '</td>
                        <td>' . $model->comment . '</td>
                        <td style="width:140px;">
                            <div class="form-group" role="group">
                                <button class="btn btn-info btn-sm doc_clone" type="button" data-toggle="modal" data-target="#editDoc" title="Клонировать документ"><i class="fa fa-clone" aria-hidden="true"></i></button>
                                <button class="btn btn-success btn-sm doc_edit" type="button" data-toggle="modal" data-target="#editDoc" title="Редактировать документ"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                <button class="btn btn-danger btn-sm doc_delete" type="button" title="Удалить документ"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>';
                    return $content;
                }
                else{
                    return 'ERR';
                }
            }
            else{
                if($model->update()) {
                    $msg = 'Данные в банковской выписке '. $model->doc_num .' были обновлены! ID документа '.$model->id;
                    //вызываем event
                    event(new AddEventLogs('info',Auth::id(),$msg));
                    //return 'OK';

                    $content = '<tr id='.$model->id.'>
                        <td>'. $model->created_at .'</td>';
                    if($model->direction == 'coming'){
                        $content.='<td>' . $model->amount . '</td>
                            <td></td>';
                    }
                    else{
                        $content.='<td></td>
                            <td>' . $model->amount . '</td>';
                    }
                    $content.='<td>' . $model->purpose . '</td>
                        <td>' . $model->firm->name . '</td>
                        <td>' . $model->operation->name . '</td>
                        <td>' . $model->organisation->name . '</td>
                        <td>' . $model->bank_account->account . '</td>
                        <td>' . $model->comment . '</td>
                        <td style="width:140px;">
                            <div class="form-group" role="group">
                                <button class="btn btn-info btn-sm doc_clone" type="button" data-toggle="modal" data-target="#editDoc" title="Клонировать документ"><i class="fa fa-clone" aria-hidden="true"></i></button>
                                <button class="btn btn-success btn-sm doc_edit" type="button" data-toggle="modal" data-target="#editDoc" title="Редактировать документ"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                <button class="btn btn-danger btn-sm doc_delete" type="button" title="Удалить документ"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>';
                    return $content;
                }
                else{
                    return 'ERR';
                }
            }
            return 'ERR';
        }
    }

    public function delete(Request $request){
        if($request->isMethod('post')){
            $id = $request->input('id');
            $model = Statement::find($id);
            if(!Role::granted('sale_doc_del')){
                $msg = 'Попытка удаления банковской выписки '. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            if($model->delete()) {
                $msg = 'Банковская выписка '. $model->doc_num .' была удалена!';
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
