<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        if(view()->exists('main_index')){
            return view('main_index',['title'=>'Финплан']);
        }
        abort(404);
    }
}
