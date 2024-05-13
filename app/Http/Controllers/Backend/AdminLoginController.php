<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use DB;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';
    public function __construct()
    {
        $this->middleware('guest:admin')->except('login','logout','showLoginForm');
    }

    public function showLoginForm()
    {
        // echo \Hash::make('abcd1234');
        return view('backend.adminlogin');
    }
    
    /**
     * Get the path the user should be redirected to when Unauthenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    public function redirectTo()
    {
        return route('admin.index');
        // return \Redirect::to(toUrl('').'/login')->with('flash_error',_('Login Error!'));
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    // /**
    //  * Function description
    //  * @return true
    //  */
    // public function login()
    // {
    //     dd('here');
    //     return true;
    // }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
