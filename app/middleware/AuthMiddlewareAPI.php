<?php
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use MiladRahimi\PhpRouter\Router;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthMiddlewareAPI
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        if (!Auth::check()) {
            // Call the next middleware/controller
            return $next($request);
        }

        $obj = new stdClass;
        $obj->status = "error";
        $obj->message = "Bạn đã đăng nhập tài khoản";
        $obj->href = "/";

        return new JsonResponse($obj, 401);
    }
}
