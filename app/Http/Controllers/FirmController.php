<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Group;
use Illuminate\Http\Request;
use Validator;

class FirmController extends Controller
{
    public function index(){
        if(view()->exists('firms')){
            //выбираем все группы с parent_id=null
            $groups = Group::all();
            $grpsel = array();
            foreach ($groups as $group){
                $grpsel[$group->id] = $group->name;
            }
            $title='Все контрагенты';
            $firms = Firm::all();
            $data = [
                'title' => $title,
                'head' => $title,
                'firms' => $firms,
                'grpsel' => $grpsel
            ];
            //dd($childs);
            return view('firms',$data);
        }
        abort(404);
    }

    public function physical(){
        if(view()->exists('firms')){
            //выбираем все группы с parent_id=null
            $groups = Group::all();
            $grpsel = array();
            foreach ($groups as $group){
                $grpsel[$group->id] = $group->name;
            }
            $title='Физические лица';
            $firms = Firm::where('type','physical')->get();
            $data = [
                'title' => $title,
                'head' => $title,
                'firms' => $firms,
                'grpsel' => $grpsel
            ];
            //dd($childs);
            return view('firms',$data);
        }
        abort(404);
    }

    public function legal(){
        if(view()->exists('firms')){
            //выбираем все группы с parent_id=null
            $groups = Group::all();
            $grpsel = array();
            foreach ($groups as $group){
                $grpsel[$group->id] = $group->name;
            }
            $title='Юридические лица';
            $firms = Firm::where('type','legal_entity')->get();
            $data = [
                'title' => $title,
                'head' => $title,
                'firms' => $firms,
                'grpsel' => $grpsel
            ];
            //dd($childs);
            return view('firms',$data);
        }
        abort(404);
    }

    public function create(Request $request){

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'integer' => 'Значение поля должно быть числовым!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'in' => 'Не допустимое значение поля! (Физлицо или Юрлицо)'
            ];
            $validator = Validator::make($input,[
                'type' => 'in:physical,legal_entity',
                'name' => 'required|max:90',
                'full_name' => 'required|max:180',
                'group_id' => 'nullable|integer',
                'inn' => 'nullable|max:12',
                'kpp' => 'nullable|max:9',
                'acc_id' => 'nullable|integer',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('firmAdd')->withErrors($validator)->withInput();
            }

            $firm = new Firm();
            $firm->fill($input);
            if($firm->save()){
                $msg = 'Контрагент '. $input['name'] .' был успешно добавлен!';
                return redirect('/firms')->with('status',$msg);
            }
        }
        if(view()->exists('firm_add')){
            //выбираем все группы с parent_id=null
            $groups = Group::all();
            $grpsel = array();
            foreach ($groups as $group){
                $grpsel[$group->id] = $group->name;
            }
            $data = [
                'title' => 'Новая запись',
                'grpsel' => $grpsel
            ];
            return view('firm_add', $data);
        }
        abort(404);
    }
}
