<?php

class PlayTogether
{
    public static $game = 'play-together';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game'=>static::$game,'status' => 'available','sell'=>0])->get();
    }

    public static function fashion($data = 'empty')
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