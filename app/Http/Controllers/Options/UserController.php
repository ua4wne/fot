<?php

namespace App\Http\Controllers\Options;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;

class UserController extends Controller
{
    public function index(){
        if(view()->exists('options.users')){
            $title='Учетные записи';
            //$users = User::all();
            $users = User::paginate(env('PAGINATION_SIZE')); //all();
            $data = [
                'title' => $title,
                'head' => 'Учетные записи пользователей',
                'users' => $users,
            ];
            return view('options.users',$data);
        }
        abort(404);
    }

    public function create(Request $request){

        if($request->isMethod('post')){
            $input = $request->except('_token'); //параметр _token нам не нужен

            $messages = [
                'required' => 'Поле обязательно к заполнению!',
                'string' => 'Значение поля должно быть строковым!',
                'max' => 'Значение поля должно быть не более :max символов!',
                'min' => 'Значение поля должно быть не менее :min символов!',
                'email' => 'Значение поля должно быть корректным адресом e-mail!',
                'unique' => 'Значение поля должно быть уникальным!',

            ];
            $validator = Validator::make($input,[
                'login' => 'required|string|min:3|max:50|unique:users',
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:40|unique:users',
            ],$messages);
            if($validator->fails()){
                return redirect()->route('userAdd')->withErrors($validator)->withInput();
            }

            $user = new User();
            $user->fill($input);
            $user->active = 0;
            $user->auth_code = $this->generateAuthCode(16);
            $pass = $this->makepass(8);
            $user->password = Hash::make($pass);
            if($user->save()){
                //Генерируем ссылку и отправляем письмо на указанный адрес
                $url = url('/').'/activate?id='.$user->id.'&code='.$user->auth_code;
                Mail::send('emails.registration', array('url' => $url,'login'=>$user->login,'pass'=>$pass), function($message) use ($request)
                {
                    $message->to($request->email)->subject('Данные для регистрации');
                });
                $msg = 'Новый пользователь '. $input['name'] .' был успешно добавлен!';
                return redirect('/users')->with('status',$msg);
            }
        }
        if(view()->exists('auth.new_login')){

            $data = [
                'title' => 'Новый логин',
            ];
            return view('auth.new_login', $data);
        }
        abort(404);
    }

    private function generateAuthCode($length = 10){
        $num = range(0, 9);
        $alf = range('a', 'z');
        $_alf = range('A', 'Z');
        $symbols = array_merge($num, $alf, $_alf);
        shuffle($symbols);
        $code_array = array_slice($symbols, 0, (int)$length);
        $code = implode("", $code_array);

        return $code;
    }

    //функция генерации случайного пароля
    protected function makepass($num_chars)
    {
        $pass='';
        if((is_numeric($num_chars))&&($num_chars>0)&&(!is_null($num_chars)))
        {
            $accepted_chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ1234567890';
            srand(((int)((double)microtime()*1000003)));
            for($i=0;$i<=$num_chars;$i++)
            {
                $random_number=rand(0,(strlen($accepted_chars)-1));
                $pass.=$accepted_chars[$random_number];
            }
        }
        return $pass;
    }
}
