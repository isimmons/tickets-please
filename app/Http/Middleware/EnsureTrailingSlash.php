<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getRequestUri();

        $subDirs = ['/js', '/css', '/fonts', '/App'];
        $extensions = ['.js', '.xml', '.html'];

        foreach ($subDirs as $dir) {
            if(str_starts_with($path, $dir)) {
                return redirect('docs' . $path, 301);
            }
        }

        foreach ($extensions as $ext) {
            if(str_ends_with($path, $ext)) {
                return redirect('docs' . $path, 301);
            }
        }

        return $next($request);

    }

}
