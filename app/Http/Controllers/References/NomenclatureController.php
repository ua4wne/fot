<?php

namespace App\Http\Controllers\References;

use App\Models\Nomenclature;
use App\Models\NomenclatureGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class NomenclatureController extends Controller
{
    public function index(){
        if(view()->exists('refs.nomenclatures')){
            $parents = NomenclatureGroup::all();
            $childs = Nomenclature::all();
            $data = [
                'title' => 'Номенклатура',
                'head' => 'Список номенклатуры',
                'groups' => $parents,
                'childs' => $childs
            ];
            //dd($childs);
            return view('refs.nomenclatures',$data);
        }
        abort(404);
    }
}
