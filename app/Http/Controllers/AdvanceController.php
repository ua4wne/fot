<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Advance;
use App\Models\Buhcode;
use App\Models\Currency;
use App\Models\Organisation;
use App\Models\Person;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AdvanceController extends Controller
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
        if(view()->exists('advances')){
            $docs = Advance::whereBetween('created_at',[$from, $to])->get();
            $data = [
                'title' => 'Авансовые отчеты',
                'head' => 'Авансовые отчеты',
                'docs' => $docs,
            ];

            return view('advances',$data);
        }
        abort(404);
    }

    public function view(Request $request){
        return 'View';
    }

    public function create(){
        if(!Role::granted('finance')){//вызываем event
            $msg = 'Попытка создания нового авансового отчета!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if(view()->exists('advance_add')){
            $doc_num = LibController::GenNumberDoc('advance');
            $persons = Person::select(['id','fio'])->get();
            $persel = array();
            foreach ($persons as $person){
                $persel[$person->id] = $person->fio;
            }
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

            $data = [
                'title' => 'Новый документ',
                'doc_num' => $doc_num,
                'orgsel' => $orgsel,
                'persel' => $persel,
                'currsel' => $currsel,
                'codesel' => $codesel,
            ];
            return view('advance_add', $data);
        }
        abort(404);
    }

}
