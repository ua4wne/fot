<?php

namespace App\Http\Controllers;

use App\Models\Buhcode;
use Illuminate\Http\Request;
use Excel;
use PHPExcel_IOFactory;

class CodeExcelController extends Controller
{
    public function importCode(Request $request){

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
                    $code = $row[0];
                    $text = $row[1];
                    // Получение кода по свойствам, или создание нового экземпляра
                    $code = Buhcode::firstOrCreate(array('code'=>$code,'text'=>$text));
                    if($code->save())
                        $num++;
                }
            }
            $msg = 'Обработано записей: '.$num;
            return redirect('/codes')->with('status',$msg);
        }
    }
}
