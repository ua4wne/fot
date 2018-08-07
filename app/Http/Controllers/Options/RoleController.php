<?php

namespace App\Http\Controllers\Options;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class RoleController extends Controller
{
    public function index(){
        if(view()->exists('options.roles')){
            $title='Список ролей';
            $roles = Role::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => $title,
                'head' => 'Список системных ролей',
                'roles' => $roles,
            ];
            return view('options.roles',$data);
        }
        abort(404);
    }
}
