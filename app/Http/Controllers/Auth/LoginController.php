<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'login';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка попытки аутентификации.
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $data = $request->all();
        //есть записи в
        if (Auth::attempt(['login' => $data['login'], 'password' => $data['password'], 'active' => 1])) {
            // Аутентификация успешна...
            return redirect()->intended($this->redirectTo);
        }
        else{
            return 'Oooo';
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->intended($this->redirectTo);
    }

}
