<?php

class ZingSpeed
{
    public static $game = 'zing-speed-mobile';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game'=>static::$game,'status' => 'available','sell'=>0])->get();
    }

    public static function sex($data = 'male')
    {
        $type = [
            "male" =>"Nam",
            "female" =>"Nữ",
            "both" =>"Cả 2",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
}