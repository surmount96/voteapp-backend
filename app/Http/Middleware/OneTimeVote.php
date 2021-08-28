<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OneTimeVote
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if($user->participated && !empty($user->participated)) {
            return response()->json(['message' => 'Thank you for participanting'],301);
        }

        return $next($request);
    }
}
