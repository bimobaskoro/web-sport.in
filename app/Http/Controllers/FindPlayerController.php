<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Posting; // Sesuaikan dengan namespace dan model yang sesuai
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class FindPlayerController extends Controller
{
    public function FindPlayer(Request $request){
        $filter = $request->input('filter', 'all'); // Mendapatkan nilai filter dari permintaan, default 'all'
    
        $posts = Posting::query();
    
        // Menambahkan filter berdasarkan "News" atau "Olds"
        if ($filter == 'news') {
            $posts->orderBy('created_at', 'desc'); // Urutkan postingan berdasarkan waktu terbaru
        } elseif ($filter == 'olds') {
            $posts->orderBy('created_at', 'asc'); // Urutkan postingan berdasarkan waktu terlama
        }
    
        // Menambahkan filter "Your Post" jika pengguna sudah login
        if (Auth::check() && $filter == 'yourpost') {
            $posts->where('user_id', Auth::id()); // Hanya menampilkan postingan pengguna yang sedang login
        }
    
        $filteredPosts = $posts->get();
    
        return view('customer.findPlayer', compact('filteredPosts', 'filter'));
    }
    
    public function redirectToWhatsApp($postId)
    {
        // Dapatkan informasi posting berdasarkan ID posting
        $post = Posting::find($postId);
    
        if (!$post) {
            Log::error("Post with ID $postId not found in the database");
            return abort(404); // Tampilkan halaman 404 jika posting tidak ditemukan
        }
    
        // Dapatkan informasi pengguna yang memposting
        $user = User::find($post->user_id);
    
        if (!$user) {
            Log::error("User with ID $post->user_id not found in the database");
            return abort(404); // Tampilkan halaman 404 jika pengguna tidak ditemukan
        }
    
        // Dapatkan nomor WhatsApp dari pengguna yang memposting
        $whatsappNumber = $user->phone;
    
        if (!$whatsappNumber) {
            Log::error("WhatsApp number not found for user with ID $user->id");
            return abort(404); // Tampilkan halaman 404 jika nomor WhatsApp tidak ditemukan
        }
    
        // Buat URL untuk direktori ke WhatsApp
        $whatsappUrl = 'https://wa.me/' . $whatsappNumber;
    
        return Redirect::away($whatsappUrl);
    }
}
