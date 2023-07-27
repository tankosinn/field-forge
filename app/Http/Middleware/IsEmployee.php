<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && Auth::user()->role == "employee") {
            $activeVisit = Visit::where('employee', Auth::user()->id)->where('status', 1)->first();

            if ($activeVisit && url()->current() !== url('ziyaret/' . $activeVisit->employee_route) && $request->isMethod('get')) {
                return redirect('ziyaret/' . $activeVisit->employee_route)->with('error', 'Öncelikle ziyareti tamamlayın.');
            }

            $response = $next($request);

            return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }

        abort(404);
    }
}
