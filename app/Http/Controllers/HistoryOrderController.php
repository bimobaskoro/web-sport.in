<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HistoryOrderController extends Controller
{
    public function index(Request $request)
    {
        // Mengecek apakah tombol "Apply Filters" ditekan
        if ($request->has('apply_filters')) {
            // Jika ya, simpan filter ke dalam sesi
            $request->session()->put('filter.status', $request->input('status'));
            $request->session()->put('filter.price_order', $request->input('price_order', 'asc'));
            $request->session()->put('filter.sort_order', $request->input('sort_order', 'desc'));
        } else {
            // Jika tidak, reset filter pada sesi
            $request->session()->forget('filter');
        }
    
        // Ambil data sesuai filter
        $userId = Auth::id();
        $bookings = $this->applyFilters($request, Auth::id());
    
        // Urutan berdasarkan harga
        $priceOrder = $request->session()->get('filter.price_order', 'desc');
        $bookings->orderBy('price', $priceOrder);
    
        // Urutan berdasarkan waktu
        $timeOrder = $request->session()->get('filter.sort_order', 'desc');
        $bookings->orderBy('created_at', $timeOrder);
    
        // Ambil hasil query
        $filteredBookings = $bookings->get();
    
        return view('customer.historyOrder', compact('filteredBookings'));
    }
    
    
    public function filter(Request $request)
    {
        $bookings = $this->applyFilters($request);
    
        // Ambil hasil query
        $filteredBookings = $bookings->get();
    
        if ($request->has('apply_filters')) {
            $request->session()->put('filter.status', $request->input('status'));
            $request->session()->put('filter.price_order', $request->input('price_order', 'asc'));
            $request->session()->put('filter.sort_order', $request->input('sort_order', 'desc'));
        } elseif (!$request->session()->has('filter')) {
            // Jika halaman diperbarui (refresh) dan tidak ada session filter, reset filter
            $request->session()->forget('filter');
        }
    
        return view('customer.historyOrder', compact('filteredBookings'));
    }
    

    private function applyFilters(Request $request)
    {
        $bookings = Booking::query();

        $this->applyStatusFilter($bookings, $request);
    
        // Urutan berdasarkan harga
        $priceOrder = $request->input('price_order', 'asc');
        
        // Pastikan Anda menggunakan kolom 'price' dan bukan 'created_at'
        $bookings->orderBy('price', $priceOrder);

        // Urutan berdasarkan waktu
        $timeOrder = $request->input('time_order', 'desc');
        $bookings->orderBy('created_at', $timeOrder);
        
        Log::info('Filtered prices:', $bookings->pluck('price')->toArray());
        Log::info('Filtered created_at:', $bookings->pluck('created_at')->toArray());
        return $bookings;
    }

    private function applyStatusFilter($query, Request $request)
    {
        $status = $request->input('status');
        
        if ($status && in_array($status, ['paid', 'pending', 'cancel'])) {
            $query->where('status', $status);
        }
    }

}
