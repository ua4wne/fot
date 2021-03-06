<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\BankAccount;
use App\Models\Buhcode;
use App\Models\CashDoc;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Operation;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Statement;
use App\User;
use Illuminate\Http\Request;
use Excel;
use PHPExcel_IOFactory;
use App\Models\Firm;
use App\Events\AddEventLogs;
use Illuminate\Support\Facades\Auth;

class DocExcelController extends Controller
{
    public function importCashDoc(Request $request){

        if($request->hasFile('file')){
            $path = $request->file('file')->getRealPath();
            $excel = PHPExcel_IOFactory::load($path);
            // Цикл по листам Excel-файла
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                // выгружаем данные из объекта в массив
                $tables[] = $worksheet->toArray();
            }
            $num=0;
            $buh_code = Buhcode::where(array('code'=>'50.01'))->first()->id;
            $default = Firm::where(array('name'=>'Не указано'))->first()->id;
            // Цикл по листам Excel-файла
            foreach( $tables as $table ) {
                $rows = count($table);
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $date = $row[0];
                    $dt1 = explode(' ',$date);
                    $tmp = explode('.', $dt1[0]);
                    $date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0].' '.$dt1[1];
                    $doc_num = $row[1];
                    if($row[2]>0){
                        $direction = 'coming';
                        $amount = $row[2];
                    }
                    else{
                        $direction = 'expense';
                        $amount = $row[3];
                    }
                    $amount = str_replace(',','',$amount);
                    $firm = $row[5];
                    if(empty($firm)){
                        $firm_id = $default;
                    }
                    else{
                        // Получение контрагента по свойствам, или создание нового экземпляра
                        $firm_id = Firm::firstOrCreate(array('name'=>$firm))->id;
                    }
                    $oper = $row[6];
                    $oper_id = Operation::firstOrCreate(array('name'=>$oper))->id;
                    $org = $row[7];
                    $org_id = Organisation::firstOrCreate(array('name'=>$org))->id;
                    $user = $row[8];
                    $user_id = User::where(array('name'=>$user))->first()->id;
                    $comment = $row[9];
                    // Получение документа по свойствам, или создание нового экземпляра
                    $doc = CashDoc::firstOrCreate(array('user_id'=>$user_id,'doc_num'=>$doc_num,'direction'=>$direction,'operation_id'=>$oper_id,'buhcode_id'=>$buh_code,
                                                    'org_id'=>$org_id,'firm_id'=>$firm_id,'amount'=>$amount,'comment'=>$comment,'created_at'=>$date));
                    //$doc->created_at = $date;
                    if($doc->save())
                            $num++;
                }
            }
            $msg = 'Выполнение импорта из файла Excel в журнал кассовых документов!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/cash_docs')->with('status','Обработано записей: '.$num);
        }
    }

    public function importStatements(Request $request){
        if($request->hasFile('file')){
            set_time_limit(300);
            $path = $request->file('file')->getRealPath();
            $excel = PHPExcel_IOFactory::load($path);
            // Цикл по листам Excel-файла
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                // выгружаем данные из объекта в массив
                $tables[] = $worksheet->toArray();
            }
            $num=0;
            $buh_code = Buhcode::where(array('code'=>'51'))->first()->id;
            $default = 'Не указано';
            // Цикл по листам Excel-файла
            foreach( $tables as $table ) {
                $rows = count($table);
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $date = $row[0];
                    $tmp = explode('.', $date);
                    $date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0]. ' 00:00:00';
                    if($row[1]>0){
                        $direction = 'coming';
                        $amount = $row[1];
                    }
                    else{
                        $direction = 'expense';
                        $amount = $row[2];
                    }
                    $amount = str_replace(',','',$amount);
                    $purpose = $row[3];
                    if(empty($purpose))
                        $purpose = $default;
                    $firm = $row[4];
                    $firm_id = Firm::firstOrCreate(array('name'=>$firm))->id;
                    $oper = $row[5];
                    $oper_id = Operation::firstOrCreate(array('name'=>$oper))->id;
                    $org = $row[6];
                    $org_id = Organisation::firstOrCreate(array('name'=>$org))->id;
                    $barr = explode(',',$row[7]);
                    $bacc_id = BankAccount::firstOrCreate(array('account'=>$barr[0],'org_id'=>$org_id))->id;
                    $doc_num = $row[8];
                    $user = $row[9];
                    $user_id = User::where(array('name'=>$user))->first()->id;
                    $comment = $row[10];
                    // Получение документа по свойствам, или создание нового экземпляра
                    $doc = Statement::firstOrCreate(array('user_id'=>$user_id,'doc_num'=>$doc_num,'direction'=>$direction,'operation_id'=>$oper_id,'buhcode_id'=>$buh_code,
                        'org_id'=>$org_id,'bacc_id'=>$bacc_id,'firm_id'=>$firm_id,'amount'=>$amount,'purpose'=>$purpose,'comment'=>$comment,'created_at'=>$date));
                    $doc->created_at = $date;
                    if($doc->save())
                        $num++;
                }
            }
            $msg = 'Выполнение импорта из файла Excel банковских выписок!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/statements')->with('status','Обработано записей: '.$num);
        }
    }

    public function importAdvances(Request $request){
        if($request->hasFile('file')){
            //set_time_limit(300);
            $path = $request->file('file')->getRealPath();
            $excel = PHPExcel_IOFactory::load($path);
            // Цикл по листам Excel-файла
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                // выгружаем данные из объекта в массив
                $tables[] = $worksheet->toArray();
            }
            $num=0;
            $currency_id = Currency::where(array('dcode'=>'643'))->first()->id;
            // Цикл по листам Excel-файла
            foreach( $tables as $table ) {
                $rows = count($table);
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $date = $row[0];
                    $dt1 = explode(' ',$date);
                    $tmp = explode('.', $dt1[0]);
                    $date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0].' '.$dt1[1];
                    $doc_num = $row[1];
                    $person = $row[2];
                    $person_id = Person::firstOrCreate(array('fio'=>$person))->id;
                    $amount = $row[3];
                    $amount = str_replace(',','',$amount);
                    $org = $row[5];
                    $org_id = Organisation::firstOrCreate(array('name'=>$org))->id;
                    $user = $row[6];
                    $user_id = User::where(array('name'=>$user))->first()->id;
                    // Получение документа по свойствам, или создание нового экземпляра
                    $doc = Advance::firstOrCreate(array('user_id'=>$user_id,'doc_num'=>$doc_num,'person_id'=>$person_id,'amount'=>$amount,'currency_id'=>$currency_id,'org_id'=>$org_id,'created_at'=>$date));
                    $doc->created_at = $date;
                    if($doc->save())
                        $num++;
                }
            }
            $msg = 'Выполнение импорта из файла Excel банковских выписок!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/advances')->with('status','Обработано записей: '.$num);
        }
    }

    public function importSales(Request $request){
        if($request->hasFile('file')){
            set_time_limit(300);
            $path = $request->file('file')->getRealPath();
            $excel = PHPExcel_IOFactory::load($path);
            // Цикл по листам Excel-файла
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                // выгружаем данные из объекта в массив
                $tables[] = $worksheet->toArray();
            }
            $num=0;
            $currency_id = Currency::where(array('dcode'=>'643'))->first()->id;
            $buhcode_id = Buhcode::where(array('code'=>'76.06'))->first()->id;
            // Цикл по листам Excel-файла
            foreach( $tables as $table ) {
                $rows = count($table);
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $date = $row[0];
                    $dt1 = explode(' ',$date);
                    $tmp = explode('.', $dt1[0]);
                    $date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0].' '.$dt1[1];
                    $doc_num = $row[1];
                    $firm = $row[2];
                    $firm_id = Firm::firstOrCreate(array('name'=>$firm))->id;
                    $org = $row[5];
                    $org_id = Organisation::firstOrCreate(array('full_name'=>$org))->id;
                    $contract_id = Contract::where('org_id',$org_id)->where('firm_id',$firm_id)->first()->id;
                    $user = $row[6];
                    $user_id = User::where(array('name'=>$user))->first()->id;
                    $comment = $row[7];
                    // Получение документа по свойствам, или создание нового экземпляра
                    $doc = Sale::firstOrCreate(array('user_id'=>$user_id,'doc_num'=>$doc_num,'org_id'=>$org_id,'firm_id'=>$firm_id,'buhcode_id'=>$buhcode_id,
                        'currency_id'=>$currency_id,'contract_id'=>$contract_id,'comment'=>$comment,'created_at'=>$date));
                    $doc->created_at = $date;
                    if($doc->save())
                        $num++;
                }
            }
            $msg = 'Выполнение импорта из файла Excel реализаций!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/sales')->with('status','Обработано записей: '.$num);
        }
    }

    public function importPurchases(Request $request){
        if($request->hasFile('file')){
            set_time_limit(300);
            $path = $request->file('file')->getRealPath();
            $excel = PHPExcel_IOFactory::load($path);
            // Цикл по листам Excel-файла
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                // выгружаем данные из объекта в массив
                $tables[] = $worksheet->toArray();
            }
            $num=0;
            $currency_id = Currency::where(array('dcode'=>'643'))->first()->id;
            $buhcode_id = Buhcode::where(array('code'=>'76.05'))->first()->id;
            // Цикл по листам Excel-файла
            foreach( $tables as $table ) {
                $rows = count($table);
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $date = $row[0];
                    $dt1 = explode(' ',$date);
                    $tmp = explode('.', $dt1[0]);
                    $date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0].' '.$dt1[1];
                    $doc_num = $row[1];
                    $firm = $row[2];
                    $firm_id = Firm::firstOrCreate(array('name'=>$firm))->id;
                    $org = $row[5];
                    $org_id = Organisation::firstOrCreate(array('full_name'=>$org))->id;
                    $comment = $row[6];
                    $user = $row[7];
                    $user_id = User::where(array('name'=>$user))->first()->id;
                    // Получение документа по свойствам, или создание нового экземпляра
                    $doc = Purchase::firstOrCreate(array('user_id'=>$user_id,'doc_num'=>$doc_num,'org_id'=>$org_id,'firm_id'=>$firm_id,'buhcode_id'=>$buhcode_id,
                        'currency_id'=>$currency_id,'comment'=>$comment,'created_at'=>$date));
                    $doc->created_at = $date;
                    if($doc->save())
                        $num++;
                }
            }
            $msg = 'Выполнение импорта из файла Excel реализаций!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/purchases')->with('status','Обработано записей: '.$num);
        }
    }
}
