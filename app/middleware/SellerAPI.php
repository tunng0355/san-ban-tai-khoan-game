<?php
use MiladRahimi\PhpRouter\Router;
use Psr\Http\Message\ServerRequestInterface;
use MiladRahimi\PhpRouter\View\View;

class SellerAPI
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        $DB = DB::connect();

        if (Auth::check()):            
            if(!empty(Auth::user()->shop)):
                $shop = $DB::connect()->num_rows("shop",['UserID'=>Auth::user()->uid])->get();
                if(!empty($shop)):
                    return $next($request);
                endif;
            endif;
        endif;

        return new RedirectResponse('/auth/login');
    }
}