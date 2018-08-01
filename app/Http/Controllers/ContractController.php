<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Validator;

class ContractController extends Controller
{
    public function index(){
        if(view()->exists('contracts')){
            $title='Договоры';
            $contracts = Contract::all();
            $data = [
                'title' => $title,
                'head' => 'Договоры с контрагентами',
                'contracts' => $contracts,
            ];
            //dd($childs);
            return view('contracts',$data);
        }
        abort(404);
    }
}
