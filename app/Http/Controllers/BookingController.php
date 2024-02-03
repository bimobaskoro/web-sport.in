<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function checkOut(Request $request)
    {
        $newBookingId = session('newBookingId');

        // // Pastikan ID booking ada dalam sesi
        // if (!$newBookingId) {
        //     // Redirect ke halaman lain jika ID booking tidak tersedia
        //     return redirect()->route('home');
        // }

        $newBooking = Booking::find($newBookingId);

        // Hapus ID booking dari sesi setelah mengambilnya
        session()->forget('newBookingId');

        return view('customer.checkOut', compact('newBooking'));
    }
    }
