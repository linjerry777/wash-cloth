<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role_id != $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => '未授權的訪問'], 403);
            }
            return redirect()->route('dashboard')->with('error', '未授權的訪問');
        }

        return $next($request);
    }
}
