<?php

namespace App\Http\Middleware;

use Closure;

use App\Users;

class Token
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
		$user = [];
		if ($request->header('api_token')) {
			$user = Users::where('api_key', $request->header('api_token'))->first();
		}
		
		if(empty($user)){
			return response()->json(['success' => false, 'message' => 'unauthorize'], 201);
		}
		$request->attributes->add(['loginuser' => $user]);
        return $next($request);
    }
}
