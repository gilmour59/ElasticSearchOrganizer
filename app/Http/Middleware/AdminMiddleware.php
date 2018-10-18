<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::count();
        if(!($user == 1)){ //if the only one user is registered in the DB (considered as admin)
            if(!Auth::user()->hasPermissionTo('administer roles and permissions')){
                abort('401');
            }
        }
        return $next($request);
    }
}
