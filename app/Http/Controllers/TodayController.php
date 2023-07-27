<?php

namespace App\Http\Controllers;

use App\Models\EmployeeRoute;
use Illuminate\Support\Facades\Auth;

class TodayController extends Controller
{
    public function create()
    {
        return view('today', [
            'routes' => EmployeeRoute::where('employee', Auth::user()->id)->orderBy('sort')->get()->groupBy('day')
        ]);
    }
}
