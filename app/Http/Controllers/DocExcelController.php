<?php

namespace App\Http\Controllers;

use App\Models\Buhcode;
use App\Models\CashDoc;
use App\Models\Operation;
use App\Models\Organisation;
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
}
