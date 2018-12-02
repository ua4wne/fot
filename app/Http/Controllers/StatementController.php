<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Buhcode;
use App\Models\Firm;
use App\Models\Operation;
use App\Models\Organisation;
use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class StatementController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            $from = $request['from'];
            //$to = $request['to'];
            $to = date('Y-m-d', strtotime($request['to'] .' +1 day'));
            $docs = Statement::whereBetween('created_at',[$from, $to])->get();
            //сохраняем выбранные значения в сессии
            Session::put('from', $from);
            Session::put('to', $to);
        }
        else{
            //ищем ранее сохраненные в сессии значения периода
            $from = Session::get('from');
            $to = Session::get('to');
            if(empty($from) && empty($to))
                $docs = Statement::orderBy('created_at', 'desc')->take(100)->get();
            else
                $docs = Statement::whereBetween('created_at',[$from, $to])->get();
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
            $date = $input['created_at'].' H:i:s';
            $input['created_at'] = date($date);
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
            $m_to = date('m', strtotime($input['to']));
            $y_to = ' '.date('Y', strtotime($input['to'])).' г.';
            $m_from = date('m', strtotime($input['from']));
            $y_from = ' '.date('Y', strtotime($input['from'])).' г.';
            $code = Buhcode::find($input['code_id'])->code;
            $org = Organisation::find($input['org_id'])->name;
            $coming = Statement::where(['direction'=>'coming','buhcode_id'=>$input['code_id'],'org_id'=>$input['org_id']])->where('created_at','<',$input['from'])->sum('amount');
            $expense = Statement::where(['direction'=>'expense','buhcode_id'=>$input['code_id'],'org_id'=>$input['org_id']])->where('created_at','<',$input['from'])->sum('amount');
            $balance = $coming - $expense;
            if($m_to==$m_from && $y_to==$y_from)
                $content='<h2 class="text-center">Карточка счета '.$code.' за '.self::SetMonth(date($m_to)).$y_to.' '.$org.'</h2>';
            else
                $content='<h2 class="text-center">Карточка счета '.$code.' за '.self::SetMonth(date($m_from)).$y_from.' - '.self::SetMonth(date($m_to)).$y_to.' '.$org.'</h2>';

            $content .= '<table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Период</th>
                                    <th>Документ</th>
                                    <th>Назначение платежа</th>
                                    <th>Дебет</th>
                                    <th>Кредит</th>
                                    <th>Текущее сальдо</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>';
            if($balance<0)
                $content.='<th colspan="5"><span class="pull-right">Сальдо на начало</span></th><th><p class="text-danger">'.$balance.'</p></th>';
            else
                $content.='<th colspan="5"><span class="pull-right">Сальдо на начало</span></th><th>'.$balance.'</th>';
            $content.='</tr>';
            $debet=0;
            $credit=0;
            //определяем по каким документам делать выборку
            if($code=='50.01'){
                //касса
            }
            else{
                //банковские выписки
                $docs = Statement::where(['buhcode_id'=>$input['code_id'],'org_id'=>$input['org_id']])->whereBetween('created_at',[$input['from'], $to])->get();
                foreach ($docs as $doc){
                    $content.='<tr id="'.$doc->id.'"><td>'.$doc->created_at.'</td>';
                    if($doc->direction=='expense'){
                        $content.='<td>Списание с расчетного счета <br>'.$doc->doc_num.'</td>';
                    }
                    else{
                        $content.='<td>Поступление на расчетный счет <br>'.$doc->doc_num.'</td>';
                    }
                    $pos = strpos($doc->purpose, 'Сумма');
                    if($pos)
                        $purpose = substr($doc->purpose, 0, $pos);
                    else
                        $purpose = $doc->purpose;
                    $content.='<td>'.$doc->firm->name.'<br>'.$purpose.'</td>';
                    if($doc->direction=='expense'){
                        $content.='<td></td><td>'.$doc->amount.'</td>';
                        $credit+=$doc->amount;
                        $balance-=$doc->amount;
                    }
                    else{
                        $content.='<td>'.$doc->amount.'</td><td></td>';
                        $debet+=$doc->amount;
                        $balance+=$doc->amount;
                    }
                    $content.='<td>'.$balance.'</td>';
                    $content.='</tr>';
                }
            }
            $content.='<tr><th colspan="3"><span class="pull-right">Обороты за период и сальдо на конец</span></th><th>'.$debet.'</th><th>'.$credit.'</th><th>'.$balance.'</th>';
            $content.='<tbody></table>';
        }
        if(view()->exists('reports.acct')){
            $data = [
                'title' => 'Карточка счета',
                'head' => 'Карточка счета',
                'content' => $content,
            ];
            return view('reports.acct', $data);
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

    //выборка всех месяцев
    private static function GetMonths(){
        return array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль',
            '08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь',);
    }
    //возвращаем название месяца по номеру
    public static function SetMonth($id){
        $months = self::GetMonths();
        foreach ($months as $key=>$month){
            if($key == $id)
                return mb_strtolower($month,'UTF-8');
        }
    }
}
