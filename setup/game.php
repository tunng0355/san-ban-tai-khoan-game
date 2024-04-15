<?php

class Game
{
    public static $status = false;
    public static $message = false;

    public static function type($data = 'lien-quan')
    {
        $type = [
            "lien-quan" => "Liên Quân",
            "genshin-impact" => "Genshin Impact",
            "free-fire" => "Free Fire",
            "roblox" => "Roblox",
            "ngoc-rong-online" => "Ngọc Rồng",
            "fifa-online" => "Fifa Online",
            "dot-kich" => "Đột Kích",
            "pubg-mobile" => "PUBG Mobile",
            "lien-minh" => "Liên Minh",
            "toc-chien" => "Tốc Chiến",
            "zing-speed-mobile" => "Zing Speed",
            "play-together" => "Play Together",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Trò chơi không tồn tại";
        endif;
    }

    public static function code($game, $id)
    {
        $type = [
            "lien-quan" => "LQ",
            "genshin-impact" => "GI",
            "free-fire" => "FF",
            "roblox" => "RB",
            "ngoc-rong-online" => "NRO",
            "fifa-online" => "FIFA",
            "dot-kich" => "DK",
            "pubg-mobile" => "PB",
            "lien-minh" => "LOL",
            "toc-chien" => "TC",
            "zing-speed-mobile" => "ZS",
            "play-together" => "PT",
        ];

        if (isset($type["$game"])):
            return '<a href="/tai-khoan-' . $game . '/' . $id . '" target="_blank">#' . $type["$game"] . $id . '</a>';
        else:
            return '<a href="#" >Trò chơi không tồn tại</a>';
        endif;
    }

    public static function MTK($code)
    {
        $type = [
            "lien-quan" => "LQ",
            "genshin-impact" => "GI",
            "free-fire" => "FF",
            "roblox" => "RB",
            "ngoc-rong-online" => "NRO",
            "fifa-online" => "FIFA",
            "dot-kich" => "DK",
            "pubg-mobile" => "PB",
            "lien-minh" => "LOL",
            "toc-chien" => "TC",
            "zing-speed-mobile" => "ZS",
            "play-together" => "PT",
        ];

        if (isset($type["$code"])):
            return $type["$code"];
        else:
            return 'null';
        endif;
    }

    public static function TrangThai($data = 'available')
    {
        $type = [
            "available" => '<span class="badge bg-success">Hiển thị</span>',
            "hidden" => '<span class="badge bg-danger">Tạm ẩn</span>',
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return '<span class="badge bg-danger">Tạm ẩn</span>';
        endif;
    }

    public static function discount($data)
    {

        if (empty($data)):
            return '<i class="fa fa-times text-danger"></i>';
        else:
            return '<i class="fa fa-check text-primary"></i>';
        endif;
    }
        
    public static function loginHTML($data)
    {
        $type = [
            "facebook" => '<a href="https://www.facebook.com" target="_blank">https://www.facebook.com</a>',
            "garena" => '<a href="https://account.garena.com" target="_blank">https://account.garena.com</a>',
            "google" => '<a href="https://accounts.google.com" target="_blank">https://accounts.google.com</a>',
            "apple" => '<a href="https://appleid.apple.com" target="_blank">https://appleid.apple.com</a>'
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Đăng nhập không tồn tại";
        endif;
    }

    public static function info($data = 'empty')
    {
        $type = [
            "empty" => "Trắng thông tin",
            "full" => "Full thông tin",
            "unknown" => "Thông tin ảo",
            "only_email" => "Chỉ có email",
            "only_phone" => "Chỉ có số điện thoại",
        ];

        if (isset($type["$data"])):
            return $type["$data"];
        else:
            return "Trắng thông tin";
        endif;
    }

    public function message()
    {
        return static::$message;
    }

    public function status()
    {
        return static::$status;
    }

    public static function addAccount($game, $data)
    {
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $save->game = $game;
        $save->MTK = static::MTK($game);

        switch ($game):

    case 'lien-quan':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $tuong = isset($data->tuong) ? $data->tuong : null;
        $skin = isset($data->skin) ? $data->skin : null;
        $rank = isset($data->rank) ? $data->rank : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'tuong' => $tuong,
            'skin' => $skin,
            'rank' => $rank,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($tuong)):

            static::$status = false;
            static::$message = "Tướng không được bỏ trống";

        elseif (empty($skin)):

            static::$status = false;
            static::$message = "Trang phục không được bỏ trống";

        elseif (empty($rank)):

            static::$status = false;
            static::$message = "Hạng không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'dot-kich':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $chuyen = isset($data->chuyen) ? $data->chuyen : null;
        $VIPnumber = isset($data->VIPnumber) ? $data->VIPnumber : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'chuyen' => $chuyen,
            'VIPnumber' => $VIPnumber,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($chuyen)):

            static::$status = false;
            static::$message = "Chuyên không được bỏ trống";

        elseif (empty($VIPnumber)):

            static::$status = false;
            static::$message = "Số VIP không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'fifa-online':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $squad_value = isset($data->squad_value) ? $data->squad_value : null;
        $bpwhite = isset($data->bpwhite) ? $data->bpwhite : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'squad_value' => $squad_value,
            'bpwhite' => $bpwhite,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($squad_value)):

            static::$status = false;
            static::$message = "Giá trị đội hình không được bỏ trống";

        elseif (empty($bpwhite)):

            static::$status = false;
            static::$message = "BP Trắng không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'genshin-impact':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $server = isset($data->server) ? $data->server : null;
        $ar = isset($data->ar) ? $data->ar : null;
        $nv = isset($data->nv) ? $data->nv : null;
        $vk = isset($data->vk) ? $data->vk : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'server' => $server,
            'ar' => $ar,
            'nv' => $nv,
            'vk' => $vk,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($server)):

            static::$status = false;
            static::$message = "Server không được bỏ trống";

        elseif (empty($ar)):

            static::$status = false;
            static::$message = "AR không được bỏ trống";

        elseif (empty($nv)):

            static::$status = false;
            static::$message = "Nhân vật không được bỏ trống";

        elseif (empty($vk)):

            static::$status = false;
            static::$message = "Vũ khí không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'free-fire':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $skin = isset($data->skin) ? $data->skin : null;
        $thevocuc = isset($data->thevocuc) ? $data->thevocuc : null;
        $rank = isset($data->rank) ? $data->rank : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'skin' => $skin,
            'thevocuc' => $thevocuc,
            'rank' => $rank,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($skin)):

            static::$status = false;
            static::$message = "Skin súng không được bỏ trống";

        elseif (empty($thevocuc)):

            static::$status = false;
            static::$message = "Thẻ vô cực không được bỏ trống";

        elseif (empty($rank)):

            static::$status = false;
            static::$message = "Hạng không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'lien-minh':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $tuong = isset($data->tuong) ? $data->tuong : null;
        $skin = isset($data->skin) ? $data->skin : null;
        $rank = isset($data->rank) ? $data->rank : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'tuong' => $tuong,
            'skin' => $skin,
            'rank' => $rank,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($tuong)):

            static::$status = false;
            static::$message = "Tướng không được bỏ trống";

        elseif (empty($skin)):

            static::$status = false;
            static::$message = "Trang phục không được bỏ trống";

        elseif (empty($rank)):

            static::$status = false;
            static::$message = "Hạng không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'ngoc-rong-online':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $planet = isset($data->planet) ? $data->planet : null;
        $server = isset($data->server) ? $data->server : null;
        $earring = isset($data->earring) ? $data->earring : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'planet' => $planet,
            'server' => $server,
            'earring' => $earring,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($planet)):

            static::$status = false;
            static::$message = "Hành tinh không được bỏ trống";

        elseif (empty($server)):

            static::$status = false;
            static::$message = "Server không được bỏ trống";

        elseif ($earring == null):

            static::$status = false;
            static::$message = "Bông tai không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'play-together':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $fashion = isset($data->fashion) ? $data->fashion : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'fashion' => $fashion,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($fashion)):

            static::$status = false;
            static::$message = "Thời trang không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'pubg-mobile':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $fashion = isset($data->fashion) ? $data->fashion : null;
        $skin = isset($data->skin) ? $data->skin : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'fashion' => $fashion,
            'skin' => $skin,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($fashion)):

            static::$status = false;
            static::$message = "Trang phục không được bỏ trống";

        elseif (empty($skin)):

            static::$status = false;
            static::$message = "Skin súng không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'roblox':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $category = isset($data->category) ? $data->category : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'category' => $category,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($category)):

            static::$status = false;
            static::$message = "Thể loại không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'toc-chien':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $tuong = isset($data->tuong) ? $data->tuong : null;
        $skin = isset($data->skin) ? $data->skin : null;
        $rank = isset($data->rank) ? $data->rank : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'tuong' => $tuong,
            'skin' => $skin,
            'rank' => $rank,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($tuong)):

            static::$status = false;
            static::$message = "Tướng không được bỏ trống";

        elseif (empty($skin)):

            static::$status = false;
            static::$message = "Trang phục không được bỏ trống";

        elseif (empty($rank)):

            static::$status = false;
            static::$message = "Hạng không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    case 'zing-speed-mobile':

        $account = isset($data->account) ? $data->account : null;
        $password = isset($data->password) ? $data->password : null;
        $info = isset($data->info) ? $data->info : null;
        $login = isset($data->login) ? $data->login : null;
        $describe = isset($data->describe) ? $data->describe : null;
        $note = isset($data->note) ? $data->note : null;
        $price = isset($data->price) ? $data->price : null;
        $discount = isset($data->discount) ? $data->discount : null;
        $status = isset($data->status) ? $data->status : null;

        $sex = isset($data->sex) ? $data->sex : null;

        $save->ShopID = Shop::my()->id;
        $save->account = $account;
        $save->password = $password;
        $save->info = $info;
        $save->login = $login;
        $save->describe = $describe;
        $save->note = $note;
        $save->price = $price;
        if (!empty($data->images)): $save->images = $data->images;endif;
        $save->discount = $discount;
        $save->status = $status;
        $save->createDate = time();

        $save->data = json_encode([
            'sex' => $sex,
        ]);

        if (empty($account)):

            static::$status = false;
            static::$message = "Tài khoản không được bỏ trống";

        elseif (empty($password)):

            static::$status = false;
            static::$message = "Mật khẩu không được bỏ trống";

        elseif (empty($info)):

            static::$status = false;
            static::$message = "Thông tin không được bỏ trống";

        elseif (empty($login)):

            static::$status = false;
            static::$message = "Loại đăng nhập không được bỏ trống";

        elseif (empty($describe)):

            static::$status = false;
            static::$message = "Mô tả không được bỏ trống";

        elseif (empty($note)):

            static::$status = false;
            static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

        elseif ($price == null):

            static::$status = false;
            static::$message = "Giá tiền không được bỏ trống";

        elseif (empty($sex)):

            static::$status = false;
            static::$message = "Giới tính không được bỏ trống";

        elseif ($discount == null):

            static::$status = false;
            static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

        elseif (empty($status)):

            static::$status = false;
            static::$message = "Trạng thái không được bỏ trống";

        elseif ($DB->save("account", $save)->status()):

            static::$status = true;
            static::$message = "Thêm tài khoản thành công";
            $DB->update("account", ['MTK' => static::MTK($game) . DB::$getID], ['id' => DB::$getID])->status();

        else:

            static::$status = false;
            static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

        endif;

        break;

    default:

        static::$status = false;
        static::$message = "Trò chơi không tồn tại";

        break;

        endswitch;

        return new (static::class);

    }

    public static function editAccount($game, $data, $id)
    {
            $DB = DB::connect();
            $obj = new stdClass;
            $save = new stdClass;
            $save->game = $game;

            switch ($game):

        case 'lien-quan':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $tuong = isset($data->tuong) ? $data->tuong : null;
            $skin = isset($data->skin) ? $data->skin : null;
            $rank = isset($data->rank) ? $data->rank : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'tuong' => $tuong,
                'skin' => $skin,
                'rank' => $rank,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($tuong)):

                static::$status = false;
                static::$message = "Tướng không được bỏ trống";

            elseif (empty($skin)):

                static::$status = false;
                static::$message = "Trang phục không được bỏ trống";

            elseif (empty($rank)):

                static::$status = false;
                static::$message = "Hạng không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'dot-kich':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $chuyen = isset($data->chuyen) ? $data->chuyen : null;
            $VIPnumber = isset($data->VIPnumber) ? $data->VIPnumber : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'chuyen' => $chuyen,
                'VIPnumber' => $VIPnumber,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($chuyen)):

                static::$status = false;
                static::$message = "Chuyên không được bỏ trống";

            elseif (empty($VIPnumber)):

                static::$status = false;
                static::$message = "Số VIP không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'fifa-online':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $squad_value = isset($data->squad_value) ? $data->squad_value : null;
            $bpwhite = isset($data->bpwhite) ? $data->bpwhite : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'squad_value' => $squad_value,
                'bpwhite' => $bpwhite,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($squad_value)):

                static::$status = false;
                static::$message = "Giá trị đội hình không được bỏ trống";

            elseif (empty($bpwhite)):

                static::$status = false;
                static::$message = "BP Trắng không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'free-fire':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $server = isset($data->server) ? $data->server : null;
            $ar = isset($data->ar) ? $data->ar : null;
            $nv = isset($data->nv) ? $data->nv : null;
            $vk = isset($data->vk) ? $data->vk : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'server' => $server,
                'ar' => $ar,
                'nv' => $nv,
                'vk' => $vk,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($server)):

                static::$status = false;
                static::$message = "Server không được bỏ trống";

            elseif (empty($ar)):

                static::$status = false;
                static::$message = "AR không được bỏ trống";

            elseif (empty($nv)):

                static::$status = false;
                static::$message = "Nhân vật không được bỏ trống";

            elseif (empty($vk)):

                static::$status = false;
                static::$message = "Vũ khí không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'genshin-impact':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $skin = isset($data->skin) ? $data->skin : null;
            $thevocuc = isset($data->thevocuc) ? $data->thevocuc : null;
            $rank = isset($data->rank) ? $data->rank : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'skin' => $skin,
                'thevocuc' => $thevocuc,
                'rank' => $rank,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($skin)):

                static::$status = false;
                static::$message = "Skin súng không được bỏ trống";

            elseif (empty($thevocuc)):

                static::$status = false;
                static::$message = "Thẻ vô cực không được bỏ trống";

            elseif (empty($rank)):

                static::$status = false;
                static::$message = "Hạng không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'lien-minh':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $tuong = isset($data->tuong) ? $data->tuong : null;
            $skin = isset($data->skin) ? $data->skin : null;
            $rank = isset($data->rank) ? $data->rank : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'tuong' => $tuong,
                'skin' => $skin,
                'rank' => $rank,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($tuong)):

                static::$status = false;
                static::$message = "Tướng không được bỏ trống";

            elseif (empty($skin)):

                static::$status = false;
                static::$message = "Trang phục không được bỏ trống";

            elseif (empty($rank)):

                static::$status = false;
                static::$message = "Hạng không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'ngoc-rong-online':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $planet = isset($data->planet) ? $data->planet : null;
            $server = isset($data->server) ? $data->server : null;
            $earring = isset($data->earring) ? $data->earring : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'planet' => $planet,
                'server' => $server,
                'earring' => $earring,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($planet)):

                static::$status = false;
                static::$message = "Hành tinh không được bỏ trống";

            elseif (empty($server)):

                static::$status = false;
                static::$message = "Server không được bỏ trống";

            elseif ($earring == null):

                static::$status = false;
                static::$message = "Bông tai không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'play-together':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $fashion = isset($data->fashion) ? $data->fashion : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'fashion' => $fashion,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($fashion)):

                static::$status = false;
                static::$message = "Thời trang không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'pubg-mobile':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $fashion = isset($data->fashion) ? $data->fashion : null;
            $skin = isset($data->skin) ? $data->skin : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'fashion' => $fashion,
                'skin' => $skin,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($fashion)):

                static::$status = false;
                static::$message = "Trang phục không được bỏ trống";

            elseif (empty($skin)):

                static::$status = false;
                static::$message = "Skin súng không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'roblox':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $category = isset($data->category) ? $data->category : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'category' => $category,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($category)):

                static::$status = false;
                static::$message = "Thể loại không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'toc-chien':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $tuong = isset($data->tuong) ? $data->tuong : null;
            $skin = isset($data->skin) ? $data->skin : null;
            $rank = isset($data->rank) ? $data->rank : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'tuong' => $tuong,
                'skin' => $skin,
                'rank' => $rank,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($tuong)):

                static::$status = false;
                static::$message = "Tướng không được bỏ trống";

            elseif (empty($skin)):

                static::$status = false;
                static::$message = "Trang phục không được bỏ trống";

            elseif (empty($rank)):

                static::$status = false;
                static::$message = "Hạng không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        case 'zing-speed-mobile':

            $account = isset($data->account) ? $data->account : null;
            $password = isset($data->password) ? $data->password : null;
            $info = isset($data->info) ? $data->info : null;
            $login = isset($data->login) ? $data->login : null;
            $describe = isset($data->describe) ? $data->describe : null;
            $note = isset($data->note) ? $data->note : null;
            $price = isset($data->price) ? $data->price : null;
            $discount = isset($data->discount) ? $data->discount : null;
            $status = isset($data->status) ? $data->status : null;

            $sex = isset($data->sex) ? $data->sex : null;

            $save->account = $account;
            $save->password = $password;
            $save->info = $info;
            $save->login = $login;
            $save->describe = $describe;
            $save->note = $note;
            $save->price = $price;
            if (!empty($data->images)): $save->images = $data->images;endif;
            $save->discount = $discount;
            $save->status = $status;

            $save->data = json_encode([
                'sex' => $sex,
            ]);

            $check = $DB->num_rows("account", ['id' => $id, 'ShopID' => Shop::my()->id])->get();

            if (empty($check)):

                static::$status = false;
                static::$message = "Tài khoản không tồn tại";

            elseif (empty($account)):

                static::$status = false;
                static::$message = "Tài khoản không được bỏ trống";

            elseif (empty($password)):

                static::$status = false;
                static::$message = "Mật khẩu không được bỏ trống";

            elseif (empty($info)):

                static::$status = false;
                static::$message = "Thông tin không được bỏ trống";

            elseif (empty($login)):

                static::$status = false;
                static::$message = "Loại đăng nhập không được bỏ trống";

            elseif (empty($describe)):

                static::$status = false;
                static::$message = "Mô tả không được bỏ trống";

            elseif (empty($note)):

                static::$status = false;
                static::$message = "Thông tin thêm sau khi bán không được bỏ trống";

            elseif ($price == null):

                static::$status = false;
                static::$message = "Giá tiền không được bỏ trống";

            elseif (empty($sex)):

                static::$status = false;
                static::$message = "Giới tính không được bỏ trống";

            elseif ($discount == null):

                static::$status = false;
                static::$message = "Áp dụng giảm giá tại shop không được bỏ trống";

            elseif (empty($status)):

                static::$status = false;
                static::$message = "Trạng thái không được bỏ trống";

            elseif ($DB->update("account", $save, ['id' => $id, 'ShopID' => Shop::my()->id])->status()):

                static::$status = true;
                static::$message = "Thêm tài khoản thành công";

            else:

                static::$status = false;
                static::$message = "Thêm tài khoản thất bại, vui lòng thử lại sau ít phút!";

            endif;

            break;

        default:

            static::$status = false;
            static::$message = "Trò chơi không tồn tại";

            break;

            endswitch;

            return new (static::class);

    }
}
