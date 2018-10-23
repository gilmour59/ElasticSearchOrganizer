<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class ClearanceMiddleware
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
        if(Auth::user()->id === 1){
            return $next($request);
        }

        if (Auth::user()->hasPermissionTo('administer roles and permissions')) //If user has this //permission
        {
            return $next($request);
        }

        if ($request->is('store'))//If user is creating a post
        {
            if (!Auth::user()->hasPermissionTo('create post')){
                abort('401');
            }else{
                return $next($request);
            }
        }

        if ($request->is('view_files'))//If user is creating a post
        {
            if (!Auth::user()->hasPermissionTo('create post')){
                abort('401');
            }else{
                return $next($request);
            }
        }

        if ($request->is('update/*')) //If user is editing a post
        {
            if (!Auth::user()->hasPermissionTo('edit post')){
                abort('401');
            }else{
                return $next($request);
            }
        }

        if ($request->is('get/*')) //If user is editing a post
        {
            if (!Auth::user()->hasPermissionTo('edit post')){
                abort('401');
            }else{
                return $next($request);
            }
        }

        if ($request->isMethod('Delete')) //If user is deleting a post
        {
            if(!Auth::user()->hasPermissionTo('delete post')){
                abort('401');
            }else{
                return $next($request);
            }
        }

        if ($request->is('/')) //If user is editing a post
        {
            if (!Auth::user()->hasPermissionTo('view post')){
                abort('401');
            }else{
                return $next($request);
            }
        }
    
        return $next($request);
    }
}
