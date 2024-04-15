<?php

class GenshinImpact
{
    public static $game = 'genshin-impact';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game'=>static::$game])->get();
    }

    public static function server($data = 'asia')
    {
        $type = [
            "asia"=>"Asia",
            "america"=>"America",
            "europe"=>"Europe",
            "tw-hk-mo"=>"TW/HK/MO (SAR)",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
}