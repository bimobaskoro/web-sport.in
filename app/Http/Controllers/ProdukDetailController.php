<?php

namespace App\Http\Controllers;
use Xendit\Invoice;
use Xendit\Configuration;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class ProdukDetailController extends Controller
{
    public function showPD($id){
        $product = Product::find($id);
        $reviews = Review::where('product_id', $id)->get();
        $username = auth()->user()->name; // Misalnya, Anda mendapatkannya dari autentikasi

        $openingHour = date('H', strtotime($product->openClock));
        $closingHour = date('H', strtotime($product->closeClock));

        
    // Cek apakah ada ulasan produk
    if ($reviews->isEmpty()) {
        // Jika tidak ada ulasan, arahkan ke productDetail
        return view('customer.pd_dasboard', compact('product', 'reviews','username','openingHour', 'closingHour'));
    } else {
        // Jika ada ulasan, arahkan ke showReviews
        return redirect()->route('showReviews', ['id' => $id,'openingHour' => $openingHour, 'closingHour' => $closingHour]);
    }

    }

    public function review(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'comment' => 'required|max:300',
            'rating' => 'required',
        ]);
    
        try {
            Review::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->user()->id, // Anda dapat menyimpan user_id jika pengguna telah masuk
                'comment' => $request->comment,
                'rating' => $request->rating,
            ]);

            return redirect("/dashboard/product/{$request->product_id}/reviews")->with('success', 'Review added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the review.');
        }
    }

    public function showReviews($productId)
    {
        $product = Product::find($productId);
        $reviews = Review::where('product_id', $productId)->get();

        $openingHour = date('H', strtotime($product->openClock));
        $closingHour = date('H', strtotime($product->closeClock));

        return view ('customer.pd_dasboard', compact('product', 'reviews','openingHour','closingHour'));
    }

    
    
    public function booking(Request $request){
        $request->validate([
            'product_id' => 'required|exists:product,id', 
            'name' => 'required|string|max:255',
            'field_name' => 'required',
            'booking_date' => 'required|date_format:Y-m-d',
            'start_booking_hour' => 'required|numeric|min:0|max:23',
            'finish_booking_hour' => 'required|numeric|min:0|max:23',
            'total_price' => 'required',
        ]);
    
        $productId = $request->product_id;
        $selectedField = $request->selectedField;
        $bookingDate = $request->booking_date;
        $startHour = $request->start_booking_hour;
        $endHour = $request->finish_booking_hour;
    
        $isFieldAvailable = false;

        try {
            // Memeriksa ketersediaan lapangan menggunakan metode isFieldAvailable
            $this->isFieldAvailable($productId, $selectedField, $bookingDate, $startHour, $endHour);
            $isFieldAvailable = true;
            // Jika lapangan tersedia, lanjutkan dengan pembuatan booking dan redirect ke halaman checkout
            $newBooking = new Booking();
                $newBooking->product_id = $productId;
                $newBooking->user_id = auth()->user()->id;
                $newBooking->name = $request->name;
                $newBooking->field_name = $request->field_name;
                $newBooking->type = $request->type;
                $newBooking->location = $request->location;
                $newBooking->booking_date = $request->booking_date;
                $newBooking->selectedField= $selectedField;
                $newBooking->start_booking_hour = $request->start_booking_hour;
                $newBooking->finish_booking_hour = $request->finish_booking_hour;
                $newBooking->price = $request->price;
                $newBooking->total_price= $request->total_price;
                $newBooking->status = 'pending';
                $newBooking->save();
                
                Config::$serverKey = 'SB-Mid-server-TywME8uspZ3Ol6LDbbtRG8EL';
                Config::$clientKey = 'SB-Mid-client-i3XqXL8U1U9fxNw1';
                Config::$isProduction = false; // Set true untuk mode produksi
        
                // Mendapatkan Snap Token (gunakan data booking yang telah disimpan)
                $transaction_details = [
                    'order_id' => $newBooking->id, // Menggunakan ID booking sebagai order_id
                    'gross_amount' => $newBooking->total_price, // Total harga belanja dari data booking
                ];
        
    
                // Mendapatkan Snap Token dengan menggunakan data booking
                $snapToken = Snap::getSnapToken([
                    'transaction_details' => $transaction_details,
                    // ... detail produk dan pelanggan ...
                ]);
    
            // Redirect ke halaman checkout
        return view('customer.checkOut', compact('snapToken', 'newBooking','isFieldAvailable'));

        } catch (\Exception $e) {
            // Tangkap exception dan tampilkan pesan kesalahan pada halaman sebelumnya
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }

    }
    
public function isFieldAvailable($productId, $selectedField, $bookingDate, $startHour, $endHour)
{
    Log::debug('isFieldAvailable Called: ' . now());
    Log::debug('Product ID: ' . $productId);
    Log::debug('Selected Field: ' . $selectedField);
    Log::debug('Booking Date: ' . $bookingDate);
    Log::debug('Start Hour: ' . $startHour);
    Log::debug('End Hour: ' . $endHour);

    // Cek jika lapangan sedang digunakan oleh user lain pada tanggal dan jam yang sama
    $existingBooking = Booking::where('product_id', $productId)
        ->where('selectedField', $selectedField)
        ->where('booking_date', $bookingDate)
        ->where(function ($query) use ($startHour, $endHour) {
            $query->where(function ($query) use ($startHour, $endHour) {
                $query->where('start_booking_hour', '>=', $startHour)
                    ->where('start_booking_hour', '<', $endHour);
            })->orWhere(function ($query) use ($startHour, $endHour) {
                $query->where('finish_booking_hour', '>', $startHour)
                    ->where('finish_booking_hour', '<=', $endHour);
            })->orWhere(function ($query) use ($startHour, $endHour) {
                $query->where('start_booking_hour', '<=', $startHour)
                    ->where('finish_booking_hour', '>=', $endHour);
            });
        })
        ->first();

    // Log status dan informasi pemesanan yang ada
    if ($existingBooking !== null && $existingBooking->status === 'paid') {
        Log::debug('Existing Booking Found:');
        Log::debug('Booking ID: ' . $existingBooking->id);
        Log::debug('Status: ' . $existingBooking->status);

        // Lapangan sudah dipesan dan dibayar
               // Simpan pesan kesalahan ke sesi
            //    session()->flash('error', 'Maaf, lapangan tidak tersedia pada waktu tersebut.');

               // Redirect kembali tanpa menggunakan with()
               throw new \Exception('Maaf, lapangan tidak tersedia pada waktu tersebut karena sudah dipesan.');
            } else {
               // Log jika tidak ada pemesanan yang ditemukan atau pemesanan ditemukan dengan status tidak 'paid'
               Log::debug('No Existing Booking Found or Status is not paid.');
           }
       
    return true;
}
    
  

public function notificationHandler(Request $request)
{
    try {
                                                               // Log request details
        Log::info('Received Midtrans notification:', ['request' => $request->all()]);

        // require_once(base_path('path/to/Midtrans.php'));
        Config::$isProduction = false; // Set true untuk mode produksi
        Config::$serverKey = 'SB-Mid-server-6Lr54It43EMttPYS-TjoRBAL';
        $notif = new Notification();

        // Verifikasi signature untuk memastikan notifikasi berasal dari Midtrans
        $signatureKey = $notif->signature_key;
        $isValidSignature = $notif->signature_key == $signatureKey;
        if ($isValidSignature) {
            Log::info('Notification signature is valid.');

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            $booking = Booking::find($order_id);

            if ($transaction == 'settlement' && $fraud == 'accept') {
                // Set payment status in merchant's database to 'Paid'
                $booking->status = 'paid';
                $booking->save();
                Log::info("Transaction order_id: $order_id successfully settled and accepted.");
            }
        } else {
            Log::error('Invalid signature. This notification might not be from Midtrans.');
            echo "Invalid signature. This notification might not be from Midtrans.";
        }
    } catch (\Exception $e) {
        Log::error('Error processing Midtrans notification:', ['error' => $e->getMessage()]);
        echo "An error occurred while processing the notification.";
    }

}


public function showCheckoutForm($booking_id) {
    // Fetch the booking details based on $booking_id
    $booking = Booking::findOrFail($booking_id);
    $product = Product::findOrFail($booking->product_id);
    Config::$serverKey = 'SB-Mid-server-6Lr54It43EMttPYS-TjoRBAL';
    Config::$clientKey = 'SB-Mid-client-XnTH_qZ1Jk5CvY6K';
    Config::$isProduction = false; // Set true untuk mode produksi

    // Mendapatkan Snap Token (gunakan data booking yang telah disimpan)
    $transaction_details = [
        'order_id' => $booking->id, // Menggunakan ID booking sebagai order_id
        'gross_amount' => $booking->total_price, // Total harga belanja dari data booking
    ];

    // Anda mungkin perlu menyesuaikan detail produk dan pelanggan sesuai dengan data booking

    // Mendapatkan Snap Token dengan menggunakan data booking
    $snapToken = Snap::getSnapToken([
        'transaction_details' => $transaction_details,
        // ... detail produk dan pelanggan ...
    ]);
    return view('customer.checkOut', compact('snapToken','booking', 'product'));
}

public function redirectToWhatsAppProduct($productId)
{
    // Dapatkan informasi produk berdasarkan ID produk
    $product = Product::find($productId);

    Log::info("Product: " . print_r($product, true));

    if (!$product) {
        \Illuminate\Support\Facades\Log::error("Product with ID $productId not found in the database");
        return abort(404, "Product not found"); 
    }

    // Dapatkan informasi pengguna yang mengupload produk
    $user = User::find($product->user_id);

    Log::info("User: " . print_r($user, true));

    if (!$user) {
        \Illuminate\Support\Facades\Log::error("User with ID $product->user_id not found in the database");
        return abort(404, "User not found");
    }

    // Dapatkan nomor WhatsApp dari pengguna yang mengupload produk
    $whatsappNumber = $user->phone;

    Log::info("WhatsApp Number: " . $whatsappNumber);

    if (!$whatsappNumber) {
        \Illuminate\Support\Facades\Log::error("WhatsApp number not found or empty for user with ID $user->id");
        return abort(404, "WhatsApp number not found or empty");
    }
    
    // Buat URL untuk direktori ke WhatsApp
    $whatsappUrl = 'https://wa.me/' . $whatsappNumber;

    return redirect()->away($whatsappUrl);
}



}
