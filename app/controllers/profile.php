<?php
namespace App\Controllers;

use Auth;
use DB;
use Game;
use Hash;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use stdClass;

class Profile
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function profile(View $view)
    {
        return $view->make('profile/info');
    }

    public function OrdersRating(ServerRequest $request, $idacc)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $save2 = new stdClass;

        $body = objectToArray($request->getParsedBody());

        $content = isset($body->content) ? str($body->content) : null;
        $star = isset($body->star) ? $body->star : null;

        $ck = $DB->num_rows('buy', ['UserID' => Auth::user()->uid, 'id' => $idacc])->get();

        if (empty($ck)):

            $obj->status = "error";
            $obj->message = "Sản phẩm không tồn tại";

            return new JsonResponse($obj, 401);

        else:

            $ck = $DB->find('buy', ['UserID' => Auth::user()->uid, 'id' => $idacc])->get();
            $ck2 = $DB->num_rows('shop', ['id' => $ck->ShopID])->get();

            if (empty($ck2)):

                $obj->status = "error";
                $obj->message = "Cửa hàng không tồn tại";

                return new JsonResponse($obj, 401);

            else:

                $ck2 = $DB->find('shop', ['id' => $ck->ShopID])->get();
                $ck3 = $DB->num_rows('users', ['uid' => $ck2->UserID])->get();

                if (empty($ck3)):

                    $obj->status = "error";
                    $obj->message = "Cửa hàng không tồn tại";

                    return new JsonResponse($obj, 401);

                elseif (empty($star)):

                    $obj->status = "error";
                    $obj->message = "Sao đánh giá không được bỏ trống";

                    return new JsonResponse($obj, 401);

                elseif ($star > 5):

                    $obj->status = "error";
                    $obj->message = "Sao đánh giá không hợp lệ";

                    return new JsonResponse($obj, 401);

                elseif (!empty($ck->rating)):

                    $obj->status = "error";
                    $obj->message = "Bạn đã đánh giá tài khoản này trước đó,Không thể đánh giá lại";

                    return new JsonResponse($obj, 401);

                else:

                    $save->UserID = Auth::user()->uid;
                    $save->AccID = $ck->id;
                    $save->ShopID = $ck->ShopID;
                    $save->content = $content;
                    $save->star = $star;
                    $save->createDate = time();

                    $save2->starnum = $ck2->starnum +1;

                    if ($ck2->star < $star):
                        $save2->star = $star;
                    endif;

                    if ($DB->save('rating', $save)->status() && $DB->update("shop", $save2, ['id' => $ck->ShopID])->status() && $DB->update("buy", ['rating' => 1], ['UserID' => Auth::user()->uid, 'id' => $idacc])->status()):

                        $obj->status = "success";
                        $obj->message = "Đã gửi đánh giá <b>$star</b> sao tới người bán , cảm ơn bạn đã đánh giá !";
                        $obj->href = "/orders";

                        return new JsonResponse($obj, 200);

                    else:

                        $obj->status = "error";
                        $obj->message = "Đánh giá tài khoản thất bại vui lòng thử lại sau";

                        return new JsonResponse($obj, 401);

                    endif;

                endif;

            endif;

        endif;

    }

    public function OrdersInfo($idacc)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $ck = $DB->num_rows('buy', ['UserID' => Auth::user()->uid, 'id' => $idacc])->get();

        if (empty($ck)):

            $obj->status = "error";
            $obj->message = "Sản phẩm không tồn tại";

            return new JsonResponse($obj, 401);

        else:

            $ck = $DB->find('buy', ['UserID' => Auth::user()->uid, 'id' => $idacc])->get();

            $obj->status = "success";
            $obj->message = "Lấy thông tin tài khoản thành công";
            $obj->orders = [
                'account' => $ck->account,
                'password' => $ck->password,
                'info' => Game::info($ck->info),
                'login' => Game::loginHTML($ck->login),
            ];

            return new JsonResponse($obj, 200);

        endif;

    }

    public function OrdersShop($idacc)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $ck = $DB->num_rows('buy', ['UserID' => Auth::user()->uid, 'id' => $idacc])->get();

        if (empty($ck)):

            $obj->status = "error";
            $obj->message = "Sản phẩm không tồn tại";

            return new JsonResponse($obj, 401);

        else:

            $ck = $DB->find('buy', ['UserID' => Auth::user()->uid, 'id' => $idacc])->get();
            $ck2 = $DB->num_rows('shop', ['id' => $ck->ShopID])->get();

            if (empty($ck2)):

                $obj->status = "error";
                $obj->message = "Cửa hàng không tồn tại";

                return new JsonResponse($obj, 401);

            else:

                $ck2 = $DB->find('shop', ['id' => $ck->ShopID])->get();
                $ck3 = $DB->num_rows('users', ['uid' => $ck2->UserID])->get();

                if (empty($ck3)):

                    $obj->status = "error";
                    $obj->message = "Cửa hàng không tồn tại";

                    return new JsonResponse($obj, 401);

                else:

                    $ck3 = $DB->find('users', ['uid' => $ck2->UserID])->get();

                    $obj->status = "success";
                    $obj->message = "Lấy thông tin tài khoản thành công";
                    $obj->shop = [
                        'avatar' => $ck3->avatar,
                        'name' => $ck3->fullname,
                        'username' => $ck3->username,
                    ];

                    return new JsonResponse($obj, 200);

                endif;

            endif;

        endif;

    }

    public function orders(View $view)
    {
        $DB = DB::connect();
        $tk = isset($_GET['tai-khoan']) ? $_GET['tai-khoan'] : 'all';

        if ($tk == 'all'):
            $buy = $DB->find('buy', ['UserID' => Auth::user()->uid])->all();
        else:
            $buy = $DB->find('buy', ['UserID' => Auth::user()->uid, 'game' => $tk])->all();
        endif;

        return $view->make('profile/orders', [
            'buy' => $buy,
            'tk' => $tk,
        ]);
    }

    public function ChangePass(View $view)
    {
        return $view->make('profile/password');
    }

    public function RechargePro(View $view)
    {
        return $view->make('profile/recharge');
    }

    public function CardProfile(View $view)
    {
        $DB = DB::connect();
        $card = DB::find("card",['UserID'=>Auth::user()->uid])->all();
        return $view->make('profile/card',['card'=>$card]);
    }

    public function ChangeInfo(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $avatar = isset($_FILES['avatar']['name']) ? $_FILES['avatar']['name'] : null;
        $username = isset($body->username) ? str($body->username) : null;
        $fullname = isset($body->fullname) ? str($body->fullname) : null;

        $expensions = array("jpeg", "jpg", "png");
        $path = "uploads/avatar/" . Auth::user()->uid . "/" . rtrim(time() . $avatar);

        if ($username == Auth::user()->username):
            $acc = true;
        elseif (Auth::check_username($username)):
            $acc = false;
        else:
            $acc = true;
        endif;

        if (empty($username)):

            $obj->status = "error";
            $obj->message = "Tên đăng nhập không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!$acc):

            $obj->status = "error";
            $obj->message = "Tên đăng nhập đã tồn tại";

            return new JsonResponse($obj, 401);

        elseif (!empty($avatar)):

            if (in_array(strtolower(explode('.', $avatar)[1]), $expensions) === false):

                $obj->status = "error";
                $obj->message = "Chỉ hỗ trợ upload file JPEG hoặc PNG.";

                return new JsonResponse($obj, 401);

            elseif ($_FILES['avatar']['size'] > 2097152):

                $obj->status = "error";
                $obj->message = "Kích thước file không được lớn hơn 2MB";

                return new JsonResponse($obj, 401);

            else:

                if (Auth::user()->avatar != '/default_avatar.png'):
                    if (file_exists('public/' . Auth::user()->avatar)):
                        unlink('public/' . Auth::user()->avatar);
                    endif;
                endif;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], "public/" . $path) && $DB->update("users", ["fullname" => $fullname, "username" => $username, "avatar" => "/$path"], ['uid' => Auth::user()->uid])->status()):

                    $obj->status = "success";
                    $obj->message = "Cập nhập thông tin tài khoản thành công";

                    return new JsonResponse($obj, 200);

                else:

                    $obj->status = "error";
                    $obj->message = "Cập nhập thông tin tài khoản thất bại";

                    return new JsonResponse($obj, 401);

                endif;

            endif;

        elseif ($DB->update("users", ["fullname" => $fullname, "username" => $username], ['uid' => Auth::user()->uid])->status()):

            $obj->status = "success";
            $obj->message = "Cập nhập thông tin tài khoản thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Cập nhập thông tin tài khoản thất bại";

            return new JsonResponse($obj, 401);

        endif;

    }

    public function ChangePassword(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $password = isset($body->password) ? $body->password : null;
        $new_password = isset($body->new_password) ? $body->new_password : null;
        $confirm_new_password = isset($body->confirm_new_password) ? $body->confirm_new_password : null;

        if (empty($password)):

            $obj->status = "error";
            $obj->message = "Mật khẩu cũ không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!Hash::check($password, Auth::user()->password)):

            $obj->status = "error";
            $obj->message = "Mật khẩu cũ không chính xác";

            return new JsonResponse($obj, 401);

        elseif (empty($new_password)):

            $obj->status = "error";
            $obj->message = "Mật khẩu mới không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($confirm_new_password)):

            $obj->status = "error";
            $obj->message = "Xác nhận mật khẩu mới không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($new_password === $password):

            $obj->status = "error";
            $obj->message = "Mật khẩu mới không được giống mật khẩu cũ";

            return new JsonResponse($obj, 401);

        elseif ($DB->update("users", ["password" => Hash::make($new_password)], ['uid' => Auth::user()->uid])->status()):

            $obj->status = "success";
            $obj->message = "Đổi mật khẩu tài khoản thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Đổi mật khẩu tài khoản thất bại";

            return new JsonResponse($obj, 401);

        endif;

    }

}
