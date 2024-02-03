<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
class AdminDashboard extends Controller
{
    public function ad_dashboard(){
        $users = User::all();
        $products = Product::all();
        return view('admin.ad_dashboard', compact('products'));
    }

    public function ad_dashboard_Add(Request $request)
    {
        // Make sure the user is authenticated before proceeding
        if (auth()->check()) {
            $validated = $request->validate([
                'nameField' => 'required|string',
                'location' => 'required|string',
                'type' => 'required|string',
                'desc' => 'nullable|string|max:300',
                'totalField' => 'nullable',
                'imgUrl' => 'image|mimes:jpeg,png,jpg|max:2048',
                'openClock' => 'required|numeric|min:0|max:23',
                'closeClock' => 'required|numeric|min:0|max:23',
                'price' => 'required',
            ]);
    
            $openClock = sprintf("%02d:00:00", $validated['openClock']);
            $closeClock = sprintf("%02d:00:00", $validated['closeClock']);
    
            if ($request->hasFile('imgUrl')) {
                $image = $request->file('imgUrl');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images-post', $imageName);
            } else {
                $imageName = null;
            }
    
            // Get the currently authenticated user
            $user = auth()->user();
            Log::info("User ID: " . $user->id);
            // Ensure the user is not null before accessing the ID
            if ($user) {
                // Get the user ID
                $userId = $user->id;
    
                Product::create([
                    'user_id' => $userId,
                    'nameField' => $validated['nameField'],
                    'location' => $validated['location'],
                    'type' => $validated['type'],
                    'desc' => $validated['desc'],
                    'price' => $validated['price'],
                    'totalField' => $validated['totalField'] ?? null,
                    'openClock' => $openClock,
                    'closeClock' => $closeClock,
                    'imgUrl' => $imageName,
                ]);
    
                return redirect()->route('ad_dashboard')->with('success', 'Produk berhasil ditambahkan.');
            } else {
                return redirect()->route('login')->with('error', 'Anda harus login untuk menambah produk.');
            }
        }


    }

    public function ad_dashboard_Update(Request $request, $id)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'nameField' => 'required|string',
            'location' => 'required|string',
            'type' => 'required|string',
            'desc' => 'nullable|string|max:300',
            'totalField' => 'nullable',
            'imgUrl' => 'image|mimes:jpeg,png,jpg|max:2048',
            'openClock' => 'required|numeric|min:0|max:23',
            'closeClock' => 'required|numeric|min:0|max:23',
            'price' => 'required',
        ]);

        // Konversi jam buka dan tutup ke format yang diinginkan
        $openClock = sprintf("%02d:00:00", $validated['openClock']);
        $closeClock = sprintf("%02d:00:00", $validated['closeClock']);

        // Temukan produk berdasarkan ID
        $product = Product::find($id);

        // Setel nilai atribut produk berdasarkan input yang divalidasi
        $product->nameField = $validated['nameField'];
        $product->location = $validated['location'];
        $product->type = $validated['type'];
        $product->desc = $validated['desc'];
        $product->price = $validated['price'];
        $product->totalField = $validated['totalField'] ?? null;
        $product->openClock = $openClock;
        $product->closeClock = $closeClock;

        // Jika ada file gambar yang diunggah, simpan dan setel URL gambar
        if ($request->hasFile('imgUrl')) {
            $image = $request->file('imgUrl');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images-post', $imageName);
            $product->imgUrl = $imageName;
        }

        // Simpan perubahan pada produk
        $product->save();

        return redirect()->route('ad_dashboard')->with('success', 'Produk berhasil diperbarui.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Tangani kesalahan validasi dan berikan pesan kesalahan yang lebih informatif
        $errors = $e->errors();
        $errorMessage = 'Validation error: ' . implode(', ', Arr::flatten($errors));
        Log::error($errorMessage);

        return redirect()->route('ad_dashboard')->with('error', $errorMessage);
    } catch (\Exception $e) {
        // Tangani kesalahan lain dan tambahkan log kesalahan
        Log::error('Error updating product: ' . $e->getMessage());
        return redirect()->route('ad_dashboard')->with('error', 'Gagal memperbarui produk.');
    }
}

public function ad_dashboard_Delete($id)
{
    try {
        // Temukan produk berdasarkan ID
        $product = Product::find($id);

        // Hapus gambar dari penyimpanan jika ada
        if ($product->imgUrl) {
            Storage::delete('public/images-post/' . $product->imgUrl);
        }

        // Hapus produk
        $product->delete();

        return redirect()->route('ad_dashboard')->with('success', 'Produk berhasil dihapus.');
    } catch (\Exception $e) {
        // Tangani kesalahan dan log
        Log::error('Error deleting product: ' . $e->getMessage());
        return redirect()->route('ad_dashboard')->with('error', 'Gagal menghapus produk.');
    }
}

}
