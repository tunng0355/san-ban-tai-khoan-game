<?php

class Bank
{

    public static $table = "bank";
    public static $get = null;
    public static $all = null;

    public static function find($data = [])
    {
        $DB = DB::connect();
        return $DB->find(static::$table, $data);
    }

    public static function status($type)
    {
        if ($type == 'active'):
            return "<span class='badge bg-primary'>Hoạt động</span>";
        else:
            return "<span class='badge bg-warning'>Bảo trì</span>";
        endif;
    }

    public static function type($data = 'momo')
    {
        $type = [
            "shopeepay" => "Shopee Pay",
            "momo" => "Momo",
            "zalopay" => "ZaloPay",
            "vietcombank" => "Vietcombank",
            "mb" => "MBBank",
            "acb" => "ACBâu",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Ngân hàng không tồn tại";
        endif;
    }

}
