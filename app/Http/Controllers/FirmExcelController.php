<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Export\FirmsExport;
use App\Models\Firm;
use App\Models\Group;
use Illuminate\Http\Request;
use Excel;
use PHPExcel_IOFactory;

class FirmExcelController extends Controller
{
    public function importFirm(Request $request){

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
                $name = $table[0][0];
                $group = Group::where(['name'=>$name])->get();
                $group_id = $group[0]['id'];
                for($i=1;$i<$rows;$i++){
                    $row = $table[$i];
                    $name = $row[0];
                    $inn = $row[1];
                    $full_name = $row[2];
                    // Получение контрагента по свойствам, или создание нового экземпляра
                    $firm = Firm::firstOrCreate(array('name'=>$name,'inn'=>$inn,'full_name'=>$full_name));
                    $firm->group_id = $group_id;
                    if(!empty($group_id)){
                        if($firm->save())
                            $num++;
                    }
                }
            }
            $msg = 'Обработано записей: '.$num;
            return redirect('/firms')->with('status',$msg);
        }
    }

    public function exportFirm($type){
        $file = 'export.'.$type;
        //$file = 'export.pdf';
        return Excel::download(new FirmsExport(),$file);

    }
}
