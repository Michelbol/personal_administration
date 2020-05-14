<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $banks = Bank::query();
        if(isset($request->q)){
            $banks = $banks->where('name','like', "%". $request->q ."%");
        }

        $banks = $banks->get();
        return response()->json($banks);
    }
}
