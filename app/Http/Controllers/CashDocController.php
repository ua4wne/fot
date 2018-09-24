<?php

namespace App\Http\Controllers;

use App\Models\CashDoc;
use Illuminate\Http\Request;

class CashDocController extends Controller
{
    public function index(){
        if(view()->exists('cash_doc')){
            $docs = CashDoc::all();
            $data = [
                'title' => 'Кассовые документы',
                'head' => 'Журнал кассовых документов',
                'docs' => $docs,
            ];

            return view('cash_doc',$data);
        }
        abort(404);
    }
}
