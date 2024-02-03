<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class ChatController extends Controller
{
    public function chat(){
        return view('customer.chat');
    }
    public function sendMessage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;
        $message = $request->message;

        // Simpan pesan ke database
        Chat::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'message' => $message,
        ]);

        return response()->json(['message' => 'Pesan berhasil dikirim']);
    }

    public function getMessages($productId)
{
    $messages = Chat::where('product_id', $productId)->get();
    return response()->json(['messages' => $messages]);
}

}
