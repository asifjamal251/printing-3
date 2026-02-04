<?php
namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginTimeRestriction
{
    public function handle($request, Closure $next)
{
    $admin = auth('admin')->user();

    if (!$admin) {
        return redirect()->route('admin.login.form')
            ->with('error', 'Unauthorized access.');
    }

    if ($admin->login_time_restriction_enabled == 15) {
        return $next($request);
    }

    $now  = Carbon::now('Asia/Kolkata');
    $from = Carbon::createFromTimeString($admin->login_allowed_from, 'Asia/Kolkata');
    $to   = Carbon::createFromTimeString($admin->login_allowed_to, 'Asia/Kolkata');

    if (!$now->between($from, $to)) {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login.form')->with([
            'class'   => 'bg-danger',
            'message' => 'Login allowed only between ' .
                         $from->format('H:i') . ' and ' .
                         $to->format('H:i') .
                         '. Current time: ' . $now->format('H:i'),
        ]);
    }

    return $next($request);
}
}

