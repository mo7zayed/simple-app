<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeesRequest;
use App\Models\Employee;

class EmployeesController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Employee::with('company:id,name')->latest()->get(),
        ]);
    }

    public function store(EmployeesRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->back();
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json(
            status: 200
        );
    }
}
