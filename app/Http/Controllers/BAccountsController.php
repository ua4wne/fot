<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Validator;

class BAccountsController extends Controller
{
    public function index(){
        if(view()->exists('refs.bacc')){
            $bacc = BankAccount::paginate(15); //all();
            $data = [
                'title' => 'Банковские счета',
                'head' => 'Банковские счета организаций',
                'bacc' => $bacc,
            ];

            return view('refs.bacc',$data);
        }
        abort(404);
    }
}
