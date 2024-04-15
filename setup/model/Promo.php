<?php

class Promo
{

    static public $table = "promo";
    static public $get = null;
    static public $all = null;

    public static function find($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table,$data);
    }

    
    public static function game($data = 'all')
    {
        $type = [
            "all" => "Tất cả tài khoản",
            "lien-quan" => "Chỉ tài khoản Liên Quân",
            "genshin-impact" => "Chỉ tài khoản Genshin Impact",
            "free-fire" => "Chỉ tài khoản Free Fire",
            "roblox" => "Chỉ tài khoản Roblox",
            "ngoc-rong-online" => "Chỉ tài khoản Ngọc Rồng",
            "fifa-online" => "Chỉ tài khoản Fifa Online",
            "dot-kich" => "Chỉ tài khoản Đột Kích",
            "pubg-mobile" => "Chỉ tài khoản PUBG Mobile",
            "lien-minh" => "Chỉ tài khoản Liên Minh",
            "toc-chien" => "Chỉ tài khoản Tốc Chiến",
            "zing-speed-mobile" => "Chỉ tài khoản Zing Speed",
            "play-together" => "Chỉ tài khoản Play Together",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Tất cả tài khoản";
        endif;
    }

}