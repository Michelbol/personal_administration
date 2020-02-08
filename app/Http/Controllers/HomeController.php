<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $bankAccounts = BankAccount::with('bank:id,title_color,body_color')->get(['id', 'name', 'bank_id']);
        $cars = Car::all(['id', 'license_plate', 'model']);
        $total_cars = Car::count();
        return view('home', compact('bankAccounts', 'total_cars', 'cars', 'year'));
    }
}
