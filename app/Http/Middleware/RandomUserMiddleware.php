<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RandomUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Her store işlemi için random bir kullanıcı al
        $randomUser = User::inRandomOrder()->first();

        // Middleware'den gelen request'e random user_id'yi ekle
        $request->merge(['user_id' => $randomUser->id]);

        // Sonraki middleware veya controller'a devam et
        return $next($request);
    }
}
