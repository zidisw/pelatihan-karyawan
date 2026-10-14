<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            abort(403, 'Akses Ditolak.');
        }

        // Superadmin selalu memiliki hak akses penuh ke seluruh fitur sistem
        if ($request->user()->role === 'superadmin') {
            return $next($request);
        }

        // Cek apakah role user saat ini cocok dengan role yang diizinkan
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin ke halaman ini.');
        }

        return $next($request);
    }
}
