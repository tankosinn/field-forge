<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{
    public function list()
    {
        return view('pages.admins.list', ['users' => User::where('role', 'admin')->get()]);
    }

    public function create($slug = null)
    {
        return view('pages.admins.admin', ['user' => $slug ? User::where('slug', $slug)->where('role', 'admin')->first() : null]);
    }

    public function store()
    {
        $request = request();

        $user = User::where('id', $request->id)->where('role', 'admin')->first();

        $validation = [
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($request->id)],
            'password' => ['nullable', 'min:5', 'max:20'],
            'phone' => ['numeric']
        ];

        if (!$user) {
            array_unshift($validation['password'], 'required');
        }

        $attributes = $request->validate($validation);

        if (!empty($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        $attributes['role'] = 'admin';
        $attributes['status'] = true;
        $attributes['super_admin'] = false;
        $attributes['slug'] = null;

        if ($user) {
            $user->update($attributes);
        } else {
            User::create($attributes);
        }

        session()->flash('success', 'Kaydedildi.');

        return response()->json([
            "status" => true
        ]);
    }
}
