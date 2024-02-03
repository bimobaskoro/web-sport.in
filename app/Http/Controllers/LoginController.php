<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function login(){
        return view('login');
    }

    public function loginPost(Request $request){
        
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $accountType = $user->account_type;
    
            if ($accountType === 'customer') {
                return redirect()->intended('/dashboard')->with('success', 'Login Successfully');
            } elseif ($accountType === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Login Successfully');
            }
        } else {
            return back()->with('loginError', 'Login Failed');
        }
    }
}
