<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
            if(Auth::getUser()->id_level_user!=2){
              return $next($request);
            }else{
              return redirect('/home');
            }
           /* }else{
                 Auth::logout();
                 return redirect('/login')->with('title', 'Akun Belum Terverifikasi')->with('message', 'Akun anda belum diverifikasi. Silahkan verifikas email anda terlebih dahulu!')->with('type', 'warning');
            }*/
        }

        return redirect('/login');
    }
}
