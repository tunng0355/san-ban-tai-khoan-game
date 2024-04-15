<?php

class Post
{

    static public $table = "post";

    public static function all()
    {
        $DB = DB::connect();
        return $DB->find(static::$table)->all();
    }

    public static function find($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table)->get()->$data;
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