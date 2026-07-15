<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use function Safe\base64_decode;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm(Request $request)
{
    if($request->has('' . base64_encode('poste_code') .''))
    {
        $data["poste_code"] = $request->query('' . base64_encode('poste_code') .'');
        $data["poste_code"] = $request->query('' . base64_encode('poste_code') .'');
        $data["poste_code"] = $request->query('' . base64_encode('poste_code') .'');
        return view('auth.login_qrcode', $data);
    }
    else
    {
        return view('auth.login_normal');
    }
}
}
