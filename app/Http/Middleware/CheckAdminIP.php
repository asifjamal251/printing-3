<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminIP
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login.form')->with('error', 'Unauthorized access.');
        }

        // Skip IP check if disabled
        if ($admin->ip_enabled == 15) {
            return $next($request);
        }

        $clientIp = $request->ip();
        $allowedIps = $admin->ips()->pluck('ip_address')->toArray();

        //dd($clientIp);

        if (in_array($clientIp, $allowedIps)) {
            return $next($request);
        }

        // ❌ IP not allowed → Logout + redirect with message
        Auth::guard('admin')->logout();

        return redirect()
            ->route('admin.login.form')
            ->with(['class'=>'error','message'=>'Access denied from this IP address. You have been logged out.']);
    }
}