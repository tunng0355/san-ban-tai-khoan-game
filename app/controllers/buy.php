<?php
namespace App\Controllers;

use Auth;
use DB;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use stdClass;

class Buy
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function acc(ServerRequest $request, $game, $idacc)
    {
        $this->header();
        $DB = DB::connect();
        $save = new stdClass;
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $promo_code = isset($body->promo_code) ? $body->promo_code : null;

        $game = str_replace("tai-khoan-", "", $game);
        $save->game = $game;

        $account = $DB->num_rows('account', ['id' => $idacc, 'game' => $game, 'status' => 'available', 'sell' => 0])->get();

        if (empty($idacc)):

            $obj->status = "error";
            $obj->message = "Tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($account)):

            $obj->status = "error";
            $obj->message = "Tài khoản không tồn tại";

            return new JsonResponse($obj, 401);

        else:

            $account = $DB->find('account', ['id' => $idacc, 'game' => $game, 'status' => 'available', 'sell' => 0])->get();

            $promo = $DB->num_rows('promo', ['game' => $account->game, 'code' => $promo_code])->get();
            $promo2 = $DB->num_rows('promo', ['game' => 'all', 'code' => $promo_code])->get();

            if (!empty($promo)):
                $promoCK = true;
                $game2 = $account->game;
            elseif (!empty($promo2)):
                $game2 = 'all';
                $promoCK = true;
            else:
                $promoCK = false;
            endif;

            $save->rating = 0;
            $save->account = $account->account;
            $save->password = $account->password;
            $save->login = $account->login;
            $save->MTK = $account->MTK;
            $save->info = $account->info;
            $save->note = $account->note;
            $save->ShopID = $account->ShopID;
            $save->UserID = Auth::user()->uid;
            $save->createDate = time();
            $save->promo_code = $promo_code;

            if (!$promoCK):

                $save->price = $account->price;
                $money = Auth::user()->money - $save->price;

                if ($save->price > Auth::user()->money):

                    $obj->status = "error";
                    $obj->message = "Bạn không đủ <b>" . format_number($save->price) . "</b> để thanh toán, vui lòng nạp thêm.";

                    return new JsonResponse($obj, 401);

                elseif ($DB->update('account', ['sell' => 1], ['id' => $idacc, 'game' => $game, 'status' => 'available', 'sell' => 0])->status() && $DB->update('users', ['money' => $money], ['uid' => Auth::user()->uid])->status() && $DB->save('buy', $save)->status()):

                    $obj->status = "success";
                    $obj->message = "Mua tài khoản thành công";
                    $obj->href = "/orders";
                    return new JsonResponse($obj, 200);

                else:

                    $obj->status = "error";
                    $obj->message = "Mua tài khoản thất bại";

                    return new JsonResponse($obj, 401);

                endif;

            else:

                $promo = $DB->find('promo', ['game' => $game2, 'code' => $promo_code])->get();
                $used = $promo->used + 1;
                
                if ($promo->usemin > $account->price):

                    $obj->status = "error";
                    $obj->message = "Giá trị tối thiểu để sử dụng phải từ <b>".format_number($promo->usemin)."</b> trỏ lên ";
                    return new JsonResponse($obj, 401);

                elseif ($promo->used == $promo->count):

                        $obj->status = "error";
                        $obj->message = "Mã khuyến mãi đã giới hạn";
                        return new JsonResponse($obj, 401);
    
                elseif (empty($account->discount)):

                    $obj->status = "error";
                    $obj->message = "Tài khoản này không thể áp mã khuyến mãi";
                    return new JsonResponse($obj, 401);

                else:

                    if ($promo->type == 'percent'):
                        $price = $account->price - ($account->price / 100 * $promo->quantity);
                    else:
                        $price = $account->price - $promo->quantity;
                    endif;

                    $save->price = $price;
                    
                    $money = Auth::user()->money - $save->price;

                    if ($save->price > Auth::user()->money):

                        $obj->status = "error";
                        $obj->message = "Bạn không đủ <b>" . format_number($save->price) . "</b> để thanh toán, vui lòng nạp thêm.";

                        return new JsonResponse($obj, 401);

                    elseif ($DB->update('account', ['sell' => 1], ['id' => $idacc])->status() && $DB->update('users', ['money' => $money], ['uid' => Auth::user()->uid])->status() && $DB->save('buy', $save)->status() && $DB->update('promo',['used'=>$promo->used +1] ,['game' => $game2, 'code' => $promo_code])->status()):

                        $obj->status = "success";
                        $obj->message = "Mua tài khoản thành công";
                        $obj->href = "/orders";

                        return new JsonResponse($obj, 200);

                    else:

                        $obj->status = "error";
                        $obj->message = "Mua tài khoản thất bại";

                        return new JsonResponse($obj, 401);

                    endif;

                endif;

            endif;

        endif;

    }

    public function promo(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $id = isset($body->id) ? $body->id : null;
        $promo_code = isset($body->promo_code) ? $body->promo_code : null;

        $account = $DB->num_rows('account', ['id' => $id])->get();

        if (empty($id)):

            $obj->status = "error";
            $obj->message = "Tài khoản không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($promo_code)):

            $obj->status = "error";
            $obj->message = "Mã khuyến mãi không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($account)):

            $obj->status = "error";
            $obj->message = "Tài khoản không tồn tại";

            return new JsonResponse($obj, 401);

        else:

            $account = $DB->find('account', ['id' => $id])->get();
            $promo = $DB->num_rows('promo', ['game' => $account->game, 'code' => $promo_code])->get();
            $promo2 = $DB->num_rows('promo', ['game' => 'all', 'code' => $promo_code])->get();

            if (!empty($promo)):
                $promoCK = true;
                $game = $account->game;
            elseif (!empty($promo2)):
                $game = 'all';
                $promoCK = true;
            else:
                $promoCK = false;
            endif;

            if (!$promoCK):

                $obj->status = "error";
                $obj->message = "Mã khuyến mãi không tồn tại";
                return new JsonResponse($obj, 401);

            else:

                $promo = $DB->find('promo', ['game' => $game, 'code' => $promo_code])->get();
                $used = $promo->used + 1;

                if ($promo->used == $promo->count):

                    $obj->status = "error";
                    $obj->message = "Mã khuyến mãi đã giới hạn";
                    return new JsonResponse($obj, 401);

                elseif (empty($account->discount)):

                    $obj->status = "error";
                    $obj->message = "Tài khoản này không thể áp mã khuyến mãi";
                    return new JsonResponse($obj, 401);

                else:

                    $price = $account->price;

                    if ($promo->type == 'percent'):
                        $price = $price - ($price / 100 * $promo->quantity);
                    else:
                        $price = $price - $promo->quantity;
                    endif;

                    $obj->status = "success";
                    $obj->message = "Áp dụng mã khuyến mãi thành công";
                    $obj->price = format_number($price);
                    $obj->promo_code = $promo_code;

                    return new JsonResponse($obj, 200);

                endif;

            endif;

        endif;

    }

    public function account(View $view, $game, $id)
    {
        $DB = DB::connect();

        $game = str_replace("tai-khoan-", "", $game);

        $ck = $DB->num_rows('account', ['game' => $game, 'id' => $id, 'status' => 'available','sell'=>0])->get();

        if (!empty($ck)):
            $account = $DB->find('account', ['game' => $game, 'id' => $id, 'status' => 'available'])->get();
            $shop = $DB->find('shop', ['id' => $account->ShopID])->get();
            $User = $DB->find('users', ['uid' => $shop->UserID])->get();

            return $view->make('buy/account', [
                'acc' => $account,
                'shop' => $shop,
                'User' => $User,
            ]);
        endif;

        return $view->make('404');

    }

    public function game(View $view, $game)
    {
        $game = str_replace("tai-khoan-", "", $game);

        if (file_exists('views/buy/game/' . $game . '.phtml')):
            $view->make('buy/game/' . $game);
        else:
            return $view->make('404');
        endif;

    }
}
