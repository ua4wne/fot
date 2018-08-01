<?php

namespace App\Http\Controllers\References;

use App\Models\Typedoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class TypeDocController extends Controller
{
    public function index(){
        if(view()->exists('refs.type_docs')){
            $tdocs = Typedoc::paginate(15); //all();
            $data = [
                'title' => 'Виды договоров',
                'head' => 'Виды договоров',
                'tdocs' => $tdocs,
            ];

            return view('refs.type_docs',$data);
        }
        abort(404);
    }
}
