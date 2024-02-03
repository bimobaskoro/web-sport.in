<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class csProfileController extends Controller
{
    public function profile(){
        $user = auth()->user();
        return view('customer.cs_profile', compact('user'));
    }

    public function updateProfile(Request $request, $id){
        $validated = $request->validate([
            'name' => 'required',
            'email'=> 'required|email',
            'phone' => 'required',
        ]);
        $user = auth()->user();
        if ($request->email !== $user->email) {
            $emailExists = User::where('email', $request->email)->exists();
            if ($emailExists) {
                return redirect()->back()->with('error', 'Email sudah digunakan oleh pengguna lain.');
            }
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
    
        $user->save();
        
        return redirect()->route('csProfile')->with('success', 'Profil berhasil diperbarui.');


    }
}
