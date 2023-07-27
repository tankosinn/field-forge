<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;


class ReportController extends Controller
{
    public function list()
    {
        return view('pages.reports.list');
    }

    public function day()
    {
        return view('pages.reports.day');
    }

    public function stores()
    {
        $generated = false;

        if (request()->all()) {
            $validator = validator(request()->all(), [
                'generated' => 'required|in:true',
                'from' => 'required',
                'until' => 'required',
            ], [
                'generated.required' => 'Raporun oluşturulması zorunludur.',
                'generated.in' => 'Raporun oluşturulması zorunludur.',
                'from.required' => 'Tarih aralığı doldurulması zorunludur.',
                'until.required' => 'Tarih aralığı doldurulması zorunludur.',
            ]);


            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());

                return redirect('rapor/olustur/satis-noktalari');
            }

            $generated = true;
        }

        return view('pages.reports.stores', [
            'stores' => Store::all(),
            'employees' => User::where('role', 'employee')->where('manager', Auth::user()->id)->get(),
            'generated' => $generated
        ]);
    }

    public function stores_pdf()
    {
        if (request()->all()) {
            $validator = validator(request()->all(), [
                'generated' => 'required|in:true',
                'from' => 'required',
                'until' => 'required',
            ], [
                'generated.required' => 'Raporun oluşturulması zorunludur.',
                'generated.in' => 'Raporun oluşturulması zorunludur.',
                'from.required' => 'Tarih aralığı doldurulması zorunludur.',
                'until.required' => 'Tarih aralığı doldurulması zorunludur.',
            ]);


            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());

                return redirect('rapor/olustur/satis-noktalari');
            }
        }


        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.store');
        return $pdf->stream();
    }
}
