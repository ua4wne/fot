<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            $from = $request['from'];
            $to = $request['to'];
        }
        else{
            $now = date('Y-m-d');
            $arr = explode('-',$now);
            $year = $arr[0];
            $month = $arr[1];
            $day = $arr[2];
            $from = date('Y-m-d', strtotime("$year-$month-$day -1 month"));
            $to = date('Y-m-d', strtotime("$year-$month-$day +1 day"));
        }
        if(view()->exists('sales')){
            $docs = Sale::whereBetween('created_at',[$from, $to])->get();
            $data = [
                'title' => 'Реализация',
                'head' => 'Документы реализаций',
                'docs' => $docs,
            ];

            return view('sales',$data);
        }
        abort(404);
    }
}
