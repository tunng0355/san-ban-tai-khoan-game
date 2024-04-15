<?php

class Setting
{

    static public $table = "setting";

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
}