<?php

namespace App\Http\Controllers;

use App\Models\Buhcode;
use App\Models\Operation;
use App\Models\Organisation;
use App\Models\Statement;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class StatementController extends Controller
{
    public function index(){
        if(view()->exists('statements')){
            $now = date('Y-m-d');
            $arr = explode('-',$now);
            $year = $arr[0];
            $month = $arr[1];
            $day = $arr[2];
            $from = date('Y-m-d', strtotime("$year-$month-$day -1 month"));
            $to = date('Y-m-d');
            //dd($from);
            $docs = Statement::whereBetween('created_at',[$from, $to])->get();
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
                'title' => 'Банковские выписки',
                'head' => 'Банковские выписки',
                'docs' => $docs,
                'orgsel' => $orgsel,
                'opersel' => $opersel,
                'codesel' => $codesel,
            ];

            return view('statements',$data);
        }
        abort(404);
    }
}
