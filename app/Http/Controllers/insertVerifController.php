<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class InsertVerifController extends Controller
{
    public function insertCode()
    {
        return view('customer.verifNo');
    }

    public function postCode(Request $request)
    {
        $user = Auth::user();
    
        if ($user instanceof User) {
            $storedCode = $user->verification_code;
            $enteredCode = implode('', $request->input('verification_code'));
    
            // Log storedCode and enteredCode
            \Illuminate\Support\Facades\Log::error("Stored Code: " . json_encode($storedCode) . ", Entered Code: " . json_encode($enteredCode));
            \Illuminate\Support\Facades\Log::info("Before Update - is_verified: " . $user->is_verified);
            if ($enteredCode == $storedCode) {
                // Update 'is_verified' to true
                $user->update(['is_verified' => true]);
                \Illuminate\Support\Facades\Log::info("After Update - is_verified: " . $user->is_verified);
                // Refresh user data after update
                $user->save();
    
                // Log user data after update
                \Illuminate\Support\Facades\Log::error("User Data After Update: " . json_encode($user->toArray()));
    
                return Redirect::route('dashboard')->with('success', 'Verifikasi berhasil! Akun Anda sekarang telah diverifikasi.');
            } else {
                \Illuminate\Support\Facades\Log::error("Invalid verification code entered by user");
                return Redirect::route('code')->with('error', 'Kode verifikasi tidak cocok.');
            }
        }
    
        // Log an error if user not found
        \Illuminate\Support\Facades\Log::error("User not found in the database");
    
        return Redirect::route('code')->with('error', 'Pengguna tidak ditemukan dalam basis data');
    }
}
