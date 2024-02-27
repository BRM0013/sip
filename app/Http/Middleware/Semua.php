<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Semua
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            /*if (Auth::getUser()->status_verifikasi == 'Ya') {*/
                return $next($request);
           /* }else{
                 Auth::logout();
                 return redirect('/login')->with('title', 'Akun Belum Terverifikasi')->with('message', 'Akun anda belum diverifikasi. Silahkan verifikas email anda terlebih dahulu!')->with('type', 'warning');
            }*/
        }

        return redirect('/login');
    }
}
