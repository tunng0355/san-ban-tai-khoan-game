<?php

class Card
{

    static public $table = "card";

    public static function all()
    {
        $DB = DB::connect();
        return $DB->find(static::$table)->all();
    }

    public static function find($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table,$data);
    }

    
    public static function status($type)
    {
        if($type == 1 ):
            return "<span class='badge bg-primary'>Thành công</span>";
        elseif($type == 2 ):
            return "<span class='badge bg-warning'>Thẻ sai mệnh giá</span>";
        elseif($type == 99):
            return "<span class='badge bg-warning'>Chờ duyệt</span>";
        else:
            return "<span class='badge bg-danger'>Thất bại</span>";
        endif;
    }
}