<?php

namespace App\Http\Controllers;

use App\Models\CashDoc;
use App\Models\Organisation;
use App\Models\Statement;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        if(view()->exists('main_index')){
            $coming = CashDoc::where('direction','coming')->sum('amount');
            $expense = CashDoc::where('direction','expense')->sum('amount');
            $kassa = $coming - $expense;
            $kassa = round($kassa,2);
            //$period = date('Y-m-d', strtotime(date("Y-m-d") .' -30 day'));
            $period = date('Y'); //текущий год
            $in = Statement::where('direction','coming')->where('created_at','like',$period.'%')->sum('amount');
            $out = Statement::where('direction','expense')->where('created_at','like',$period.'%')->sum('amount');
            //выбираем свои организации
            $content='';
            $orgs = Organisation::where('name','not like','%Юнион%')->get();
            foreach ($orgs as $org){
                $content.= '<div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>'.$org->name.' <small>Банковские выписки за '.date('Y').' год.</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div id="'.$org->id.'" style="width:100%; height:270px;"></div>
                  </div>
                </div>
              </div>';
            }
            return view('main_index',[
                'title'=>'Финплан',
                'kassa' => $kassa,
                'coming' => $in,
                'expense' => $out,
                'content' => $content,
            ]);
        }
        abort(404);
    }
}
