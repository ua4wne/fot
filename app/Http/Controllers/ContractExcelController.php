<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Organisation;
use App\Models\Settlement;
use App\Models\Typedoc;
use Illuminate\Http\Request;
use Excel;
use PHPExcel_IOFactory;
use App\Models\Firm;
use App\Events\AddEventLogs;
use Illuminate\Support\Facades\Auth;

class ContractExcelController extends Controller
{
    public function importContract(Request $request){

        if($request->hasFile('file')){
            $path = $request->file('file')->getRealPath();
            $excel = PHPExcel_IOFactory::load($path);
            // Цикл по листам Excel-файла
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                // выгружаем данные из объекта в массив
                $tables[] = $worksheet->toArray();
            }
            $num=0;

            // Цикл по листам Excel-файла
            foreach( $tables as $table ) {
                $rows = count($table);
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $doc_num = LibController::GenNumberDoc('contracts');
                    $name = $row[0];
                    $date = $row[1];
                    if(empty($date)){
                        $date = date('Y-m-d');
                    }
                    else{
                        $tmp = explode('.', $date);
                        $date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0];
                    }
                    $firm = $row[2];
                    $firm_id = Firm::firstOrCreate(array('name'=>$firm))->id;
                    $typedoc = $row[3];
                    $tdoc_id = Typedoc::firstOrCreate(array('name'=>$typedoc))->id;
                    $stop = $row[4];
                    if(!empty($stop)){
                        $tmp = explode('.', $stop);
                        $stop = $tmp[2].'-'.$tmp[1].'-'.$tmp[0];
                    }
                    $settlement = $row[5];
                    if(!empty($settlement)){
                        $settlement_id = Settlement::firstOrCreate(array('name'=>$settlement))->id;
                    }
                    else{
                        $settlement_id = null;
                    }
                    $currency = Currency::where(['dcode'=>643])->first()->id;
                    $org = $row[7];
                    $org_id = Organisation::firstOrCreate(array('full_name'=>$org))->id;

                    // Получение документа по свойствам, или создание нового экземпляра
                    $doc = Contract::firstOrCreate(array('num_doc'=>$doc_num,'name'=>$name,'tdoc_id'=>$tdoc_id,'org_id'=>$org_id,'firm_id'=>$firm_id,
                        'start'=>$date,'stop'=>$stop,'currency_id'=>$currency,'settlement_id'=>$settlement_id,'created_at'=>$date));
                    if($doc->save())
                        $num++;
                }
            }
            $msg = 'Выполнение импорта договоров из файла Excel!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect('/contracts')->with('status','Обработано записей: '.$num);
        }
    }
}
