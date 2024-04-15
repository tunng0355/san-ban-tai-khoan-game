<?php
namespace App\Controllers;

use Auth;
use DB;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use stdClass;

class Recharge
{

    public function header()
    {
        header("content-type: application/text; charset=UTF-8");
    }

    public function recharge(View $view)
    {
        $DB = DB::connect();
        return $view->make('recharge/index');
    }

    public function cardRecharge(View $view)
    {
        $DB = DB::connect();
        return $view->make('recharge/card');
    }

    public function Callback()
    {

        $body = file_get_contents('php://input');
        $content = json_decode($body);

        if (isset($content->callback_sign)):
            $myfile = fopen("log.txt", "w") or die("Unable to open file!");
            $txt = $body . "\n";
            fwrite($myfile, $txt);
            fclose($myfile);

            //// Kết quả trả về sẽ có các trường như sau:
            $partner_key = $_ENV['PARTNER_KEY'];

            $callback_sign = md5($partner_key . $content->code . $content->serial);

            if ($content->callback_sign == $callback_sign):

                $status = $content->status;
                $note = $content->message;
                $request_id = $content->request_id; /// Mã giao dịch của bạn
                $trans_id = $content->trans_id; /// Mã giao dịch của website thesieure.com
                $declared_value = $content->declared_value; /// Mệnh giá mà bạn khai báo lên
                $value = $content->value; /// Mệnh giá thực tế của thẻ
                $amount = $content->amount; /// Số tiền bạn nhận về (VND)
                $code = $content->code; /// Mã nạp
                $serial = $content->serial; /// Serial thẻ
                $telco = $content->telco; /// Nhà mạng

                if (isset($trans_id)):
                    $ck = $DB->num_rows("card", ["tranid" => $request_id])->get();
                    if (empty($ck)):
                        if ($status != 4):

                            $ck = $DB->find("card", ["tranid" => $request_id])->get();
                            $ck2 = $DB->find("users", ["uid" => $ck->UserID])->get();

                            if ($status == 1):
                                $money = $ck2->money + $amount;
                                $DB->update("users", ['money' => $money])->status();
                                $DB->update("card", ['status'=>$status,'note'=>$note],["tranid" => $request_id])->get();
                            endif;

                            if ($status == 2):
                                $money = $ck2->money + $amount;
                                $DB->update("users", ['money' => $money])->status();
                                $DB->update("card", ['status'=>$status,'note'=>$note],["tranid" => $request_id])->get();
                            endif;

                            if ($status == 3):
                                $money = $ck2->money;
                                $DB->update("users", ['money' => $money])->status();
                                $DB->update("card", ['status'=>$status,'note'=>$note],["tranid" => $request_id])->get();
                            endif;

                        endif;
                    endif;
                endif;
            endif;
        endif;

    }
    public function AddCard(ServerRequest $request)
    {
        $this->header();
        $DB = DB::connect();
        $obj = new stdClass;
        $save = new stdClass;
        $body = objectToArray($request->getParsedBody());

        $tran_id = rand(10000000, 140000000);
        $telco = isset($body->telco) ? ($body->telco) : null;
        $pin = isset($body->pin) ? ($body->pin) : null;
        $serial = isset($body->serial) ? ($body->serial) : null;
        $amount = isset($body->amount) ? ($body->amount) : null;

        if (empty($pin)):

            $obj->status = "error";
            $obj->message = 'Mã thẻ không được bỏ trống';

            return new JsonResponse($obj, 401);

        elseif (empty($serial)):

            $obj->status = "error";
            $obj->message = "Serial thẻ không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($telco)):

            $obj->status = "error";
            $obj->message = "Nhà mạng không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (empty($amount)):

            $obj->status = "error";
            $obj->message = "Mệnh giá thẻ không được bỏ trống";

            return new JsonResponse($obj, 401);

        elseif (strlen($amount) < 5):

            $obj->status = "error";
            $obj->message = "Mệnh giá thẻ quá nhỏ";

            return new JsonResponse($obj, 401);

        elseif (strlen($amount) > 7):

            $obj->status = "error";
            $obj->message = "Mệnh giá thẻ quá lớn";

            return new JsonResponse($obj, 401);

        elseif (!empty($DB->num_rows("card", ['serial' => $serial])->get())):

            $obj->status = "error";
            $obj->message = "Thẻ đã tồn tại trong hệ thống";

            return new JsonResponse($obj, 401);

        else:

            $data = [
                'telco' => $telco,
                'code' => $pin,
                'serial' => $serial,
                'amount' => $amount,
                'request_id' => $tran_id,
                'partner_id' => $_ENV['PARTNER_ID'],
                'sign' => md5($_ENV['PARTNER_KEY'] . $pin . $serial),
                'command' => "charging",
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://thesieure.com/chargingws/v2");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $result = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($result);

            $save->UserID = Auth::user()->uid;
            $save->tranid = $serial;
            $save->pin = $pin;
            $save->serial = $serial;
            $save->telco = $telco;
            $save->amount = $amount;
            $save->note = $response->message;
            $save->status = $response->status;
            $save->createDate = time();
            //185821001343088
            if ($response->status == 99):

                if ($DB->save('card', $save)->status()):

                    $obj->status = "success";
                    $obj->message = 'Gửi thẻ thành công';

                    return new JsonResponse($obj, 200);

                else:

                    $obj->status = "error";
                    $obj->message = 'Gửi thẻ thất bại vui lòng liên hệ admin';

                    return new JsonResponse($obj, 401);

                endif;

            else:

                $obj->status = "error";
                $obj->message = $response->message;

                return new JsonResponse($obj, 401);

            endif;

        endif;

    }

}
