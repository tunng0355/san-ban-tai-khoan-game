<?php

class NgocRong
{
    public static $game = 'ngoc-rong-online';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game'=>static::$game,'status' => 'available','sell'=>0])->get();
    }

    public static function planet($data = 'earth')
    {
        $type = [
            "earth" =>"Trái đất",
            "xayda" =>"Xayda",
            "namec" =>"Namec",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
    public static function server($data = 1)
    {
        $type = [
            "1" =>"Server 1",
            "2" =>"Server 2",
            "3" =>"Server 3",
            "4" =>"Server 4",
            "5" =>"Server 5",
            "6" =>"Server 6",
            "7" =>"Server 7",
            "8" =>"Server 8",
            "9" =>"Server 9",
            "10" =>"Server 10",
            "11" =>"Server 11",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
    public static function earring($data = 0)
    {
        $type = [
            "0" =>"Không",
            "1" =>"Có",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
}