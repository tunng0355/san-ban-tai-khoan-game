<?php
namespace App\Controllers;

use DB;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use stdClass;

class AdminController
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function dashboard(View $view)
    {
        return $view->make('admin/dashboard');
    }

    public function member(View $view)
    {
        return $view->make('admin/member/index');
    }

    public function bank(View $view)
    {
        return $view->make('admin/bank/index');
    }

    public function EditBank(View $view, $id)
    {
        if (empty(DB::connect()->num_rows('bank', ['id' => $id])->get())):
            return new RedirectResponse('/admin/bank');
        endif;

        $bank = DB::connect()->find('bank', ['id' => $id])->get();
        return $view->make('admin/bank/edit', ['bank' => $bank]);
    }

    public function EditbankUP(ServerRequest $request, $id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $bank = isset($body->bank) ? $body->bank : null;
        $stk = isset($body->stk) ? $body->stk : null;
        $ctk = isset($body->ctk) ? $body->ctk : null;
        $status = isset($body->status) ? $body->status : null;
        $qrcode = isset($body->qrcode) ? $body->qrcode : null;

        $save->bank = $bank;
        $save->stk = $stk;
        $save->ctk = $ctk;
        $save->qrcode = $qrcode;

        $save->status = $status;
        
        $ck = $DB->num_rows('bank', ['id' => $id])->get();

        if (empty($ck)):

            $obj->status = "error";
            $obj->message = "Ngân hàng không tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($bank)):

            $obj->status = "error";
            $obj->message = "Ngân hàng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($stk)):

            $obj->status = "error";
            $obj->message = "Số tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($ctk)):

            $obj->status = "error";
            $obj->message = "Chủ tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($status)):

            $obj->status = "error";
            $obj->message = "Trạng thái không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($DB->update("bank", $save, ['id' => $id])->status()):

            $obj->status = "success";
            $obj->message = "Cập nhập ngân hàng thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Cập nhập ngân hàng thất bại";

            return new JsonResponse($obj, 401);

        endif;
    }

    public function Deletebank($id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $check = $DB->num_rows("bank", ['id' => $id])->get();

        if (empty($check)):

            header('location: /admin/bank');

        elseif ($DB->delete("bank", ['id' => $id])->status()):
            header('location: /admin/bank');
        else:
            header('location: /admin/bank');
        endif;

    }
    public function addbank(ServerRequest $request)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $bank = isset($body->bank) ? $body->bank : null;
        $stk = isset($body->stk) ? $body->stk : null;
        $ctk = isset($body->ctk) ? $body->ctk : null;
        $status = isset($body->status) ? $body->status : null;
        $qrcode = isset($body->qrcode) ? $body->qrcode : null;

        $save->bank = $bank;
        $save->stk = $stk;
        $save->ctk = $ctk;
        $save->qrcode = $qrcode;
        $save->status = $status;
        $save->createDate = time();

        if (empty($bank)):

            $obj->status = "error";
            $obj->message = "Ngân hàng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($stk)):

            $obj->status = "error";
            $obj->message = "Số tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($ctk)):

            $obj->status = "error";
            $obj->message = "Chủ tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($status)):

            $obj->status = "error";
            $obj->message = "Trạng thái không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($DB->save("bank", $save)->status()):

            $obj->status = "success";
            $obj->message = "Thêm ngân hàng thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Thêm ngân hàng thất bại";

            return new JsonResponse($obj, 401);

        endif;
    }

    public function post(View $view)
    {
        return $view->make('admin/post/index');
    }

    public function withdraw(View $view)
    {
        return $view->make('admin/withdraw/index');
    }

    public function card(View $view)
    {
        return $view->make('admin/card/index');
    }

    public function withdrawType(View $view, $id, $type)
    {
        if (empty(DB::connect()->num_rows('withdraw', ['id' => $id, 'status' => 'pending'])->get())):
            return new RedirectResponse('/admin/rut');
        endif;

        if ($type == "duyet") {
            DB::connect()->update('withdraw', ['status' => 'active'], ['id' => $id])->status();
            return new RedirectResponse('/admin/rut');
        } elseif ($type == "tuchoi") {
            DB::connect()->update('withdraw', ['status' => 'refuse'], ['id' => $id])->status();
            $ck = DB::connect()->find('withdraw', ['id' => $id])->get();
            $shop = DB::connect()->find('shop', ['id' => $ck->ShopID])->get();
            $ck2 = DB::connect()->find('users', ['uid' => $shop->UserID])->get();

            $money = $ck2->money + $ck->quantity;

            DB::connect()->update('users', ['money' => $money], ['uid' => $shop->UserID])->status();

            return new RedirectResponse('/admin/rut');
        } else {
            return new RedirectResponse('/admin/rut');
        }

    }

    public function Deletepost($id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $check = $DB->num_rows("post", ['id' => $id])->get();

        if (empty($check)):

            header('location: /admin/post');

        elseif ($DB->delete("post", ['id' => $id])->status()):
            header('location: /admin/post');
        else:
            header('location: /admin/post');
        endif;

    }

    public function shop(View $view)
    {
        return $view->make('admin/shop/index');
    }

    public function Editshop(View $view, $id)
    {
        if (empty(DB::connect()->num_rows('shop', ['id' => $id])->get())):
            return new RedirectResponse('/admin/shop');
        endif;

        $shop = DB::connect()->find('shop', ['id' => $id])->get();
        $users = DB::connect()->find('users', ['uid' => $shop->UserID])->get();
        return $view->make('admin/shop/edit', ['shop' => $shop, 'users' => $users]);
    }

    public function Editmember(View $view, $id)
    {
        if (empty(DB::connect()->num_rows('users', ['uid' => $id])->get())):
            return new RedirectResponse('/admin/member');
        endif;

        $users = DB::connect()->find('users', ['uid' => $id])->get();
        return $view->make('admin/member/edit', ['users' => $users]);
    }

    public function dashboardUP(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $logo = isset($body->logo) ? $body->logo : null;
        $title = isset($body->title) ? $body->title : null;
        $keywords = isset($body->keywords) ? $body->keywords : null;
        $description = isset($body->description) ? $body->description : null;
        $email = isset($body->email) ? $body->email : null;
        $hotline = isset($body->hotline) ? $body->hotline : null;
        $Fanpage = isset($body->Fanpage) ? $body->Fanpage : null;
        $group = isset($body->group) ? $body->group : null;
        $messenger = isset($body->messenger) ? $body->messenger : null;
        $copyright = isset($body->copyright) ? $body->copyright : null;
        $phirut = isset($body->phirut) ? $body->phirut : null;

        if (empty($logo)):

            $obj->status = "error";
            $obj->message = "Biểu tượng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($title)):

            $obj->status = "error";
            $obj->message = "Tiêu đề không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($keywords)):

            $obj->status = "error";
            $obj->message = "Từ khóa không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($description)):

            $obj->status = "error";
            $obj->message = "Mô tả không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($email)):

            $obj->status = "error";
            $obj->message = "Email hỗ trợ không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($hotline)):

            $obj->status = "error";
            $obj->message = "Hotline hỗ trợ không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($copyright)):

            $obj->status = "error";
            $obj->message = "Bản quyền không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($phirut == null):

            $obj->status = "error";
            $obj->message = "Phí rút không được bỏ trống";

            return new JsonResponse($obj, 401);

        else:

            $save->logo = $logo;
            $save->title = $title;
            $save->keywords = $keywords;
            $save->description = $description;
            $save->email = $email;
            $save->hotline = $hotline;
            $save->Fanpage = $Fanpage;
            $save->group = $group;
            $save->messenger = $messenger;
            $save->copyright = $copyright;
            $save->phirut = $phirut;

            if ($DB->update("setting", $save)->status()):

                $obj->status = "success";
                $obj->message = "Cập nhập cài đặt thành công";
                return new JsonResponse($obj, 200);

            else:

                $obj->status = "error";
                $obj->message = "Cập nhập cài đặt thất bại";

                return new JsonResponse($obj, 401);

            endif;

        endif;
    }

    public function Deletemember($id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $check = $DB->num_rows("users", ['uid' => $id])->get();

        if (empty($check)):
            header('location: /admin/member');
        else:

            $users = $DB->find("users", ['uid' => $id])->get();

            if (file_exists('public/uploads/avatar/' . $users->uid)):
                unlink('public/uploads/avatar/' . $users->uid);
            endif;

            if ($DB->delete("users", ['uid' => $id])->status()):

                if (!empty($DB->num_rows("shop", ['UserID' => $id])->get())):
                    $shop = $DB->find("shop", ['UserID' => $id])->get();
                    $ck = $DB->num_rows("account", ['ShopID' => $shop->id])->get();

                    if (file_exists('public/uploads/account/' . $shop->id)):
                        unlink('public/uploads/account/' . $shop->id);
                    endif;

                    if (!empty($ck)):
                        $DB->delete("account", ['ShopID' => $shop->id])->status();
                    endif;

                    $DB->delete("shop", ['UserID' => $id])->status();
                endif;

                header('location: /admin/member');
            else:
                header('location: /admin/member');
            endif;

        endif;
    }

    public function EditmemberUP(ServerRequest $request, $id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $fullname = isset($body->fullname) ? str($body->fullname) : null;
        $email = isset($body->email) ? $body->email : null;
        $money = isset($body->money) ? $body->money : 'not';
        $level = isset($body->level) ? $body->level : null;
        $status = isset($body->status) ? $body->status : null;

        $check = $DB->num_rows("users", ['uid' => $id])->get();
        $check2 = $DB->find("users", ['uid' => $id])->get();

        if (!empty($check)):
            if ($email == $check2->email):
                $acc = true;
            elseif (Auth::check_email($email)):
                $acc = false;
            else:
                $acc = true;
            endif;
        endif;

        if (empty($check)):

            $obj->status = "error";
            $obj->message = "Tài khoản không tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($fullname)):

            $obj->status = "error";
            $obj->message = "Họ và tên không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($email)):

            $obj->status = "error";
            $obj->message = "Email không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!$acc):

            $obj->status = "error";
            $obj->message = "Email này đã được một tài khoản khác sử dụng";

            return new JsonResponse($obj, 401);

        elseif ($money == 'not'):

            $obj->status = "error";
            $obj->message = "Số dư tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!is_numeric($money)):

            $obj->status = "error";
            $obj->message = "Số dư tài khoản phải là dạng số";

            return new JsonResponse($obj, 401);

        else:

            $save->fullname = $fullname;
            $save->email = $email;
            $save->money = $money;
            $save->level = $level;
            $save->status = $status;

            if ($DB->update("users", $save, ['uid' => $id])->status()):

                $obj->status = "success";
                $obj->message = "Lưu thông tin tài khoản thành công";

                return new JsonResponse($obj, 200);

            else:

                $obj->status = "error";
                $obj->message = "Lưu thông tin tài khoản thất bại";

                return new JsonResponse($obj, 401);

            endif;

        endif;
    }

    public function Deleteshop($id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $check = $DB->num_rows("shop", ['id' => $id])->get();

        if (empty($check)):

            header('location: /admin/shop');

        elseif ($DB->delete("shop", ['id' => $id])->status()):

            $ck = $DB->num_rows("account", ['ShopID' => $id])->get();

            if (file_exists('public/uploads/account/' . $id)):
                unlink('public/uploads/account/' . $id);
            endif;

            if (!empty($ck)):
                $DB->delete("account", ['ShopID' => $id])->status();
            endif;

            header('location: /admin/shop');

        else:
            header('location: /admin/shop');
        endif;

    }

    public function EditshopUP(ServerRequest $request, $id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $facebook = isset($body->facebook) ? str($body->facebook) : null;
        $zalo = isset($body->zalo) ? $body->zalo : null;
        $hotline = isset($body->hotline) ? $body->hotline : 'not';
        $content = isset($body->content) ? $body->content : null;
        $status = isset($body->status) ? $body->status : null;

        $check = $DB->num_rows("shop", ['id' => $id])->get();
        $check2 = $DB->find("shop", ['id' => $id])->get();

        if (empty($check)):

            $obj->status = "error";
            $obj->message = "Cửa hàng không tồn tại";

            return new JsonResponse($obj, 401);

        else:

            $save->facebook = $facebook;
            $save->zalo = $zalo;
            $save->hotline = $hotline;
            $save->content = $content;
            $save->status = $status;

            $stt = ($status == 'active') ? 1 : 0;

            if ($DB->update("shop", $save, ['id' => $id])->status() && $DB->update("users", ['shop' => $stt], ['uid' => $check2->UserID])->status()):

                if ($status === 'active'):

                    if (!file_exists("public/uploads/account/" . $id)):

                        mkdir('public/uploads/account/' . $id, 0777, true);

                    endif;

                elseif (file_exists("public/uploads/account/" . $id)):

                    unlink("public/uploads/account/" . $id);

                endif;

                $obj->status = "success";
                $obj->message = "Lưu thông tin thành công";

                return new JsonResponse($obj, 200);

            else:

                $obj->status = "error";
                $obj->message = "Lưu thông tin thất bại";

                return new JsonResponse($obj, 401);

            endif;

        endif;
    }

}
