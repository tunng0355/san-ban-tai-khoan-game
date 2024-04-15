<?php
namespace App\Controllers;

use DB;
use Laminas\Diactoros\Response\JsonResponse;
use MiladRahimi\PhpRouter\View\View;
use stdClass;

class PAGES
{
    public function index(View $view)
    {
        $DB = DB::connect();
        $buy = $DB->find("buy")->all();
        $buy2 = $DB->num_rows("buy")->all();

        return $view->make('home/index',['buy'=>$buy,'buy2'=>$buy2]);
    }


    public function get(View $view)
    {
        return Setting::find();
    }
    
    public function test()
    {
        $obj = new stdClass;
        $obj->status = 'error';
        $obj->message = 'sdfsdfs!';

        return new JsonResponse($obj, 401);
    }

}
