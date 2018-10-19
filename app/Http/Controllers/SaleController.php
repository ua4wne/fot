<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Buhcode;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Firm;
use App\Models\Nomenclature;
use App\Models\NomenclatureGroup;
use App\Models\Organisation;
use App\Models\Sale;
use App\Models\SaleTable;
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
            $docs = Sale::whereBetween('created_at',[$from, $to])->get();
        }
        else{
            /*$now = date('Y-m-d');
            $arr = explode('-',$now);
            $year = $arr[0];
            $month = $arr[1];
            $day = $arr[2];
            $from = date('Y-m-d', strtotime("$year-$month-$day -1 month"));
            $to = date('Y-m-d', strtotime("$year-$month-$day +1 day"));*/
            $docs = Sale::all()->sortByDesc('created_at')->take(100);
        }
        if(view()->exists('sales')){

            $data = [
                'title' => 'Реализация',
                'head' => 'Документы реализаций',
                'docs' => $docs,
            ];

            return view('sales',$data);
        }
        abort(404);
    }

    public function create(){
        if(!Role::granted('sale_doc_add')){//вызываем event
            $msg = 'Попытка создания новой реализации!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if(view()->exists('sale_add')){
            $doc_num = LibController::GenNumberDoc('sale');
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $coins = Currency::select(['id','name'])->get();
            $currsel = array();
            foreach ($coins as $coin){
                $currsel[$coin->id] = $coin->name;
            }
            $buxcodes = Buhcode::select(['id','code'])->where(['show'=>1])->get();
            $codesel = array();
            foreach ($buxcodes as $buxcode){
                $codesel[$buxcode->id] = $buxcode->code;
            }
            $groups = NomenclatureGroup::all(['id','name']);
            $nomsel = array();
            foreach ($groups as $group){
                $item = array();
                $nomens = Nomenclature::where(['group_id'=>$group->id])->get();
                foreach ($nomens as $nomen){
                    $item[$nomen->id] = $nomen->name;
                }
                $nomsel[$group->name] = $item;
            }

            $data = [
                'title' => 'Новый документ',
                'doc_num' => $doc_num,
                'orgsel' => $orgsel,
                'codesel' => $codesel,
                'nomsel' => $nomsel,
                'currsel' => $currsel,
            ];
            return view('sale_add', $data);
        }
        abort(404);
    }

    public function edit(Request $request, $id){
        if(!Role::granted('sale_doc_edit')){//вызываем event
            $msg = 'Попытка редактирования документа реализации!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if(view()->exists('sale_edit')){
            $model = Sale::find($id);
            $positions = SaleTable::where(['sale_id'=>$id])->get();

            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $coins = Currency::select(['id','name'])->get();
            $currsel = array();
            foreach ($coins as $coin){
                $currsel[$coin->id] = $coin->name;
            }
            $buxcodes = Buhcode::select(['id','code'])->where(['show'=>1])->get();
            $codesel = array();
            foreach ($buxcodes as $buxcode){
                $codesel[$buxcode->id] = $buxcode->code;
            }
            $groups = NomenclatureGroup::all(['id','name']);
            $nomsel = array();
            foreach ($groups as $group){
                $item = array();
                $nomens = Nomenclature::where(['group_id'=>$group->id])->get();
                foreach ($nomens as $nomen){
                    $item[$nomen->id] = $nomen->name;
                }
                $nomsel[$group->name] = $item;
            }
            $contract[$model->contract_id] = $model->contract->name;

            $data = [
                'title' => 'Редактирование документа',
                'model' => $model,
                'positions' => $positions,
                'orgsel' => $orgsel,
                'nomsel' => $nomsel,
                'currsel' => $currsel,
                'codesel' => $codesel,
                'contract' => $contract,
            ];
            return view('sale_edit', $data);
        }
        abort(404);
    }
}
