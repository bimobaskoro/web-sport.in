<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\WhatsAppNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Vonage\Client\Credentials\Basic as VonageBasicCredentials;
use Vonage\Client as VonageClient;
use Vonage\SMS\Message\SMS as VonageSMS;

class VerificationController extends Controller
{
    const BRAND_NAME = 'NamaMerek'; // Ganti dengan nama merek atau identifikasi aplikasi Anda

    public function sendVerificationCode(Request $request)
    {
        // Assuming the phone number is already stored in the database
        $user = Auth::user();    
        if ($user instanceof User) {
            $phoneNumber = $user->phone;
            $verificationCode = $this->generateVerificationCode();
    
            // Save the verification code to the user's record
            $user->verification_code = $verificationCode;
            $user->save();
    
            // Continue with the existing code for sending WhatsApp notification
            $vonageCredentials = new VonageBasicCredentials("899f7ec2", "1jPXwFgzPSv36yIB");
            $vonageClient = new VonageClient($vonageCredentials);
    
            $response = $vonageClient->sms()->send(
                new VonageSMS($phoneNumber, self::BRAND_NAME, "Kode verifikasi: {$verificationCode}")
            );
    
            $message = $response->current();
    
            if ($message->getStatus() == 0) {
                return Redirect::route('code')->with('success', 'Kode verifikasi telah dikirim!');
            } else {
                return Redirect::route('code')->with('error', 'Gagal mengirim kode verifikasi');
            }
        } else {
            // Handle the case where no user is found in the database
            return Redirect::route('code')->with('error', 'User tidak ditemukan dalam database');
        }
    }

    public function verifyCode(Request $request)
{
    $user = Auth::user();
    $enteredCode = $request->input('verification_code');

    if ($user instanceof User && $enteredCode == $user->verification_code) {
        // Verification code is correct
        $user->is_verified = true; // Assuming 'is_verified' is a boolean field in the users table
        $user->save();

        return Redirect::route('home')->with('success', 'Verifikasi berhasil! Akun Anda sekarang telah diverifikasi.');
    } else {
        // Verification code is incorrect
        return Redirect::route('code')->with('error', 'Kode verifikasi salah. Silakan coba lagi.');
    }
}

    private function generateVerificationCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffledString = str_shuffle($characters);
        $code = substr($shuffledString, 0, 5);

        return $code;
    }
}
