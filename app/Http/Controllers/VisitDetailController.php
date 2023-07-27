<?php

namespace App\Http\Controllers;

use App\Models\Visit;

class VisitDetailController extends Controller
{
    public function create($id)
    {
        return view('visit_detail', ['visit' => Visit::where('id', $id)->first()]);
    }
}
