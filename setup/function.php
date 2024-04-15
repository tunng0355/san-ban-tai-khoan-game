<?php

function format_number($number)
{
    return str_replace(",", ",", number_format($number));
}

function format_number2($number)
{
    return str_replace(",", ".", number_format($number));
}

function objectToArray($d)
{
    return json_decode(json_encode($d));
}

function tagurl($text)
{
    $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
    $string = preg_replace($url, '<strong>$0</strong>', $text);
    return $string;
}

function str($data)
{
    return str_replace(array('<', "'", '>', '?', '/', "\\", '--', 'eval(', '<php'), array('', '', '', '', '', '', '', '', ''), htmlspecialchars(addslashes(strip_tags($data))));
}
function base_url($data = '')
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }

    $url .= $_SERVER['HTTP_HOST'];
    echo $url.$data;
}
function timeago($timestamp)
{

    $strTime = ["s", "phút", "giờ", "ngày", "tháng", "ALL"];
    $length = ["60", "60", "24", "30", "12", "10"];

    $currentTime = time();
    if ($currentTime >= $timestamp):
        $diff = time() - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff = $diff / $length[$i];
        }

        $diff = round($diff);
        if ($strTime[$i] == "ALL"):
            return date('d-m-Y H:i:s', strtotime($date));
        elseif ($strTime[$i] == "s"):
            return "vài giây trước";
        else:
            return $diff . " " . $strTime[$i] . " trước";
        endif;
    endif;
}

function getIP()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } elseif (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

function StatusNhan($status)
{
    if ($status == 'all'):
        return 'Tất cả';
    else:
        return $status;
    endif;
}

function StatusRut($status)
{
    if ($status == 'pending'):
        return '<span class="badge bg-warning">Chờ duyệt</span>';
    elseif ($status == 'done'):
        return '<span class="badge bg-success">Thành công</span>';
    else:
        return '<span class="badge bg-danger">Từ chối</span>';
    endif;
}


function StatusOrder($status)
{
    if ($status == 'pending'):
        return '<span class="badge bg-warning">Đang chạy...</span>';
    elseif ($status == 'done'):
        return '<span class="badge bg-success">Thành công</span>';
    else:
        return '<span class="badge bg-danger">Thất bại</span>';
    endif;
}
