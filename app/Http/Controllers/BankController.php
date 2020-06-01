<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->get('q', null);
        $banks = Bank::query();
        if(isset($search)){
            $banks = $banks->where('name','like', "%$search%");
        }
        $banks = $banks->get();
        return response()->json($banks);
    }
}
