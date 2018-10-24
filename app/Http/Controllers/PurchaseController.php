<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Buhcode;
use App\Models\Currency;
use App\Models\Nomenclature;
use App\Models\NomenclatureGroup;
use App\Models\Organisation;
use App\Models\Purchase;
use App\Models\PurchaseTable;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('post')){
            $from = $request['from'];
            $to = $request['to'];
            $docs = Purchase::whereBetween('created_at',[$from, $to])->get();
        }
        else{
            $docs = Purchase::all()->sortByDesc('created_at')->take(100);
        }
        if(view()->exists('purchases')){

            $data = [
                'title' => 'Поступления',
                'head' => 'Документы поступлений',
                'docs' => $docs,
            ];

            return view('purchases',$data);
        }
        abort(404);
    }

    public function create(){
        if(!Role::granted('sale_doc_add')){//вызываем event
            $msg = 'Попытка создания нового поступления!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if(view()->exists('purchase_add')){
            $doc_num = LibController::GenNumberDoc('purchase');
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
            return view('purchase_add', $data);
        }
        abort(404);
    }

    public function edit(Request $request, $id){
        if(!Role::granted('sale_doc_edit')){//вызываем event
            $msg = 'Попытка редактирования документа поступления!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if(view()->exists('purchase_edit')){
            $model = Purchase::find($id);
            $positions = PurchaseTable::where(['purchase_id'=>$id])->get();

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
            if(isset($model->contract_id)){
                $contractName = $model->contract->name;
                $contract[$model->contract_id] = $contractName;
            }
            else{
                $contract = array();
                $contractName = null;
            }

            $data = [
                'title' => 'Редактирование документа',
                'model' => $model,
                'positions' => $positions,
                'orgsel' => $orgsel,
                'nomsel' => $nomsel,
                'currsel' => $currsel,
                'codesel' => $codesel,
                'contract' => $contract,
                'contractName' => $contractName,
            ];
            return view('purchase_edit', $data);
        }
        abort(404);
    }
}
