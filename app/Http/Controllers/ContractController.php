<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Lib\LibController;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Firm;
use App\Models\Organisation;
use App\Models\Settlement;
use App\Models\Typedoc;
use Illuminate\Http\Request;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index(){
        $model = Organisation::all()->first();
        $org_id = $model->id;
        $name = $model->name;
        if(view()->exists('contracts')){
            $title='Договоры';
            $contracts = Contract::where(['org_id'=>$org_id])->get()->sortBy('created_at');
            $data = [
                'title' => $title,
                'head' => 'Договоры с контрагентами',
                'contracts' => $contracts,
                'name' => $name,
            ];
            return view('contracts',$data);
        }
        abort(404);
    }

    public function create(Request $request){
        if(!Role::granted('sale_doc_add')){//вызываем event
            $msg = 'Попытка создания нового договора с контрагентом!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            if(isset($input['firm_id']))
                $input['firm_id'] = Firm::where('name', $input['firm_id'])->first()->id;
            $input['start'] = date('Y-m-d');
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'string' => 'Значение поля должно быть строковым!',
                'integer' => 'Значение поля должно быть числовым!',
                'date' => 'Неверный формат даты!',
            ];
            $validator = Validator::make($input,[
                'num_doc' => 'required|string|max:15',
                'name' => 'required|string|max:100',
                'tdoc_id' => 'required|integer',
                'org_id' => 'required|integer',
                'firm_id' => 'required|integer',
                'text' => 'nullable|string|max:255',
                'start' => 'required|date',
                'stop' => 'nullable|date',
                'currency_id' => 'required|integer',
                'settlement_id' => 'nullable|integer',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('contractAdd')->withErrors($validator)->withInput();
            }
            $doc = new Contract();
            $doc->fill($input);
            if($doc->save()){
                $msg = 'Новый договор '. $input['num_doc'] .' добавлен в справочник!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect()->route('contractView',['id'=>$doc->org_id])->with('status',$msg);
            }
        }
        if(view()->exists('contract_add')){
            $doc_num = LibController::GenNumberDoc('contracts');
            $types = Typedoc::select(['id','name'])->get();
            $tdocsel = array();
            foreach ($types as $type){
                $tdocsel[$type->id] = $type->name;
            }
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $currs = Currency::select(['id','name'])->get();
            $currsel = array();
            foreach ($currs as $currency){
                $currsel[$currency->id] = $currency->name;
            }
            $settls = Settlement::select(['id','name'])->get();
            $settlsel = array();
            foreach ($settls as $settl){
                $settlsel[$settl->id] = $settl->name;
            }
            $data = [
                'title' => 'Новый договор',
                'doc_num' => $doc_num,
                'orgsel' => $orgsel,
                'tdocsel' => $tdocsel,
                'currsel' => $currsel,
                'settlsel' => $settlsel,
            ];
            return view('contract_add', $data);
        }
        abort(404);
    }

    public function view(Request $request,$id=null){
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            $pattern = $input['org_id'];
            $messages = [
                'required' => 'Значение фильтра не указано!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'string' => 'Значение поля должно быть строковым!',
            ];
            $validator = Validator::make($input,[
                'org_id' => 'required|string|max:90',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('contracts')->withErrors($validator)->withInput();
            }
            if(!empty($pattern)){
                $model = Organisation::where('name', 'LIKE', "%$pattern%")->first();
                $org_id = $model->id;
                $name = $model->name;
                if(view()->exists('contracts')){
                    $title='Договоры';
                    $contracts = Contract::where(['org_id'=>$org_id])->get()->sortBy('created_at');
                    $data = [
                        'title' => $title,
                        'head' => 'Договоры с контрагентами',
                        'contracts' => $contracts,
                        'name' => $name,
                    ];
                    return view('contracts',$data);
                }
                abort(404);
            }
        }
        if($request->isMethod('get')){
            if(!empty($id)){
                $model = Organisation::find($id);
                $name = $model->name;
                if(view()->exists('contracts')){
                    $title='Договоры';
                    $contracts = Contract::where(['org_id'=>$id])->get()->sortBy('created_at');
                    $data = [
                        'title' => $title,
                        'head' => 'Договоры с контрагентами',
                        'contracts' => $contracts,
                        'name' => $name,
                    ];
                    return view('contracts',$data);
                }
                abort(404);
            }
        }
        return redirect('/contracts');
    }

    public function edit($id,Request $request){
        $model = Contract::find($id);
        $firm = $model->firm->name;
        if($request->isMethod('delete')){
            if(!Role::granted('sale_doc_del')){
                $msg = 'Попытка удаления договора '.$model->name;
                event(new AddEventLogs('access',Auth::id(),$msg));
                abort(503,'У Вас нет прав на удаление записи!');
            }
            $org_id = $model->org_id;
            $model->delete();
            $msg = 'Договор '. $model->name .' был удален!';
            //вызываем event
            event(new AddEventLogs('info',Auth::id(),$msg));
            return redirect()->route('contractView',['id'=>$org_id])->with('status',$msg);
        }
        if(!Role::granted('sale_doc_edit')){
            $msg = 'Попытка редактирования договора '. $model->name;
            //вызываем event
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на редактирование записи!');
        }
        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен
            if(isset($input['firm_id']))
                $input['firm_id'] = Firm::where('name', $input['firm_id'])->first()->id;
            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'string' => 'Значение поля должно быть строковым!',
                'integer' => 'Значение поля должно быть числовым!',
                'date' => 'Неверный формат даты!',
            ];
            $validator = Validator::make($input,[
                'num_doc' => 'required|string|max:15',
                'name' => 'required|string|max:100',
                'tdoc_id' => 'required|integer',
                'org_id' => 'required|integer',
                'firm_id' => 'required|integer',
                'text' => 'nullable|string|max:255',
                'stop' => 'nullable|date',
                'currency_id' => 'required|integer',
                'settlement_id' => 'nullable|integer',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('contractAdd')->withErrors($validator)->withInput();
            }

            $model->fill($input);
            $model->updated_at = date('Y-m-d H:i:s');
            if($model->update()){
                $msg = 'Данные договора '. $model->name .' обновлены!';
                //вызываем event
                event(new AddEventLogs('info',Auth::id(),$msg));
                return redirect()->route('contractView',['id'=>$model->org_id])->with('status',$msg);
            }
        }
        $old = $model->toArray(); //сохраняем в массиве предыдущие значения полей модели Contract
        $old['firm_id'] = $firm;
        if(view()->exists('contract_edit')){
            $types = Typedoc::select(['id','name'])->get();
            $tdocsel = array();
            foreach ($types as $type){
                $tdocsel[$type->id] = $type->name;
            }
            $orgs = Organisation::select(['id','name'])->get();
            $orgsel = array();
            foreach ($orgs as $org){
                $orgsel[$org->id] = $org->name;
            }
            $currs = Currency::select(['id','name'])->get();
            $currsel = array();
            foreach ($currs as $currency){
                $currsel[$currency->id] = $currency->name;
            }
            $settls = Settlement::select(['id','name'])->get();
            $settlsel = array();
            foreach ($settls as $settl){
                $settlsel[$settl->id] = $settl->name;
            }
            if(empty($old['settlement_id'])){
                array_unshift($settlsel,'Не выбрано');
            }
            $data = [
                'title' => 'Редактирование договора '.$old['name'],
                'data' => $old,
                'orgsel' => $orgsel,
                'tdocsel' => $tdocsel,
                'currsel' => $currsel,
                'settlsel' => $settlsel,
            ];
            return view('contract_edit',$data);
        }
        abort(404);
    }
}
