<?php
use MiladRahimi\PhpRouter\Router;
use Psr\Http\Message\ServerRequestInterface;
use MiladRahimi\PhpRouter\View\View;

class Admin
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        if (Auth::check()):            
            if(Auth::user()->level == 4):
               return $next($request);
            endif;
        endif;

        return $view->make('404');
    }
}