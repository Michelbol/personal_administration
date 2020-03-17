<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Car;
use App\Services\BankAccountService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends CrudController
{
    /**
     * Create a new controller instance.
     *
     * @param BankAccountService $service
     * @param BankAccount $bankAccount
     */
    public function __construct(BankAccountService $service, BankAccount $bankAccount)
    {
        parent::__construct($service, $bankAccount);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param $tenant
     * @param Request $request
     * @return Factory|View
     */
    public function index($tenant, Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $bankAccounts = BankAccount::with('bank')->get();
        $cars = Car::all();
        $total_cars = Car::count();
        $service = $this->service;
        return view('home', compact('bankAccounts', 'total_cars', 'cars', 'year', 'service'));
    }
}
