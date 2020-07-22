<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class AdminMiddleware {

	/**
	 * Handle request to allow users that are only
     * Designated as admin to access admin routes
     * Otherwise redirect back to home
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!Gate::allows('admin', auth()->user()))
		{
			return redirect()->route('homepage');
		}

		return $next($request);
	}

}