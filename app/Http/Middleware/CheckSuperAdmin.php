<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->is_super_admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Fitur Data Transaksional hanya dapat diakses oleh Super Admin.'
                ], 403);
            }

            abort(403, 'Fitur Data Transaksional hanya dapat diakses oleh Super Admin.');
        }

        return $next($request);
    }
}
