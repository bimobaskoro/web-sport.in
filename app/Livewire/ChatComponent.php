<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Product;
use App\Models\Review;

class ChatComponent extends Component
{
    public $productId;
    public $message;
    public $chats;

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $productId = $product->id;
    
        return view('livewire.chat-component', compact('product', 'productId'));
    }
    

    public function loadChats()
    {
        $this->chats = Chat::where('product_id', $this->productId)->get();
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string',
        ]);

        Chat::create([
            'user_id' => Auth::id(),
            'product_id' => $this->productId,
            'message' => $this->message,
        ]);

        // Bersihkan input pesan setelah dikirim
        $this->message = '';

        // Perbarui daftar obrolan
        $this->loadChats();
    }

    public function render()
    {
        return view('livewire.chat-component');
    }
}
