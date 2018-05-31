<?php
date_default_timezone_set("Asia/Shanghai");

//判断连号
function is_double_num($str, $num = 2){
	for ($i=0; $i < 10; $i++) { 

		$needle = '';
		for ($j=0; $j < $num; $j++) {
			$needle .= $j;
		}

		if(strpos($str, $needle) !== false){
			return true;
			break;
		}
	}

	return false;
}

//筛选号码
function AbNumber($number, $is_weak = false)
{
	$number = trim($number);

	if(strpos($number, "1") !== 0) continue;

	if($number < 11) continue;

	$num_3 = substr($number, 3, 1);
	$num_4 = substr($number, 4, 1);
	$num_5 = substr($number, 5, 1);
	$num_6 = substr($number, 6, 1);
	$num_7 = substr($number, 7, 1);
	$num_8 = substr($number, 8, 1);
	$num_9 = substr($number, 9, 1);
	$num_10 = substr($number, 10, 1);

	if ($num_7 == ($num_8 + 1) && $num_8 == ($num_9 + 1) && $num_9 == ($num_10 + 1)) {
		return $number;
	}

	if (strpos($number, '44') !== FALSE) return FALSE;
	if (strpos($number, '4') !== FALSE) return FALSE;


	// if ($num_3 != $num_4  && $num_3 != $num_5 && $num_3 != $num_6 && $num_4 != $num_5 &&  $num_4 != $num_6 && $num_5 != $num_6) {
	// 	continue;
	// }

	// if ($num_7 != $num_8  && $num_7 != $num_9 && $num_9 != $num_10 && $num_8 != $num_9 &&  $num_8 != $num_10 && $num_9 != $num_10) {
	// 	continue;
	// }

	if ($num_3 . $num_4 . $num_5 . $num_6 == $num_7 . $num_8 . $num_9 . $num_10) {
		return $number;
	}

	//后四位中三个相等8818类型
	if ($num_7 == $num_8 && $num_8 == $num_10 && $num_9 != $num_10 ) {
		return $number;
	}

	//后四位中三个相等8881类型
	if ($num_7 == $num_8 && $num_8 == $num_9 && $num_9 != $num_10) {
		return $number;
	}

	//如果是弱规则, 下列条件也符合
	if ($is_weak) {
		//后四位中两个相等1188类型
		if ($num_7 == $num_8 || $num_7 == $num_9 || $num_7 == $num_10 || $num_8 == $num_9 || $num_8 == $num_10 || $num_9 == $num_10) {
			return $number;
		}
	}

	// //后两位相同
	// if (is_double_num($num_9 . $num_10, 2)) {
	// 	$return[] = $value;
	// }

	return FALSE;
}

function Request($url, $data = [], $is_post = false)
{
	try {
		$headers = [
			"content-type: application/x-www-form-urlencoded",
			"User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1"
		];

	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $output = curl_exec($ch);


        if ($output === false) {
            $error = curl_error($ch);
            throw new Exception('api错误|' . $error);
        }

        if ($output === false) {
            $error = curl_error($ch);
            throw new Exception('api错误|' . $error);
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != 200 && $http_code != 201) {
            throw new Exception( '请求出错了：' . $http_code . ' output: ' . $output);
        }

        return $output;
	} catch (Exception $e) {
		var_dump($e->getMessage());
		var_dump($e->getTrace());
	}
}

//判断是否是电话号
function isNumber($number)
{
	if (strlen($number) < 11 ) return FALSE;
	return TRUE;
}

//联通天神卡
function getWoNumber()
{
	$time = time() . "0" .  rand(10, 99);
	$url = "http://m.10010.com/NumApp/NumberCenter/qryNum?callback=jsonp_queryMoreNums&provinceCode=31&cityCode=310&monthFeeLimit=0&groupKey=8300283067&searchCategory=3&net=01&amounts=200&codeTypeCode=&searchValue=&qryType=02&goodsNet=4&_=" . $time;

	$res = Request($url, '');

	$res = ltrim($res, "jsonp_queryMoreNums(");
	$res = rtrim($res, ")");

	$res = json_decode($res, true);

	if (!$res) return FALSE;

	$res = array_filter($res['numArray'], "isNumber");
	return $res;
}

//获取移动魔卡信息
function getAndNumber()
{
	$time = time() . "0" .  rand(10, 99);
	$url = "http://www.sh.10086.cn/h5/action.do?_t=" . $time;
	$data = array(
		"pool_type" => "0",
		"rwx_px" => "",
		"number" => "",
		"fro_num" => "",
		"wxflag" => 0,
		"number_type" => 2,
		"search_type" => 0,
		"act" => "h5-salecard-getcardlist"
	);

	foreach ($data as $key => $value) {
		 $param .= $key . "=" . $value;
		 $param .= "&";
	}

	$param = trim($param, "&");

	$res = Request($url, $param, true);

	if (!$res) return FALSE;

    $res = json_decode($res, true);

	$numbers = $res['value']['list'];
	foreach ($numbers as $key => $value) {
		$result[] =  $value['serial_number'];
	}

	return $result;
}



echo "🍺  >>> Starting Request Phone Number for 10010,11086 ...\n\n";

$andNumbers = getAndNumber();
$woNumbers = getWoNumber();

$andNumbers = $andNumbers ? $andNumbers : array();
$woNumbers = $woNumbers ? $woNumbers : array();

$number = [];

echo "🍺  >>> 10086 : " . count($andNumbers) . " Phone Number \n";
echo "🍺  >>> 10010 : " . count($woNumbers) . " Phone Number \n\n";


echo "🍺  >>> Screening Phone Number ...\n\n";
foreach ($andNumbers as $value) {
	if (AbNumber($value)) {
		$number[] = [
			'number' => $value,
			'type' => "1"
		];
	}
}

foreach ($woNumbers as $value) {
	if (AbNumber($value)) {
		$number[] = [
			'number' => $value,
			'type' => "2"
		];
	}
}

$str = file_get_contents(__DIR__ . '/number.log');
$old_numbers = explode(",",$str);

$new_numbers = []; $strLog = '';
foreach ($number as $value) {
	echo "😄 Good Number: " . $value['number'];
	echo " -> ";
	echo $value['type'] == 1 ? "10010" : "10086";
	echo "\n";
	if (in_array($value['number'], $old_numbers)) continue;
	echo "🌟 New Good Number: ", $value['number'];
	echo " -> ";
	echo $value['type'] == 1 ? "10010" : "10086";
	echo "\n";
	$strLog .= $value['number'] . ",\n";
}
if (!$strLog) {
	echo "😭\n😭  Not New Good Phone Number ...\n😭";
}

echo "\n\n";
file_put_contents(__DIR__ . "/number.log", $strLog, FILE_APPEND);





