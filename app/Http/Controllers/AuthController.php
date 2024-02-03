<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(){
        return view('register');
    }

    public function registerPost(Request $request)
    {   
        $validated = $request->validate([
            'name' => 'required',
            'email'=> 'required|unique:users',
            'password' => 'required|min:3',
            'phone' =>'required',
            'account_type' => 'required|in:customer,admin'
        ]);

        $validated['phone'] = (strpos($validated['phone'], '62') === 0) ? $validated['phone'] : '62' . substr($validated['phone'], 1);

        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->password = Hash::make($validated['password']);
        $user->account_type = $validated['account_type'];
        $user->save();

        return redirect('/login')->with('success', 'Register Successfully');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login'); // Ganti dengan URL yang sesuai setelah logout
    }
    
}
