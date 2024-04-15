<?php
namespace App\Controllers;

use Auth;
use DB;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use stdClass;

class Community
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function index(View $view)
    {
        $DB = DB::connect();
        $post = $DB->find("post", ['status' => 'active'], ['id ASC'])->all();
        return $view->make('community/index', ['post' => $post]);
    }


    public function post(View $view,$id)
    {
        $DB = DB::connect();

        if (empty(DB::connect()->num_rows('post', ['id'=>$id,'status' => 'active'])->get())):
            return new RedirectResponse('/cong-dong');
        endif;

        $post = $DB->find("post", ['id'=>$id,'status' => 'active'])->get();

        if (empty(DB::connect()->num_rows('users', ['uid'=>$post->UserID])->get())):
            return new RedirectResponse('/cong-dong');
        endif;

        $users = $DB->find("users", ['uid'=>$post->UserID])->get();

        return $view->make('community/post', ['post' => $post,'users'=>$users]);
    }
    public function createpost(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $content = isset($body->content) ? str($body->content) : null;
        $save->UserID = Auth::user()->uid;
        $save->content = $content;
        $save->cmtType = "on";
        $save->likeType = "on";
        $save->status = "active";
        $save->createDate = time();

        if (empty($content)):

            $obj->status = "error";
            $obj->message = "Nội dung không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif ($DB->save("post", $save)->status()):

            $obj->status = "success";
            $obj->message = "Đăng bài thành công";
            $obj->post = [
                "id"=>DB::$getID,
                "avatar" => Auth::user()->avatar,
                "username" => Auth::user()->username,
                "fullname" => Auth::user()->fullname,
                "content" => $save->content,
                "cmtType" => $save->cmtType,
                "likeType" => $save->likeType,
                "status" => $save->status,
                "tick"=> (Auth::user()->level == 4) ? '<span><i class="fad fa-shield text-primary"></i></span>' : '',
                "createDate" => timeago($save->createDate),
            ];

            return new JsonResponse($obj, 200);

        else:

            $obj->status = "error";
            $obj->message = "Đăng bài thất bại, vui lòng thử lại sau ít phút!";

            return new JsonResponse($obj, 401);

        endif;

    }

}
