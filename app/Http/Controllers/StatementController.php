<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Buhcode;
use App\Models\Firm;
use App\Models\Operation;
use App\Models\Organisation;
use App\Models\Statement;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class StatementController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            $from = $request['from'];
            $to = $request['to'];
            $docs = Statement::whereBetween('created_at',[$from, $to])->get();
        }
        else{
            //$docs = Statement::all()->sortByDesc('created_at')->take(100);
            $docs = Statement::orderBy('created_at', 'desc')->take(100)->get();
        }
        if(view()->exists('statements')){
            $operations = Operation::select(['id','name'])->get();
            $opersel = array();
            foreach ($operations as $operation){
                $opersel[$operation->id] = $operation->name;
            }
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $buxcodes = Buhcode::select(['id','code'])->where(['show'=>1])->get();
            $codesel = array();
            foreach ($buxcodes as $buxcode){
                $codesel[$buxcode->id] = $buxcode->code;
            }
            $data = [
                'title' => 'Банковские выписки',
                'head' => 'Банковские выписки',
                'docs' => $docs,
                'orgsel' => $orgsel,
                'opersel' => $opersel,
                'codesel' => $codesel,
            ];

            return view('statements',$data);
        }
        abort(404);
    }

    public function create(Request $request,$direction){
        if(!Role::granted('bank_doc_add')){//вызываем event
            $msg = 'Попытка создания новой банковской выписки!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            if(isset($input['firm_id']))
                $input['firm_id'] = Firm::where('name', $input['firm_id'])->first()->id;
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'string' => 'Значение поля должно быть строковым!',
                'integer' => 'Значение поля должно быть числовым!',
                'numeric' => 'Поле должно иметь корректное числовое или дробное значение!',
            ];
            $validator = Validator::make($input,[
                'doc_num' => 'required|string|max:15',
                'created_at' => 'required|date',
                'operation_id' => 'required|integer',
                'buhcode_id' => 'required|integer',
                'bacc_id' => 'required|integer',
                'org_id' => 'required|integer',
                'purpose' => 'required|string|max:250',
                'firm_id' => 'required|integer',
                'amount' => 'required|numeric',
                'contract' => 'nullable|integer',
                'comment' => 'nullable|string|max:200',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('statementAdd',['direction'=>$direction])->withErrors($validator)->withInput();
            }
            $doc = new Statement();
            $doc->fill($input);
            $doc->direction = $direction;
            $doc->user_id = Auth::id();
            if($doc->save()){
                $msg = 'Новый документ '. $input['doc_num'] .' добавлен в банковские выписки!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/statements')->with('status',$msg);
            }
        }
        if(view()->exists('bank_doc_add')){
            $doc_num = LibController::GenNumberDoc('statement_docs');
            $operations = Operation::select(['id','name'])->get();
            $opersel = array();
            foreach ($operations as $operation){
                $opersel[$operation->id] = $operation->name;
            }
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $buxcodes = Buhcode::select(['id','code'])->where(['show'=>1])->get();
            $codesel = array();
            foreach ($buxcodes as $buxcode){
                $codesel[$buxcode->id] = $buxcode->code;
            }
            $data = [
                'title' => 'Новый документ',
                'direction' => $direction,
                'doc_num' => $doc_num,
                'orgsel' => $orgsel,
                'opersel' => $opersel,
                'codesel' => $codesel,
                'bacc' => array(),
            ];
            return view('bank_doc_add', $data);
        }
        abort(404);
    }

    public function set_filter(){
        if(view()->exists('reports.acct_filter')){
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $codes = Buhcode::select(['id','code'])->where(['show'=>1])->get();
            $codsel = array();
            foreach ($codes as $code){
                $codsel[$code->id] = $code->code;
            }
            $data = [
                'title' => 'Карточка счета',
                'head' => 'Карточка счета',
                'orgsel' => $orgsel,
                'codsel' => $codsel,
            ];
            return view('reports.acct_filter', $data);
        }
        abort(404);
    }

    public function acct_report(Request $request){
        if(!Role::granted('reports')){//вызываем event
            $msg = 'Попытка чтения отчета по счету!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на чтение отчетов!');
        }

        if($request->isMethod('post')) {
            $input = $request->except('_token'); //параметр _token нам не нужен
            $to = date('Y-m-d', strtotime($input['to'] . ' +1 day'));
        }
        if(view()->exists('reports.acct')){
            /*$data = [
                'title' => 'Кассовая книга',
                'head' => 'Кассовая книга',
                'content' => $content,
            ];
            return view('reports.acct', $data);*/
        }
        abort(404);
    }

    public function balance_filter(){
        if(view()->exists('reports.balance_filter')){
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $codes = Buhcode::select(['id','code'])->where(['show'=>1])->get();
            $codsel = array();
            foreach ($codes as $code){
                $codsel[$code->id] = $code->code;
            }
            $data = [
                'title' => 'Оборотно-сальдовая ведомость по счету',
                'head' => 'Оборотно-сальдовая ведомость',
                'orgsel' => $orgsel,
                'codsel' => $codsel,
            ];
            return view('reports.balance_filter', $data);
        }
        abort(404);
    }

    public function balance_report(Request $request){
        if(!Role::granted('reports')){//вызываем event
            $msg = 'Попытка чтения оборотно-сальдовой ведомости по счету!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на чтение отчетов!');
        }

        if($request->isMethod('post')) {
            $input = $request->except('_token'); //параметр _token нам не нужен
            $to = date('Y-m-d', strtotime($input['to'] . ' +1 day'));
        }
        if(view()->exists('reports.balance')){
            /*$data = [
                'title' => 'Кассовая книга',
                'head' => 'Кассовая книга',
                'content' => $content,
            ];
            return view('reports.balance', $data);*/
        }
        abort(404);
    }
}
