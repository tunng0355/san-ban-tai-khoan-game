<?php

class Buy
{

    static public $table = "buy";

    public static function all($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table,$data)->all();
    }

    public static function find($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table,$data)->get();
    }

}
