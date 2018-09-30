<?php

namespace App\Http\Controllers;

use App\Models\CashDoc;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        if(view()->exists('main_index')){
            $coming = CashDoc::where('direction','coming')->sum('amount');
            $expense = CashDoc::where('direction','expense')->sum('amount');
            $kassa = $coming - $expense;
            $kassa = round($kassa,2);
            return view('main_index',[
                'title'=>'Финплан',
                'kassa' => $kassa,
            ]);
        }
        abort(404);
    }
}
