<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class DashboardController extends Controller
{
    public function dashboard(){
        $users = User::all(); 
        $products = Product::all();
        
        return view('customer.cs_dashboard', compact('users', 'products'));
    }

    public function filterSoccer(){
        $users = User::all();
        $products = Product::where('type', 'Soccer')->get();

        return view('customer.cs_dashboard', compact('users', 'products'));
    }

    public function filterBasketball(){
        $users = User::all();
        $products = Product::where('type', 'BasketBall')->get();

        return view('customer.cs_dashboard', compact('users', 'products'));
    }

    public function filterBadminton(){
        $users = User::all();
        $products = Product::where('type', 'Badminton')->get();

        return view('customer.cs_dashboard', compact('users', 'products'));
    }

    public function filterIndoorFootball(){
        $users = User::all();
        $products = Product::where('type', 'Indoor Football')->get();

        return view('customer.cs_dashboard', compact('users', 'products'));
    }

    public function filterVolley(){
        $users = User::all();
        $products = Product::where('type', 'Volley')->get();

        return view('customer.cs_dashboard', compact('users', 'products'));
    }
}
