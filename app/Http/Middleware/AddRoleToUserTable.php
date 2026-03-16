<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddRoleToUserTable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if(!$user){
            return response()->json(['error' => 'No autenticado'], 401);
        }

        if(!in_array($user->role, $roles)){
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return $next($request);
    }
}
