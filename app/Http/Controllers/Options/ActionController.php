<?php

namespace App\Http\Controllers\Options;

use App\Models\Action;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ActionController extends Controller
{
    public function index(){
        if(view()->exists('options.actions')){
            $title='Список разрешений';
            $actions = Action::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => $title,
                'head' => 'Список разрешений в системе',
                'actions' => $actions,
            ];
            return view('options.actions',$data);
        }
        abort(404);
    }
}
