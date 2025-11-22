<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) { // Jika belum login
            return redirect('login');
        }

        $user = Auth::user();
        foreach ($roles as $role) {
            // Cek jika user punya role yang diizinkan
            if ($user->role == $role) {
                return $next($request);
            }
        }

        // Jika tidak punya role yang sesuai
        abort(403, 'Unauthorized action.'); 
    }
}