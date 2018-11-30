<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Organisation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function balance_graph(Request $request){
        if($request->isMethod('post')) {

            $id = $request->input('id');
            if($id=='graph'){
                $year = date('Y');
                //выбираем свои организации
                $data=array();
                $datas=array();
                for($i=0;$i<12;$i++){
                    $data[$i]=['x'=>$i+1,'y'=>0,'z'=>0];
                }
                $orgs = Organisation::where('name','not like','%Юнион%')->get();
                $key=0;
                foreach ($orgs as $org){
                    $coming = DB::select("select sum(amount) as amount, DATE_FORMAT(created_at,'%m') as period
                                  from statements where direction='coming' and org_id=$org->id and created_at like '$year%'
                                  group by DATE_FORMAT(created_at,'%m')");
                    $k=0;
                    foreach($coming as $in){
                        $data[$k]['x']=$in->period;
                        $data[$k]['y']=$in->amount;
                        $k++;
                    }
                    $expense = DB::select("select sum(amount) as amount, DATE_FORMAT(created_at,'%m') as period
                                  from statements where direction='expense' and org_id=$org->id and created_at like '$year%'
                                  group by DATE_FORMAT(created_at,'%m')");
                    $k=0;
                    foreach($expense as $out){
                        $data[$k]['z']=$out->amount;
                        $k++;
                    }
                    $datas[$key]=['org'=>$org->id,'val'=>$data];
                    $key++;
                }
                return json_encode($datas);
            }
            $datas[0]=['org'=>0,'val'=>array()];
            return json_encode($datas);
        }
    }
}
