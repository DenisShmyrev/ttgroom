<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Получаем список последних бронирований (например, 5 самых новых)
        $recentBookings = Booking::with('room')
            ->orderByDesc('start_time')
            ->take(5)
            ->get();

        return view('home', compact('recentBookings'));
    }


            public function about()
        {
            return view('about');
        }

        public function contact()
        {
            return view('contact');
        }

        public function policy()
        {
            return view('policy');
        }


}
