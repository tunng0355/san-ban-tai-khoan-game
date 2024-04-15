<?php

class Shop
{

    static public $table = "shop";
    static public $get = null;
    static public $all = null;

    public static function find($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table,$data);
    }

    public static function my()
    {
        return static::find(['UserID' => Auth::user()->uid])->get();
    }


    public static function status($type)
    {
        if($type == 'active'):
            return "<span class='badge bg-primary'>Hoạt Động</span>";
        elseif($type == 'pending'):
            return "<span class='badge bg-warning'>Chờ duyệt</span>";
        else:
            return "<span class='badge bg-danger'>Từ chối</span>";
        endif;
    }
}