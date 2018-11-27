<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Buhcode;
use App\Models\CashDoc;
use App\Models\Firm;
use App\Models\Operation;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CashDocController extends Controller
{
    public function index(){
        if(view()->exists('cash_doc')){
            $docs = CashDoc::all()->sortByDesc('created_at')->take(100);
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
                'title' => 'Кассовые документы',
                'head' => 'Журнал кассовых документов',
                'docs' => $docs,
                'orgsel' => $orgsel,
                'opersel' => $opersel,
                'codesel' => $codesel,
            ];

            return view('cash_doc',$data);
        }
        abort(404);
    }

    public function view(Request $request){
        if($request->isMethod('post')){
            $from = $request['from'];
            $to = $request['to'];
            $docs = CashDoc::whereBetween('created_at',[$from, $to])->get();
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
        }
        if(view()->exists('cash_doc')){
            $data = [
                'title' => 'Кассовые документы',
                'head' => 'Журнал кассовых документов',
                'docs' => $docs,
                'orgsel' => $orgsel,
                'opersel' => $opersel,
                'codesel' => $codesel,
            ];

            return view('cash_doc',$data);
        }
        abort(404);
    }

    public function create(Request $request,$direction){
        if(!Role::granted('cash_doc_add')){//вызываем event
            $msg = 'Попытка создания новой записи в журнале кассовых документов!';
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
                'operation_id' => 'required|integer',
                'buhcode_id' => 'required|integer',
                'org_id' => 'required|integer',
                'firm_id' => 'required|integer',
                'amount' => 'required|numeric',
                'contract' => 'nullable|string|max:150',
                'comment' => 'nullable|string|max:200',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('cashDocAdd',['direction'=>$direction])->withErrors($validator)->withInput();
            }

            $doc = new CashDoc();
            $date = $input['created_at'].' H:i:s';
            $input['created_at'] = date($date);
            $doc->fill($input);
            $doc->direction = $direction;
            $doc->user_id = Auth::id();
            if($doc->save()){
                $msg = 'Новый документ '. $input['doc_num'] .' добавлен в журнал кассовых документов!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect('/cash_docs')->with('status',$msg);
            }
        }
        if(view()->exists('cash_doc_add')){
            $doc_num = LibController::GenNumberDoc('cash_docs');
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
            ];
            return view('cash_doc_add', $data);
        }
        abort(404);
    }

    public function cash_book(Request $request){
        if(!Role::granted('reports')){//вызываем event
            $msg = 'Попытка чтения отчета по кассе!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на чтение отчетов!');
        }

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $to = date('Y-m-d', strtotime($input['to'] .' +1 day'));
            $docs = CashDoc::where(['org_id'=>$input['org_id']])->whereBetween('created_at', array($input['from'], $to))->get();
            if(!count($docs)){
                $content='<h2 class="text-center">Нет данных за выбранный период!</h2>';
                $content.='<img src="/images/smile.png" class="img-responsive center-block">';
            }
            else{
                $coming = CashDoc::where('direction','coming')->where('created_at','<',$input['from'])->sum('amount');
                $expense = CashDoc::where('direction','expense')->where('created_at','<',$input['from'])->sum('amount');
                $balance = $coming - $expense;
                $content='';
                $old_date='01.01.1999'; //date("d.m.Y");
                $k = 0;
                $in = 0;
                $out = 0;
                foreach($docs as $doc){
                    $date = DATE_FORMAT($doc->created_at,"d.m.Y");
                    if($date != $old_date) {
                        if($k){
                            $balance = $balance+$in-$out;
                            $content.='<tr>
                                    <th colspan="2"><span class="pull-right">Итого за день</span></th><td></td><th>'.$in.'</th><th>'.$out.'</th>
                                </tr>';
                            if($balance<0){
                                $content.='<tr>
                                    <th colspan="2"><span class="pull-right">Остаток на конец дня</span></th><td></td><th><p class="text-danger">'.$balance.'</p></th><th>X</th>
                                </tr>';
                            }
                            else{
                                $content.='<tr>
                                    <th colspan="2"><span class="pull-right">Остаток на конец дня</span></th><td></td><th>'.$balance.'</th><th>X</th>
                                </tr>';
                            }
                            $in = 0;
                            $out = 0;
                            $content.='</tbody>
                                </table>';
                        }
                        $content .= '<h2 class="text-center">КАССА за ' . $date . '</h2>';
                        $content .= '<table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Номер документа</th>
                                    <th>От кого получено или кому выдано</th>
                                    <th>Номер счета</th>
                                    <th>Приход</th>
                                    <th>Расход</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>';
                                    if($balance<0)
                                        $content.='<th colspan="2"><span class="pull-right">Остаток на начало дня</span></th><td></td><th><p class="text-danger">'.$balance.'</p></th><td>X</td>';
                                    else
                                        $content.='<th colspan="2"><span class="pull-right">Остаток на начало дня</span></th><td></td><th>'.$balance.'</th><td>X</td>';
                                $content.='</tr>';
                    }
                    if($doc->direction=='coming'){
                        $in+=$doc->amount;
                        $content.='<tr><td>'.$doc->doc_num.'</td><td>Принято от '.$doc->firm->name.'</td><td>'.$doc->buhcode->code.'</td><td>'.$doc->amount.'</td><td></td></tr>';
                    }
                    if($doc->direction=='expense'){
                        $out+=$doc->amount;
                        $content.='<tr><td>'.$doc->doc_num.'</td><td>Выдано '.$doc->firm->name.'</td><td>'.$doc->buhcode->code.'</td><td></td><td>'.$doc->amount.'</td></tr>';
                    }
                    $old_date = $date;
                    $k++;
                }
                //для последней таблицы
                $balance = $balance+$in-$out;
                $content.='<tr>
                                    <th colspan="2"><span class="pull-right">Итого за день</span></th><td></td><th>'.$in.'</th><th>'.$out.'</th>
                                </tr>';
                $content.='<tr>
                                    <th colspan="2"><span class="pull-right">Остаток на конец дня</span></th><td></td><th>'.$balance.'</th><th>X</th>
                                </tr>';
                $content.='</tbody>
                                </table>';
            }
        }

        if(view()->exists('reports.cash_book')){
            $data = [
                'title' => 'Кассовая книга',
                'head' => 'Кассовая книга',
                'content' => $content,
            ];
            return view('reports.cash_book', $data);
        }
        abort(404);
    }

    public function set_filter(){
        if(view()->exists('reports.cash_book_filter')){
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $data = [
                'title' => 'Выбор периода',
                'head' => 'Выбор периода',
                'orgsel' => $orgsel,
            ];
            return view('reports.cash_book_filter', $data);
        }
        abort(404);
    }

}
