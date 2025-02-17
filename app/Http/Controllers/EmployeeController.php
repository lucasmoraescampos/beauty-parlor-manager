<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function show(string $uid): Employee
    {
        return Employee::with('department', 'jobTitle')
            ->where('uid', $uid)->firstOrFail();
    }
}
