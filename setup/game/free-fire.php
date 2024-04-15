<?php

class FreeFire
{
    public static $game = 'free-fire';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game'=>static::$game,'status' => 'available','sell'=>0])->get();
    }

    public static function rank($data = 'empty')
    {
        $type = [
            "empty" => "Chưa có",
            "bronze" => "Đồng",
            "silver" => "Bạc",
            "gold" => "Vàng",
            "platinum" => "Bạch kim",
            "diamond" => "Kim cương",
            "heroic" => "Huyền thoại",
            "grandmaster" => "Thách đấu",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
}