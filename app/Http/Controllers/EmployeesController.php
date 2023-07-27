<?php

namespace App\Http\Controllers;

use App\Models\EmployeeRoute;
use App\Models\Store;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    public function list()
    {
        return view('pages.employees.list', ['users' => User::where('role', 'employee')->get()]);
    }

    public function create($slug = null)
    {
        return view('pages.employees.employee', [
            'user' => $slug ? User::where('slug', $slug)->where('role', 'employee')->first() : null,
            'managers' => User::where('role', 'admin')->get(),
            'stores' => Store::orderBy('name')->get()
        ]);
    }

    public function store()
    {
        $request = request();

        $employee = User::where('id', $request->id)->where('role', 'employee')->first();

        $validation = [
            'manager' => ['required'],
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($request->id)],
            'password' => ['nullable', 'min:5', 'max:20'],
            'phone' => ['numeric']
        ];

        if (!$employee) {
            array_unshift($validation['password'], 'required');
        }

        $attributes = $request->validate($validation);

        if (!empty($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        $attributes['role'] = 'employee';
        $attributes['status'] = true;
        $attributes['super_admin'] = false;
        $attributes['slug'] = null;

        if ($employee) {
            $employee->update($attributes);
        } else {
            $employee = User::create($attributes);
        }

        if (isset($request['routes'])) {
            foreach ($request['routes'] as $day => $routeList) {
                foreach ($routeList as $sort => $route) {
                    $employeeRoute = EmployeeRoute::withTrashed()->where('day', $day)->where('store_branch', $route['id'])->first();
                    if ($employeeRoute) {
                        $employeeRoute->restore();
                        $employeeRoute->update(['sort' => $sort]);
                        if (isset($route['delete']) && $route['delete']) {
                            EmployeeRoute::where('id', $route['route'])->delete();
                        }
                    } else {
                        EmployeeRoute::create([
                            'employee' => $employee->id,
                            'store_branch' => $route['id'],
                            'day' => $day,
                            'sort' => $sort
                        ]);
                    }
                }
            }
        }

        session()->flash('success', 'Kaydedildi.');

        return response()->json([
            "status" => true
        ]);
    }
}
