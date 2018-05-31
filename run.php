<?php
function is_double_num($str){
	for ($i=0; $i < 10; $i++) { 
		if(strpos($str, $i . $i) !== false){
			return true;
			break;
		}
	}

	return false;
}


function get_number($i){
	$return = $numbers = $values = [];
	$str = file_get_contents(__DIR__ . '/numbers_' . fmod($i, 20) .'.log');

	$str_array = explode(",",$str);

	foreach ($str_array as $value) {
		 $value = trim($value);
		if(strpos($value, "1") === 0){
			if($value < 11) continue;

			if (strpos('44', $value) !== false) continue;

			$num_3 = substr($value, 3, 1);
			$num_4 = substr($value, 4, 1);
			$num_5 = substr($value, 5, 1);
			$num_6 = substr($value, 6, 1);
			$num_7 = substr($value, 7, 1);
			$num_8 = substr($value, 8, 1);
			$num_9 = substr($value, 9, 1);
			$num_10 = substr($value, 10, 1);

			if ($num_3 != $num_4  && $num_3 != $num_5 && $num_3 != $num_6 && $num_4 != $num_5 &&  $num_4 != $num_6 && $num_5 != $num_6) {
				continue;
			}

			if ($num_9 == $num_10) {
				continue;
			}

			// if ($num_7 != $num_8  && $num_7 != $num_9 && $num_9 != $num_10 && $num_8 != $num_9 &&  $num_8 != $num_10 && $num_9 != $num_10) {
			// 	continue;
			// }

			if ($num_3 != $num_5 && $num_4 != $num_6){
				continue;
			}

			if ($num_7 != $num_9 && $num_8 != $num_10){
				continue;
			}

			if ($num_3 == $num_5 && $num_5 == $num_7 && $num_7 == $num_9) {
				$return[] = $value;
			}

			if ($num_4 == $num_6 && $num_6 == $num_8 && $num_8 == $num_10) {
				$return[] = $value;
			}

			// if ($num_9 != $num_10  && $num_7 == $num_8 && $num_7 == $num_10) {
			// 	$return[] = $value;
			// }


			// if ($num_9 != $num_10  && $num_7 == $num_8 && $num_7 == $num_9) {
			// 	$return[] = $value;
			// }

			// if ($num_3 == $num_4  || $num_5 == $num_6) {
			// 	$return[] = $value;
			// }

			// $return[] = $value;

		}
	}

	// foreach ($numbers as $key => $value) {
	// 	if($value > 11){
	// 		//判断第4位到第7位
	// 		if (is_double_num(substr($value, 3, 4)) && is_double_num(substr($value, 7, 11))) {
	// 			$values[] = $value;
	// 		}
	// 	}
	// }

	foreach ($values as $value) {
		
	}

	return $return;
}

function getNumbserLog($max_num = 100){
	//深圳
	// $id = 1527732551150;
	// $url = "https://m.10010.com/NumApp/NumberCenter/qryNum?callback=jsonp_queryMoreNums&provinceCode=51&cityCode=540&monthFeeLimit=0&groupKey=21236872&searchCategory=3&net=01&amounts=200&codeTypeCode=&searchValue=&qryType=02&goodsNet=4&_=";
	// //广州
	// $id = 1527732554130;
	// $url = "https://m.10010.com/NumApp/NumberCenter/qryNum?callback=json&provinceCode=51&cityCode=540&monthFeeLimit=0&groupKey=21236872&searchCategory=3&net=01&amounts=200&codeTypeCode=&searchValue=&qryType=02&goodsNet=4&_=";

	//上海
	$id = 1527747217416;
	$url = "https://m.10010.com/NumApp/NumberCenter/qryNum?callback=jsonp_queryMoreNums&provinceCode=31&cityCode=310&monthFeeLimit=0&groupKey=3200312468&searchCategory=3&net=01&amounts=200&codeTypeCode=&searchValue=&qryType=02&goodsNet=4&_=";

	// //北京
	// $id = 1527747215417;
	// $url = "https://m.10010.com/NumApp/NumberCenter/qryNum?callback=jsonp_queryMoreNums&provinceCode=11&cityCode=110&monthFeeLimit=0&groupKey=7200310618&searchCategory=3&net=01&amounts=200&codeTypeCode=&searchValue=&qryType=02&goodsNet=4&_=";

	file_put_contents(__DIR__ . "/a.sh", "#!/bin/sh \n");
	for ($i=0; $i < $max_num; $i++) { 
		$url_id = $url . $id;
		$id++;
		$cmd = "/usr/bin/curl '" . $url_id . "' >> " . __DIR__ . "/numbers_" . fmod($i, 20) . ".log & \n";
		file_put_contents(__DIR__ . "/pullLog.sh", $cmd, FILE_APPEND);
	}
}

if ($argv[1] == 'pning') {
	getNumbserLog(2000);
}

if ($argv[1] == 'sning') {
	$numbers = array();

	for ($i=0; $i < 100; $i++) { 
		$numbers = array_merge($numbers, get_number($i));
	}

	$numbers = array_unique($numbers, SORT_STRING);
	foreach ($numbers as $value) {
		echo $value . "\n";
	}
}




