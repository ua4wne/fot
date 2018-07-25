<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Export\FirmsExport;
use App\Models\Firm;
use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function importFirm(Request $request){

        if($request->hasFile('file')){
            $path = $request->file('file')->getRealPath();
            $data = Excel::load($path)->get();

            if($data->count()){
                foreach ($data as $key => $value) {
                    $arr[] = ['title' => $value->title, 'body' => $value->body];
                }

                if(!empty($arr)){
                    DB::table('products')->insert($arr);
                    dd('Insert Recorded successfully.');
                }
            }

        }
        dd('Request data does not have any files to import.');
    }

    public function exportFirm($type){
        $file = 'export.'.$type;
        return Excel::download(new FirmsExport(),$file);
    }
}
