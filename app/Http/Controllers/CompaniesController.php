<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompaniesController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Company::get(['id', 'name']),
        ]);
    }
}
