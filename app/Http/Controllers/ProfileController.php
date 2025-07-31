<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function index()
    {
        // Получаем бронирования текущего пользователя
        $bookings = Auth::user()->bookings()->with('room')->get();
        
        // Передаём их в шаблон profile.index
        return view('profile.index', [
            'bookings' => $bookings
        ]);
    }

}
