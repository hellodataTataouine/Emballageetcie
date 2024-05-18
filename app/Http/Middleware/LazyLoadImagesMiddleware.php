<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LazyLoadImagesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if the response is an HTML response
        if ($response->headers->get('Content-Type') === 'text/html') {
            // Modify HTML response to include lazy loading attributes for images
            $content = $response->getContent();
            $lazyLoadedContent = $this->lazyLoadImages($content);

            $response->setContent($lazyLoadedContent);
        }

        return $response;
    }

    protected function lazyLoadImages($content)
    {
        // Use regular expression to find image tags and add lazy loading attributes
        $lazyLoadedContent = preg_replace('/<img(.*?)src="(.*?)"(.*?)>/', '<img$1src="$2" data-src="$2"$3 class="lazyload">', $content);

        return $lazyLoadedContent;
    }
}
