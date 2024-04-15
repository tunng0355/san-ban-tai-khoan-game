<?php

class DotKich
{
    public static $game = 'dot-kich';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game'=>static::$game,'status' => 'available','sell'=>0])->get();
    }

    public static function chuyen($data = 'all')
    {
        $type = [
            "all" => "Tất cả",
            "zombie" => "Zombie",
            "bomb" => "Đặt bom C4",
            "single" => "Đấu đơn",
            "ai" => "Chế độ AI",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
}