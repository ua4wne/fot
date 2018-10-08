<?php

namespace App\Http\Controllers\Ajax;

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
            $model = Statement::find($id);
            if(!Role::granted('sale_doc_edit')){
                $msg = 'Попытка редактирования банковской выписки '. $model->doc_num;
                //вызываем event
                event(new AddEventLogs('access',Auth::id(),$msg));
                return 'NO';
            }
            //проверяем, что есть такой договор с фирмой
            $contract = Contract::where('org_id',$input['org_id'])->where('firm_id',$input['firm_id'])->where('name',$input['contract'])->first();
            if(empty($contract))
                return 'NO CONTRACT';
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
            if($model->update()) {
                $msg = 'Данные в банковской выписке '. $model->doc_num .' были обновлены! ID документа '.$model->id;
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
