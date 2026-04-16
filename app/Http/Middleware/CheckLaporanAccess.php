<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLaporanAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->is_super_admin || optional($user->role)->slug === 'admin-divisi') {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anda tidak memiliki hak akses untuk melihat Laporan.'
            ], 403);
        }

        abort(403, 'Anda tidak memiliki hak akses untuk melihat Laporan.');
    }
}
