<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home($slug = null)
    {
        if (Auth::user() && Auth::user()->role == "admin") {
            if ($slug) {
                $day = getDays()[$slug]['index'];
            } else {
                $day = Carbon::now()->dayOfWeekIso - 1;
            }

            $employees = User::where('role', 'employee')->where('manager', Auth::user()->id)->get();

            $count_routes = 0;
            $count_visiting = 0;
            $count_visited = 0;
            $count_visits = 0;
            $average_time = 0;

            $rangeWeek = rangeWeek(date('Y-m-d'));

            $routes = [];
            $visits = [];

            foreach ($employees as $employee) {
                $routes[$employee->id] = $employee->routes()->where('day', $day)->get();

                $count_routes += $routes[$employee->id]->count();

                foreach ($routes[$employee->id] as $route) {
                    $visits[$route->id] = $route->visit()->where('created_at', '>=', $rangeWeek['start'])->where('created_at', '<=', $rangeWeek['end'])->first();

                    if ($visits[$route->id]) {
                        if ($visits[$route->id]->status == 1) {
                            $count_visiting += 1;
                        } elseif ($visits[$route->id]->status == 2) {
                            $count_visited += 1;
                            $average_time += $visits[$route->id]->updated_at->diffInSeconds($visits[$route->id]->created_at);
                        }

                        $count_visits += 1;
                    }
                }
            }

            if ($count_visited > 0) {
                $average_time = $average_time / $count_visited;
            }

            return view('dashboard', [
                'employees' => $employees,
                'routes' => $routes,
                'visits' => $visits,
                'stats' => [
                    'count_routes' => $count_routes,
                    'count_visiting' => $count_visiting,
                    'count_visited' => $count_visited,
                    'count_visits' => $count_visits,
                    'average_time' => $average_time
                ],
                'rangeWeek' => rangeWeek(date('Y-m-d'))
            ]);
        }

        return redirect('bugun');
    }
}
