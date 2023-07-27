<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($attributes)) {
            session()->regenerate();
            return redirect(Auth::user()->role == "admin" ? 'anasayfa' : 'bugun')->with(['success' => 'Giriş Yapıldı.']);
        } else {
            return back()->withErrors(['email' => 'E-posta ya da şifreniz geçersiz.']);
        }
    }

    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success' => 'Çıkış Yapıldı.']);
    }
}
