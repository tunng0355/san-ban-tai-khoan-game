<?php
namespace App\Controllers;

use Auth;
use DB;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use Pusher\Pusher;
use stdClass;

class Shop
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function CreateShop(View $view)
    {
        return $view->make('shop/create');
    }

    public function index(View $view)
    {
        return $view->make('shop/index');
    }

    public function shopPro(View $view, $username)
    {
        $DB = DB::connect();

        $users = $DB->num_rows("users", ['username' => $username])->get();
        if (!empty($users)):
            $users = $DB->find("users", ['username' => $username])->get();
            $shop = $DB->num_rows("shop", ['UserID' => $users->uid, 'status' => 'active'])->get();
            if (!empty($shop)):
                $shop = $DB->find("shop", ['UserID' => $users->uid, 'status' => 'active'])->get();
                $rating = $DB->num_rows("rating", ['ShopID' => $shop->id])->get();
                $buy = $DB->find("buy",['ShopID' => $shop->id])->all();

                $account = $DB->setsql("SELECT DISTINCT game FROM `account` WHERE `ShopID` = '$shop->id' ")->run();
                $account2 = $DB->find("account",['ShopID' => $shop->id])->all();
                $return = [];

                while ($row = mysqli_fetch_assoc($account)) {
                    $return[] = $row;
                }
        
                mysqli_free_result($account);
        
                $return =  objectToArray($return);


                return $view->make('shop/profile', [
                    'users' => $users,
                    'shop' => $shop,
                    'buy'=>$buy,
                    'account'=>$return
                ]);

            else:
                return $view->make('404');
            endif;
        else:
            return $view->make('404');
        endif;
    }

    public function community(View $view, $username)
    {
        $DB = DB::connect();

        $users = $DB->num_rows("users", ['username' => $username])->get();
        if (!empty($users)):
            $users = $DB->find("users", ['username' => $username])->get();
            $shop = $DB->num_rows("shop", ['UserID' => $users->uid, 'status' => 'active'])->get();
            if (!empty($shop)):
                $shop = $DB->find("shop", ['UserID' => $users->uid, 'status' => 'active'])->get();
                $post = $DB->find("post",['UserID' => $users->uid])->all();
                $buy = $DB->find("buy",['ShopID' => $shop->id])->all();


                return $view->make('shop/community', [
                    'users' => $users,
                    'shop' => $shop,
                    'post'=>$post,
                    'buy'=>$buy
                ]);

            else:
                return $view->make('404');
            endif;
        else:
            return $view->make('404');
        endif;
    }
    
    public function reviews(View $view, $username)
    {
        $DB = DB::connect();

        $users = $DB->num_rows("users", ['username' => $username])->get();
        if (!empty($users)):
            $users = $DB->find("users", ['username' => $username])->get();
            $shop = $DB->num_rows("shop", ['UserID' => $users->uid, 'status' => 'active'])->get();
            if (!empty($shop)):
                $shop = $DB->find("shop", ['UserID' => $users->uid, 'status' => 'active'])->get();
                $rating = $DB->num_rows("rating", ['ShopID' => $shop->id])->get();
                $buy = $DB->find("buy",['ShopID' => $shop->id])->all();

                $star = 'all';
                $rating2 = $DB->find("rating", ['ShopID' => $shop->id])->all();

                if(isset($_GET['rating'])):
                    if(!empty($_GET['rating'])):
                        $star = $_GET['rating'];
                        $rating2 = $DB->find("rating", ['ShopID' => $shop->id,'star'=>$star])->all();
                    endif;
                endif;

                return $view->make('shop/reviews', [
                    'users' => $users,
                    'shop' => $shop,
                    'buy'=>$buy,
                    'rating2'=>$rating2,
                    'star'=>$star
                ]);

            else:
                return $view->make('404');
            endif;
        else:
            return $view->make('404');
        endif;
    }

    public function CreateShopSend(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $facebook = isset($body->facebook) ? $body->facebook : null;
        $zalo = isset($body->zalo) ? $body->zalo : null;
        $hotline = isset($body->hotline) ? $body->hotline : null;


        $save->UserID = Auth::user()->uid;
        $save->facebook = $facebook;
        $save->zalo = $zalo;
        $save->hotline = $hotline;
        $save->status = "pending";
        $save->createDate = time();

        $check = $DB->num_rows("shop", ['UserID' => Auth::user()->uid])->get();

        if (!empty($check)):
            $check2 = $DB->find("shop", ['UserID' => Auth::user()->uid])->get();
            if ($check2->status == "active"):
                $shop = "ok";
            else:
                $shop = "not";
            endif;
        else:
            $shop = false;
        endif;

        if ($shop):

            $obj->status = "error";
            $obj->message = ($shop == "ok") ? "Bạn đã có shop rồi." : "Shop của bạn đang chờ duyệt";

            return new JsonResponse($obj, 401);

        elseif (empty($facebook)):

            $obj->status = "error";
            $obj->message = "Facebook không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($zalo)):

            $obj->status = "error";
            $obj->message = "Zalo không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($hotline)):

            $obj->status = "error";
            $obj->message = "Hotline không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($DB->save("shop", $save)->status()):

            $obj->status = "success";
            $obj->message = "Yêu cầu của bạn đang đợi duyệt, vui lòng chờ";

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Gửi yêu cầu thất bại, vui lòng thử lại sau ít phút!";

            return new JsonResponse($obj, 401);

        endif;

    }

}
