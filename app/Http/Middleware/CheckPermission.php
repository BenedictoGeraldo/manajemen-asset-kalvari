<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = auth()->user();

        // Super admin selalu lolos
        if ($user->is_super_admin) {
            return $next($request);
        }

        // Cek apakah user punya permission yang diperlukan
        if (!$user->hasPermission($permission)) {
            // Jika request AJAX atau expects JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'
                ], 403);
            }

            // Jika request normal, abort dengan 403
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
