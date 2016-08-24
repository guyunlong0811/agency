<?php
//解析数据
function get_error()
{
    $error = C('G_ERROR') ? C('G_ERROR') : 'unknown';
    if ($error !== true) {
        $errorInfo = L($error);//返回错误信息
    } else {
        $errorInfo = '';
    }
    return $errorInfo;
}

//读取多层配置
function get_config($first, $key = false)
{

    //转大写
    $first = strtoupper($first);

    //读一层
    if (!$key)
        return C($first);

    //读两层
    if (!is_array($key))
        return C($first . '.' . $key);

    if (count($key) == 1)
        return C($first . '.' . $key[0]);

    //读多层
    $config = C($first);
    foreach ($key as $value)
        $config = $config[$value];

    return $config;

}

function curl_link($host, $method = 'get', $data = '', $return = true, $agent = 'WEBSERVER')
{//curl链接

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $host);

    if ($method == 'post')
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $retData = curl_exec($ch);
    curl_close($ch);
    return $retData;

}

function get_ip()
{//获取IP

    if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if ($_SERVER['HTTP_CLIENT_IP']) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if ($_SERVER['REMOTE_ADDR']) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } else if (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = 'Unknown';
    }
    return $ip;

}

function check_ip($ip_limit)
{

    //检测IP限制
    $ip = get_ip();
    if ($ip_limit == '') {
        $arr_ip_limit[] = $ip;
    } else {
        $arr_ip_limit = explode('#', $ip_limit);
    }

    if (in_array($ip, $arr_ip_limit)) {
        return true;
    } else {
        return false;
    }

}

function over_cut($string, $start = 0, $length = 10, $char = 'UTF-8')
{        //截取字符串(可以有中文)
    if (strlen($string) > $length + 2) {
        $str = mb_substr($string, $start, $length, $char);
        return $str;
    } else {
        return $string;
    }
}

function time2format($time = null, $k = 1)
{//时间格式转化方法

    $format = array(
        1 => "Y-m-d H:i:s",
        2 => "Y-m-d",
        3 => "Y/m/d",
        4 => "H:i:s",
        5 => "H:i",
    );

    if ($time === null)
        return date($format[$k]);

    if ($time <= 0)
        return false;

    return date($format[$k], $time);

}

function data_change($data, $element, $name, $func, $param = 1)
{//将数据中的数据变为需要的数据
//$data:需要改变的数组;$element:需要改变的数据元素名;$name:改变后的元素名;$func:需要改变的方法;$param:$func的参数;
//	$arr_func = array(
//		0 => '时间格式',
//		1 => '数量格式',
//		2 => '自定数组格式',
//		3 => '是否',
//	);

    foreach ($data as $key => $value) {

        switch ($func) {
            case 0:
                $data[$key][$name] = time2format($value[$element], $param);
                break;
            case 1:
                $data[$key][$name] = number_format($value[$element]);
                break;
            case 2:
                $data[$key][$name] = $param[$value[$element]];
                break;
            case 3:
                $data[$key][$name] = $value[$element] == 1 ? '是' : '否';
                break;
        }

    }

    return $data;

}

function sql_in_condition($arr)
{
    if (!empty($arr)) {
        $in = "(";
        foreach ($arr as $key => $value) {
            $in .= "'{$value}',";
        }
        $in = substr($in, 0, -1);
        $in .= ")";
        return $in;
    } else {
        return false;
    }
}

//数组排序
function arr_add_sort($arr)
{
    $i = 1;
    foreach ($arr as $key => $value) {
        $arr[$key]['rank'] = $i;
        $i++;
    }
    return $arr;
}

//数组排序
function arr_add_sort_down($arr)
{
    $i = count($arr);
    foreach ($arr as $key => $value) {
        $arr[$key]['rank'] = $i;
        $i--;
    }
    return $arr;
}

//二维数组按照某一个元素排序
function array_sort($arr, $keys, $type = 'asc')
{
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

//去除空格回车
function trimTT($str)
{
    return str_replace(PHP_EOL, '', trim($str));
}

//发送邮件
function send_mail($address, $title, $message)
{

    header('Content-type:text/html;charset=utf-8');
    vendor("PHPMailer.class#phpmailer");
    $mail = new PHPMailer();
    $mail->IsSMTP();//设置PHPMailer使用SMTP服务器发送Email
    $mail->Host = "smtp.126.com"; // SMTP server 部分邮箱不支持SMTP，QQ邮箱里要设置开启的

    $mail->SMTPDebug = false;        // 改为2可以开启调试
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->Host = "smtp.126.com"; // sets the SMTP server
    $mail->Port = 25;                 // set the SMTP port for the GMAIL server
    $mail->CharSet = "UTF-8";            // 这里指定字符集！解决中文乱码问题
    $mail->Encoding = "base64";
    $mail->Username = "guyunlong0811"; // SMTP account username
    $mail->Password = "guyunlong2802";        // SMTP account password
    $mail->AddAddress($address);
    $mail->SetFrom('guyunlong0811@126.com', '白发魔女');     //发送者邮箱
    $mail->AddReplyTo('guyunlong0811@126.com', '白发魔女'); //回复到这个邮箱
    $mail->Subject = $title;
    $mail->MsgHTML($message);
    //$mail->AddAttachment('images/phpmailer.gif');      // attachment
    // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
    $mail->Send();

}

//保存SQl至配置
function save_sql($sql)
{
    $sqlList = C('G_SQL');
    $sqlList[] = $sql;
    C('G_SQL', $sqlList);
    return;
}

//生成兑换码(位数)
function str_rand($length, $chars = false)
{
    $chars = $chars ? $chars : 'abcdefghjkmnpqrstuvwxyz23456789';//ABCDEFGHJKLMNPQRSTUVWXYZ
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 1; $i <= $length; ++$i) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

//写文件
function write_exchange_log($str, $path, $filename, $suffix = 'log')
{
    $path = LOG_PATH . $path;
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }// 如果不存在则创建
    $file = $path . $filename . '.' . $suffix;
    if (!$wfp = fopen($file, "a")) {
        return false;
    }
    fputs($wfp, $str . "\r\n");
    fclose($wfp);
    return true;
}

//写文件
function write_log($str, $path, $type = 1)
{

    $path = LOG_PATH . $path;
    // 如果不存在则创建
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    switch ($type) {
        case 1:
            $filename = date('Ymd');
            break;
        case 2:
            $filename = date('Ym');
            break;
        case 3:
            $filename = date('YmdHi');
            break;
    }
    $file = $path . $filename . ".log";
    $wfp = fopen($file, "a");
    fputs($wfp, $str . "\r\n");
    fclose($wfp);
    return;
}

//写文件
function create_sql($str, $path, $filename, $openType = 'a')
{
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }// 如果不存在则创建
    $file = $path . $filename;
    if (!$wfp = fopen($file, $openType)) {
        return false;
    }
    fputs($wfp, $str . "\r\n");
    fclose($wfp);
    return true;
}

//返回
function header_info($type = 'html', $charset = 'utf-8')
{
    switch ($type) {
        case 'html':
            $type = 'text/html';
            break;
        case 'json':
            $type = 'application/json';
            break;
        case 'xml':
            $type = 'text/xml';
            break;
        case 'plain':
            $type = 'text/plain';
            break;
    }
    header("Content-type:{$type}; charset={$charset}");
}

/*************CDN*************/
function isVersion($content)
{
    return preg_match("/^\d+\.\d+\.\d+\.\d+$/", $content);
}

function create_uuid($prefix = "")
{    //可以指定前缀
    $str = md5(uniqid(mt_rand(), true));
    $uuid = substr($str, 0, 8) . '-';
    $uuid .= substr($str, 8, 4) . '-';
    $uuid .= substr($str, 12, 4) . '-';
    $uuid .= substr($str, 16, 4) . '-';
    $uuid .= substr($str, 20, 12);
    return $prefix . $uuid;
}

function htmlspecialcharsArr($arr)
{
    $arrNew = array();
    if (!empty($arr)) {
        foreach ($arr as $key => $value) {
            $arrNew[$key] = htmlspecialchars($value);
        }
    }
    return $arrNew;
}

function my_money_format($num, $type = 'rmb')
{
    $num = sprintf("%01.2f", $num / 100);
    switch ($type) {
        case 'rmb':
            $num = '¥' . $num;
            break;
    }
    return $num;
}

function usd2cny($amount)
{
    //http://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote
    $url = "http://api.k780.com:88/?app=finance.rate&scur=USD&tcur=CNY&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4";
    $rs = curl_link($url);
    $rs = json_decode($rs, true);
    $amount *= $rs['result']['rate'];
    $amount = round($amount, 2);
    return $amount;
}