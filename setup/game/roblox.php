<?php

class Roblox
{
    public static $game = 'roblox';

    public static function total()
    {
        $DB = DB::connect();
        return $DB->num_rows('account', ['game' => static::$game, 'status' => 'available', 'sell' => 0])->get();
    }

    public static function Category($data = 'blox_fruits')
    {
        $type = [
            "blox_fruits" => "Blox Fruits",
            "king_legacy" => "King Legacy",
            "pet_simulator_x" => "Pet Simulator X",
            "anime_adventures" => "Anime Adventures",
            "shindo_life" => "Shindo Life",
            "all_star_tower_defense" => "All Star Tower Defense",
            "grand_piece_online" => "Grand Piece Online",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Không xác định";
        endif;

    }
}
