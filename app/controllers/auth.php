<?php
namespace App\Controllers;

use Auth;
use DB;
use Hash;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use Pusher\Pusher;
use stdClass;

class AuthController
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function logout()
    {
        Auth::logout();
        return new RedirectResponse('/');
    }

    public function loginViews(View $view)
    {
        return $view->make('auth/login');
    }

    public function registerViews(View $view)
    {
        return $view->make('auth/register');
    }

    public function login(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $username = isset($body->username) ? $body->username : null;
        $password = isset($body->password) ? $body->password : null;

        $users = $DB->find("users", ['username' => $username])->get();


        if (Auth::check()):

            $obj->status = "error";
            $obj->message = "Bạn đã đăng nhập tài khoản";
            $obj->href = "/";

            return new JsonResponse($obj, 401);

        elseif (empty($username)):

            $obj->status = "error";
            $obj->message = "Tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!Auth::check_username($username)):

            $obj->status = "error";
            $obj->message = "Tài khoản không tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($password)):

            $obj->status = "error";
            $obj->message = "Mật khẩu không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!Hash::check($password, $users->password)):

            $obj->status = "error";
            $obj->message = "Mật khẩu tài khoản không chính xác";

            return new JsonResponse($obj, 401);

        elseif (Auth::login($users)):

            $obj->status = "success";
            $obj->message = "Đăng nhập tài khoản thành công";
            $obj->href = "/";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Đăng nhập tài khoản thất bại";

            return new JsonResponse($obj, 401);

        endif;

    }

    public function register(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());


        $username = isset($body->username) ? str($body->username) : null;
        $email = isset($body->email) ? str($body->email) : null;
        $password = isset($body->password) ? $body->password : null;
        $confirm_password = isset($body->confirm_password) ? $body->confirm_password : null;

        if (Auth::check()):

            $obj->status = "error";
            $obj->message = "Bạn đã đăng nhập tài khoản";
            $obj->href = "/";

            return new JsonResponse($obj, 401);

        elseif (empty($username)):

            $obj->status = "error";
            $obj->message = "Tên đăng nhập không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (Auth::check_username($username)):

            $obj->status = "error";
            $obj->message = "Tên đăng nhập đã tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($email)):

            $obj->status = "error";
            $obj->message = "Địa chỉ email không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)):

            $obj->status = "error";
            $obj->message = "Địa chỉ email không hợp lệ";

            return new JsonResponse($obj, 401);

        elseif (Auth::check_email($email)):

            $obj->status = "error";
            $obj->message = "Địa chỉ email đã tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($password)):

            $obj->status = "error";
            $obj->message = "Mật khẩu không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($confirm_password)):

            $obj->status = "error";
            $obj->message = "Xác nhận mật khẩu không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($password != $confirm_password):

            $obj->status = "error";
            $obj->message = "Mật khẩu xác nhận không chính xác";

            return new JsonResponse($obj, 401);

        else:

            $save = new stdClass;
            $save->email = $email;
            $save->avatar = "/default_avatar.png";
            $save->fullname = $username;
            $save->username = $username;
            $save->password = hash::make($password);
            $save->level = 0;
            $save->status = "active";
            $save->createDate = time();
            $save->createType = "account";

            if (Auth::save($save)):

                $obj->status = "success";
                $obj->message = "Đăng ký tài khoản thành công";
                $obj->href = "/";

                mkdir('public/uploads/avatar/' . DB::$getID, 0777, true);

                return new JsonResponse($obj, 200);

            else:

                $obj->status = "error";
                $obj->message = "Đăng ký tài khoản thất bại";

                return new JsonResponse($obj, 401);

            endif;

        endif;

    }

}
