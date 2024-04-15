<?php
namespace App\Controllers;

use Account;
use DB;
use Game;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use Promo;
use Setting;
use Shop;
use stdClass;
use Auth;

class Seller
{
    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function setting(View $view)
    {
        return $view->make('/seller/setting');
    }

    public function index(View $view)
    {
        $DB = DB::connect();

        $daban = $DB->num_rows('account', [
            'sell' => 1,
            'ShopID' => Shop::my()->id,
        ])->all();

        $tonggiatri = 0;

        $doanhthuhomnay = 0;
        $doanhthutrondoi = 0;
        $doanhthuthangnay = 0;

        foreach ($DB->find('account', ['sell' => 0, 'ShopID' => Shop::my()->id])->all() as $giatri):
            $tonggiatri += $giatri->price;
        endforeach;

        $gdganday = $DB->find('buy', ['ShopID' => Shop::my()->id], ['createDate'])->all();

        foreach ($DB->find('buy', ['ShopID' => Shop::my()->id])->all() as $giatri):
            if (date('dmY', $giatri->createDate) === date('dmY', time())):
                $doanhthuhomnay += $giatri->price;
            endif;
            if (date('mY', $giatri->createDate) === date('mY', time())):
                $doanhthuthangnay += $giatri->price;
            endif;
            $doanhthutrondoi += $giatri->price;
        endforeach;

        $chuaban = $DB->num_rows('account', [
            'sell' => 0,
            'ShopID' => Shop::my()->id,
        ])->all();

        return $view->make('/seller/index', [
            'daban' => $daban,
            'chuaban' => $chuaban,
            'tonggiatri' => $tonggiatri,
            'doanhthuhomnay' => $doanhthuhomnay,
            'doanhthuthangnay' => $doanhthuthangnay,
            'doanhthutrondoi' => $doanhthutrondoi,
            'gdganday' => $gdganday,
        ]);
    }

    public function rating(View $view)
    {
        $DB = DB::connect();
        $rating = $DB->find('rating')->all();
        return $view->make('/seller/rating', ['ShopID' => Shop::my()->id,'rating' => $rating]);
    }

    public function addAcc(View $view)
    {
        return $view->make('/seller/account/add');
    }

    public function withdraw(View $view)
    {
        $DB = DB::connect();
        $withdraw = $DB->find('withdraw',['ShopID' => Shop::my()->id])->all();
        return $view->make('/seller/withdraw',['withdraw'=>$withdraw]);
    }

    public function addPromo(View $view)
    {
        return $view->make('/seller/promo/create');
    }

    public function Promo(View $view)
    {
        return $view->make('/seller/promo/index');
    }

    public function settingUpdate(ServerRequest $request)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $content = isset($body->content) ? $body->content : null;
        $facebook = isset($body->facebook) ? $body->facebook : null;
        $youtube = isset($body->youtube) ? $body->youtube : null;
        $zalo = isset($body->zalo) ? $body->zalo : null;
        $hotline = isset($body->hotline) ? $body->hotline : null;

        $save->content = $content;
        $save->facebook = $facebook;
        $save->youtube = $youtube;
        $save->zalo = $zalo;
        $save->hotline = $hotline;

        if ($DB->update("shop", $save, ['id' => Shop::my()->id])->status()):

            $obj->status = "success";
            $obj->message = "Cập nhập thông tin thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Cập nhập thông tin thất bại";

            return new JsonResponse($obj, 401);

        endif;
    }

    public function EditPromo(View $view, $id)
    {

        if (empty(DB::connect()->num_rows('promo', ['id' => $id, 'ShopID' => Shop::my()->id])->get())):
            return new RedirectResponse('/seller/promo');
        endif;

        $promo = DB::connect()->find('promo', ['id' => $id, 'ShopID' => Shop::my()->id])->get();
        return $view->make('/seller/promo/edit', ['promo' => $promo]);
    }

    public function addAcc2(View $view, $game)
    {
        if (file_exists('views/seller/account/add/' . $game . '.phtml')):
            $view->make('seller/account/add/' . $game);
        else:
            return $view->make('404');
        endif;

    }

    public function editAcc(View $view, $id)
    {
        $DB = DB::connect();
        $Ck = $DB->num_rows('account', ['id' => $id, 'ShopID' => Shop::my()->id, 'sell' => 0])->get();

        if (!empty($Ck)):

            $Ck = $DB->find('account', ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (file_exists('views/seller/account/edit/' . $Ck->game . '.phtml')):
                $view->make('seller/account/edit/' . $Ck->game, [
                    'account' => $Ck,
                ]);
            else:
                return $view->make('404');
            endif;

        else:
            return new RedirectResponse('/seller/account');
        endif;
    }

    public function DeleteAccount(View $view, $idacc)
    {
        $DB = DB::connect();
        $Ck = $DB->num_rows('account', ['id' => $idacc, 'ShopID' => Shop::my()->id, 'sell' => 0])->get();

        if (!empty($Ck)):

            $Ck = $DB->find('account', ['id' => $idacc, 'ShopID' => Shop::my()->id])->get();
            $images = json_decode($Ck->images);

            foreach ($images as $key):
                if (file_exists('public' . $key)):
                    unlink('public' . $key);
                endif;
            endforeach;

            $Ck2 = $DB->num_rows('buy', ['MTK' => $Ck->MTK, 'ShopID' => Shop::my()->id])->get();

            if (!empty($Ck2)):
                $DB->delete('buy', ['MTK' => $Ck->MTK, 'ShopID' => Shop::my()->id])->status();
            endif;

            $DB->delete('account', ['id' => $idacc, 'ShopID' => Shop::my()->id])->status();

            return header('location: /seller/account');

        else:
            return new RedirectResponse('/seller/account');
        endif;
    }

    public function accountSeller(View $view)
    {
        return $view->make('/seller/account/index');
    }

    public function orders(View $view)
    {
        return $view->make('/seller/account/orders');
    }

    public function CreateRut(ServerRequest $request)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;

        $body = objectToArray($request->getParsedBody());

        $stk = isset($body->stk) ? $body->stk : null;
        $ctk = isset($body->ctk) ? $body->ctk : null;
        $bank = isset($body->bank) ? $body->bank : null;
        $quantity = isset($body->quantity) ? $body->quantity : null;

        $save->ShopID = Shop::my()->id;
        $save->stk = $stk;
        $save->ctk = $ctk;
        $save->bank = $bank;
        $save->quantity = $quantity;
        $save->thucnhan = $quantity - ($quantity / 100 * Setting::find('phirut'));
        $save->nhan = "all";
        $save->status = "pending";
        $save->createDate = time();

        $money = Auth::user()->money - $save->thucnhan;

        if (empty($stk)):

            $obj->status = "error";
            $obj->message = "Số tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($ctk)):

            $obj->status = "error";
            $obj->message = "Chủ tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($bank)):

            $obj->status = "error";
            $obj->message = "Ngân hàng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($quantity)):

            $obj->status = "error";
            $obj->message = "Số tiền cần rút không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($save->thucnhan < 100000):

            $obj->status = "error";
            $obj->message = "Số tiền cần rút phải <b>100.000đ</b> trở lên";

            return new JsonResponse($obj, 401);

        elseif (Auth::user()->money < $save->thucnhan):

            $obj->status = "error";
            $obj->message = "Tài khoản không đủ tiền";

            return new JsonResponse($obj, 401);

        elseif ($DB->save("withdraw", $save)->status() && $DB->save("users", ['money' => $money], ['uid' => Auth::user()->uid])->status()):

            $obj->status = "success";
            $obj->message = "Tạo yêu cầu rút tiền thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Tạo yêu cầu rút tiền thất bại, vui lòng thử lại sau ít phút!";

            return new JsonResponse($obj, 401);

        endif;

    }

    public function CreatePromo(ServerRequest $request)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $code = isset($body->code) ? $body->code : null;
        $count = isset($body->count) ? $body->count : null;
        $usemin = isset($body->usemin) ? $body->usemin : null;
        $type = isset($body->type) ? $body->type : null;
        $quantity = isset($body->quantity) ? str_replace("%", "", $body->quantity) : null;
        $game = isset($body->game) ? $body->game : null;

        $save->ShopID = Shop::my()->id;
        $save->code = $code;
        $save->count = $count;
        $save->usemin = $usemin;
        $save->type = $type;
        $save->quantity = $quantity;
        $save->game = $game;
        $save->createDate = time();

        $check = $DB->num_rows("promo", ['code' => $code, 'ShopID' => Shop::my()->id, 'game' => 'all'])->get();
        $check2 = $DB->num_rows("promo", ['code' => $code, 'ShopID' => Shop::my()->id, 'game' => $game])->get();

        if (empty($code)):

            $obj->status = "error";
            $obj->message = "Mã giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!empty($check) || !empty($check2)):

            $obj->status = "error";
            $obj->message = "Mã giảm giá đã tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($count)):

            $obj->status = "error";
            $obj->message = "Số lần sử dụng tối đa không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($usemin == null):

            $obj->status = "error";
            $obj->message = "Giá trị tối thiểu để sử dụng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($type)):

            $obj->status = "error";
            $obj->message = "Loại giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($quantity)):

            $obj->status = "error";
            $obj->message = "Giá trị giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($game)):

            $obj->status = "error";
            $obj->message = "Áp dụng giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($DB->save("promo", $save)->status()):

            $obj->status = "success";
            $obj->message = "Tạo mã khuyến mãi thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Tạo mã khuyến mãi thất bại, vui lòng thử lại sau ít phút!";

            return new JsonResponse($obj, 401);

        endif;
    }

    public function EditAccount(ServerRequest $request, $game, $idacc)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $expensions = array("jpeg", "jpg", "png");

        $error = array();
        $IMG = array();
        $IMG2 = array();
        $i = 0;
        $x = 0;
        $stt = false;
        $json = [];

        if (!empty($DB->num_rows("account", ['id' => $idacc, 'ShopID' => Shop::my()->id])->get())):
            foreach ((array) $_FILES["images"]["tmp_name"] as $key => $tmp_name):

                $file_name = $_FILES["images"]["name"][$key];

                if (in_array(pathinfo($file_name, PATHINFO_EXTENSION), array("jpeg", "jpg", "png", "gif"))):

                    $stt = true;
                    $rand = rtrim(time() . $file_name);
                    $path = "public/uploads/account/" . Shop::my()->id . "/" . $rand;
                    $IMG[$i++] = $path;
                    $IMG2[$x++] = "/uploads/account/" . Shop::my()->id . "/" . $rand;
                    move_uploaded_file($tmp_name, $path);

                else:
                    $stt = false;
                    array_push($error, "$file_name, ");
                endif;

            endforeach;

            $json = json_decode(Account::find(['id' => $idacc, 'ShopID' => Shop::my()->id])->images);

        else:
            $stt = false;
        endif;

        if (!empty(count($IMG2))):
            $body->images = json_encode($IMG2);
        endif;

        $game = Game::editAccount($game, $body, $idacc);

        if ($stt || $game->status()):

            if ($game->status()):

                foreach ($json as $key => $value):
                    if (file_exists($value)):
                        unlink($value);
                    endif;
                endforeach;

                $obj->status = "success";
                $obj->message = "Cập nhập tài khoản thành công";

                return new JsonResponse($obj, 200);

            else:

                foreach ($IMG as $key => $value):
                    if (file_exists($value)):
                        unlink($value);
                    endif;
                endforeach;

                $obj->status = "error";
                $obj->message = $game->message();

                return new JsonResponse($obj, 401);

            endif;

        else:

            foreach ($IMG as $key => $value):
                if (file_exists($value)):
                    unlink($value);
                endif;
            endforeach;

            $obj->status = "error";
            $obj->message = "Định dạng ảnh không hợp lệ";

            return new JsonResponse($obj, 401);

        endif;
    }

    public function AddAccount(ServerRequest $request, $game)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $expensions = array("jpeg", "jpg", "png");

        $error = array();
        $IMG = array();
        $IMG2 = array();
        $i = 0;
        $x = 0;
        $stt = false;

        foreach ((array) $_FILES["images"]["tmp_name"] as $key => $tmp_name):

            $file_name = $_FILES["images"]["name"][$key];

            if (in_array(pathinfo($file_name, PATHINFO_EXTENSION), array("jpeg", "jpg", "png", "gif"))):

                $stt = true;
                $rand = rtrim(time() . $file_name);
                $path = "public/uploads/account/" . Shop::my()->id . "/" . $rand;
                $IMG[$i++] = $path;
                $IMG2[$x++] = "/uploads/account/" . Shop::my()->id . "/" . $rand;

                move_uploaded_file($tmp_name, $path);

            else:
                $stt = false;
                array_push($error, "$file_name, ");
            endif;

        endforeach;

        $body->images = json_encode($IMG2);

        $game = Game::addAccount($game, $body);

        if ($stt):

            if ($game->status()):

                $obj->status = "success";
                $obj->message = "Thêm tài khoản thành công";

                return new JsonResponse($obj, 200);

            else:

                $obj->status = "error";
                $obj->message = $game->message();

                return new JsonResponse($obj, 401);

            endif;

        else:

            foreach ($IMG as $key => $value):
                if (file_exists($value)):
                    unlink($value);
                endif;
            endforeach;

            $obj->status = "error";
            $obj->message = "Định dạng ảnh không hợp lệ";

            return new JsonResponse($obj, 401);

        endif;
    }

    public function DeletePromo($id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;

        $check = $DB->num_rows("promo", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

        if (empty($check)):
            header('location: /seller/promo');
        elseif ($DB->delete("promo", ['id' => $id, 'ShopID' => Shop::my()->id])->status()):
            header('location: /seller/promo');
        else:
            header('location: /seller/promo');
        endif;

    }

    public function UpdatePromo(ServerRequest $request, $id)
    {

        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $code = isset($body->code) ? $body->code : null;
        $count = isset($body->count) ? $body->count : null;
        $usemin = isset($body->usemin) ? $body->usemin : null;
        $type = isset($body->type) ? $body->type : null;
        $quantity = isset($body->quantity) ? str_replace("%", "", $body->quantity) : null;
        $game = isset($body->game) ? $body->game : null;

        $ShopID = Shop::my()->id;
        $promo = Promo::find(['ShopID' => $ShopID, 'id' => $id])->get();

        $save->code = $code;
        $save->count = $count;
        $save->usemin = $usemin;
        $save->type = $type;
        $save->quantity = $quantity;
        $save->game = $game;

        $check = $DB->num_rows("promo", ['id' => $id, 'ShopID' => $ShopID])->get();
        $check2 = $DB->num_rows("promo", ['code' => $code, 'ShopID' => $ShopID, 'game' => 'all'])->get();
        $check3 = $DB->num_rows("promo", ['code' => $code, 'ShopID' => $ShopID, 'game' => $game])->get();

        if (empty($check2)):
            if ($code == $promo->code || $promo->game == 'all'):
                $op = true;
            else:
                $op = false;
            endif;
        elseif (empty($check2)):
            if ($code == $promo->code || $promo->game == $game):
                $op = true;
            else:
                $op = false;
            endif;
        else:
            $op = true;
        endif;

        if (empty($check)):

            $obj->status = "error";
            $obj->message = "Mã giảm giá không tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($code)):

            $obj->status = "error";
            $obj->message = "Mã giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (!$op):

            $obj->status = "error";
            $obj->message = "Mã giảm giá đã tồn tại";

            return new JsonResponse($obj, 401);

        elseif (empty($count)):

            $obj->status = "error";
            $obj->message = "Số lần sử dụng tối đa không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($usemin == null):

            $obj->status = "error";
            $obj->message = "Giá trị tối thiểu để sử dụng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($type)):

            $obj->status = "error";
            $obj->message = "Loại giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($quantity)):

            $obj->status = "error";
            $obj->message = "Giá trị giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($game)):

            $obj->status = "error";
            $obj->message = "Áp dụng giảm giá không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($DB->update("promo", $save, ['id' => $id])->status()):

            $obj->status = "success";
            $obj->message = "Cập nhập mã khuyến mãi thành công";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Cập nhập mã khuyến mãi thất bại, vui lòng thử lại sau ít phút!";

            return new JsonResponse($obj, 401);

        endif;
    }

}
