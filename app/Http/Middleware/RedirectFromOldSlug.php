<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectFromOldSlug
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
        $url = parse_url($request->url());
        $route = array_key_exists('path', $url) ? $url['path'] : '';
        $redirect = Redirect::where('old_slug', $route)
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->first();
        if ($redirect === null) {
        return $next($request);
        }

        while ($redirect !== null) {
        $route = $redirect->new_slug;
        $redirect = Redirect::where('old_slug', $route)
            ->where('created_at', '>', $redirect->created_at)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();
        }
        return redirect($route);
    }
}
