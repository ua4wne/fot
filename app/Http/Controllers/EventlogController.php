<?php

namespace App\Http\Controllers;

use App\Models\Eventlog;
use App\User;
use Illuminate\Http\Request;
use App\Events\AddEventLogs;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class EventlogController extends Controller
{
    public function index(Request $request){
        if(!Role::granted('admin')){//вызываем event
            $msg = 'Попытка просмотра журнала событий!';
            event(new AddEventLogs('access',Auth::id(),$msg));
            abort(503,'У Вас нет прав на создание записи!');
        }
        if($request->isMethod('post')){
            $from = $request['from'];
            $to = $request['to'];
            $events = Eventlog::whereBetween('created_at',[$from, $to])->get();
        }
        else{
            $events = Eventlog::all()->sortByDesc('created_at')->take(50);
        }
        if(view()->exists('events')){
            $count = Eventlog::all()->count();
            $data = [
                'title' => 'События',
                'head' => 'Журнал событий',
                'events' => $events,
                'count' => $count,
            ];

            return view('events',$data);
        }
        abort(404);
    }

    public function edit($id,Request $request){
        if(!User::hasRole('admin')){
            abort(503);
        }

        if($request->isMethod('delete')){
            if($id=='all'){
                Eventlog::whereNotNull('id')->delete(); //удаляем все записи
                $msg = 'Журнал событий был очищен!';
            }
            else{
                $model = Eventlog::find($id);
                $model->delete();
                $msg = 'Удаление события '. $model->text;
            }

        }
        return redirect('/eventlog')->with('status',$msg);
    }
}
