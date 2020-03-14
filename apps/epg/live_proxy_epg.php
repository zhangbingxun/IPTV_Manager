<?php
header("Content-Type:application/json;chartset=uft-8");
include_once "proxy_chk.php";
include_once "curl.class.php";
include_once "caches.class.php";

$cachedir = "./cache";
if (! is_dir ($cachedir)) {
    @mkdir ($cachedir, 0755, true) or die ('创建文件夹失败');
} 
$id = !empty($_GET["id"])?$_GET["id"]:exit(json_encode(["code" => 500, "msg" => "EPG频道参数不能为空!", "name" => $name, "date" => null, "data" => null], JSON_UNESCAPED_UNICODE));
if(!empty($_GET["is_ez"])){
	echo out_epg($id,true);
}else{
	echo out_epg($id);
}
exit;
// 输出EPG节目地址
function out_epg($id,$is_ez=false) {
    $tvdata = channel($id);
    $tvid = $tvdata['id'];
    $epgid = $tvdata['name'];

    if (!is_numeric($tvid)) {
        return $tvid;
    } 

    $tt = cache("time_out_chk", "cache_time_out"); //获取当前时间（后天）的00:00时间戳
    if (time() >= $tt) {
        Cache::$cache_path = "./cache/"; 
        // 删除除当前目录缓存文件
        Cache::dels(); 
        // 重新写入当天时间缓存文件
        cache("time_out_chk", "cache_time_out");
    } 
	$ejson=cache($tvid,"get_epg_data",[$tvid,$epgid,$id]);
    return $ejson;
} 
//获取当前播放位置
function getPos($json,$which_api) {
	$curpos_sure=false;
	$cur=date("H:i");
	$pos=0;
	foreach($json as $v) {
		$list_index=1;
		foreach($v as $v1) {
			$i=0;
			foreach($v1 as $v2) {
				if($which_api==6) {
					//天脉接口计算
					if($i==9) {
						if($list_index ==count($v)&&!$curpos_sure) {
							//最后一个
							$pos = $list_index;
							$curpos_sure=true;
						}
						if(strtotime($cur)>strtotime($v2)) {
							//当前正在播放
							$curpos_sure=true;
						} else {
							if(curpos_sure) {
								$pos = $list_index;
								break;
							}
						}
					}
				} else {
					if($i==1) {
						//echo $v2."\n";
						if($list_index ==count($v)&&!$curpos_sure) {
							//最后一个
							$pos = $list_index;
							$curpos_sure=true;
						}
						if(strtotime($cur)<strtotime($v2)) {
							//当前正在播放
							$curpos_sure=true;
						} else {
							if($curpos_sure) {
								$pos = $list_index;
								break;
							}
						}
					}
				}
				$i++;
			}
			$list_index++;
		}
	}
	return ($pos-1);
}
// 缓存EPG节目数据
function cache($key, $f_name, $ff = []) {
    Cache::$cache_path = "./cache/";
    $val = Cache::gets($key);
    if (!$val) {
        $data = call_user_func_array($f_name, $ff);
        Cache::put($key, $data);
        return $data;
    } else {
        return $val;
    } 
} 

function cache_time_out() {
    date_default_timezone_set("Asia/Shanghai");
    $tt = strtotime(date("Y-m-d 00:00:00", time())) + 86400;
    return $tt;
} 
// 请求频道的EPG数据
function get_epg_data($tvid, $epgid, $name = "", $date = "") {  // CNTV
    if (strstr($epgid, "cntv") != false) {
        $url = "https://api.cntv.cn/epg/epginfo?serviceId=cbox&c=" . substr($epgid, 5) . "&d=" . date('Ymd');
        $str = curl::c()->set_ssl()->get($url);
        $re = json_decode($str, true);
        if (!empty($re[substr($epgid, 5)]['program'])) {
            $data = array("code" => 200, "msg" => "请求成功!", "name" => $re[substr($epgid, 5)]['channelName'], "tvid" => $tvid, "date" => date('Y-m-d'));
            foreach($re[substr($epgid, 5)]['program'] as $row) {
                $data["data"][] = array("name" => $row['t'], "starttime" => $row['showTime']);
            } 
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } 
        $data = ["code" => 500, "msg" => "请求失败!", "name" => $name, "date" => null, "data" => null];
		//$data["pos"] = getPos($data);  //当前播放位置
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    } else if (strstr($epgid, "tvsou") != false) {   // 搜视网
        $wday = intval(date('w', strtotime(date('Y-m-d'))));
        if ($wday == 0)$wday = 7;
        $url = "https://www.tvsou.com/epg/" . substr($epgid, 6) . "/w" . $wday;
        $file = curl::c()->set_ssl()->get($url);
        $file = strstr($file, "<tbody>");
        $pos = strpos($file, "</tbody>");
        $file = substr($file, 0, $pos);
        $file = preg_replace(array("/<script[\s\S]*?<\/script>/i", "/<a .*?href='(.*?)'.*?>/is", "/<tbody>/i", "/<\/a>/i"), '', $file);
		$file =  str_replace("</td><td></td></tr> ", '|', $file);
		$file =  str_replace("</td><td>", '#', $file);
		$file =  str_replace(array("<tr><td>","\r","\n","\r\n"," "), '', $file);
		$preview = substr($file,0,strlen($file)-1);
		if (!empty($preview)) {
			$data=array("code"=>200,"msg"=>"请求成功!","name"=>$name,"tvid"=>$tvid,"date"=>date('Y-m-d'));
			$preview =  str_replace("</td><tdstyle='width:100px;'></td></tr>", '|', $preview);
			$preview =  str_replace("</td><tdstyle='width:100px;'></td></tr", '', $preview);
			$preview = explode('|',$preview);
			foreach($preview as $row) {
				$row1 = explode('#',$row);
				$data["data"][]= array("name"=> $row1[1],"starttime"=> $row1[0]);
			}
			$data["pos"] = getPos($data);  //当前播放位置
            return json_encode($data, JSON_UNESCAPED_UNICODE);;
        } 
        $data = ["code" => 500, "msg" => "请求失败!", "name" => $name, "date" => null, "data" => null];
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    } else if (strstr($epgid, "tvmao") != false) {  // 电视猫
        $keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        $wday = intval(date('w', strtotime(date('Y-m-d'))));
        if ($wday == 0)
            $wday = 7;
        $str = curl::c()->set_ssl()->get("https://m.tvmao.com/program/" . substr($epgid, 6) . "-w" . $wday . ".html");
        preg_match('#action="/query.jsp" q="(\w+)" a="(\w+)"#', $str, $id);
        preg_match('#name="submit" id="(\w+)"#', $str, $id1);
        $str1 = curl::c()->set_ssl()->get("https://m.tvmao.com/api/pg?p=" . $keyStr[$wday * $wday] . base64_encode($id1[1] . "|" . $id[2]) . base64_encode("|" . $id[1]));
        $str1 = preg_replace(array('/<tr[^>]*>/i', '/<td[^>]*>/i', '/<div[^>]*>/i', '/<a[^>]*>/i'), '', $str1);
        $str1 = str_replace("<\/a>", '', $str1);
        $str1 = str_replace("<\/div><\/td>", '#', $str1);
        $str1 = str_replace("<\/td><\/tr>", '|', $str1);
        $str1 = str_replace('[1,"', '', $str1);
        $str1 = str_replace('"]', '', $str1);
        $str1 = str_replace('\n', '', $str1);
        $str1 = substr($str1, 0, strlen($str1)-1);
		$preview = substr($str1,0,strlen($str1)-1);
        if (!empty($preview)) {
            $data = array("code" => 200, "msg" => "请求成功!", "name" => $name, "tvid" => $tvid, "date" => date('Y-m-d'));
            $preview = explode('|', $preview);
            foreach($preview as $row) {
                $row1 = explode('#', $row);
                $data["data"][] = array("name" => $row1[1], "starttime" => $row1[0]);
            } 
			$data["pos"] = getPos($data);  //当前播放位置
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } 
        $data = ["code" => 500, "msg" => "请求失败!", "name" => $name, "date" => null, "data" => null]; 
        return json_encode($data, JSON_UNESCAPED_UNICODE); 
	} else if(strstr($epgid,"tvming") != false) {  //天脉接口
		$url = "http://passport.live.tvmining.com/approve/epginfo?channel=";
		// 通过 php 的 file_get_contents 函数传给 $html 变量
		$html = file_get_contents($url.substr($epgid, 7));
		// 把字符串$html转为XML
		$xml = simplexml_load_string($html, 'SimpleXMLElement', LIBXML_NOCDATA);
		// 把XML转成数组$arr
		$arr = object_array($xml);
		foreach($arr["epg"] as &$epg) {
			foreach($epg["program"] as &$val) {
				$val["name"] = $val["title"];
				$val["starttime"] = date("H:i", $val["start_time"]);
			}
		}
		unset($epg);
		unset($val);
		// $arr["epg"][0]["date"] 里面的0代表今天
		// 改成1是昨天的 改成2是前天的
		// 最多改成6是一周前的
		$newar= array(
			"name" => $arr["channel"],
			"date" => $arr["epg"][0]["date"],
			"which" => 6,
			"data" => $arr["epg"][0]["program"]
		);
		return json_encode($newar, JSON_UNESCAPED_UNICODE);
        // 51zmt
    } else if (strstr($epgid, "51zmt") != false) {
        $cachefile = "./cache/51zmt.xml";
        $url = "http://epg.51zmt.top:8000/e.xml";
        if (!file_exists($cachefile)) {
            $file = curl::c()->get($url);
            file_put_contents($cachefile, $file) ;
        } 
        $xml = simplexml_load_file($cachefile);
        $xml = json_encode($xml);
        $xml = json_decode($xml, true);
        $arr = $channel = $epgdata = $result = array();
        foreach($xml['channel'] as $row) {
            $channel['data'][] = array('id' => $row['@attributes']['id'], 'name' => $row['display-name']);
        } 
        foreach($channel['data'] as $key => $value) {
            foreach ($value as $valu) {
                if (substr($epgid, 6) == $valu) {
                    array_push($arr, $key);
                } 
            } 
        } 
        foreach ($arr as $key => $value) {
            if (array_key_exists($value, $channel['data'])) {
                array_push($result, $channel['data'][$value]);
            } 
        } 
        foreach($xml['programme'] as $row) {
            $epgdata[] = array('id' => $row['@attributes']['channel'], "start" => $row['@attributes']['start'], 'title' => $row['title']);
        } 
        if (!empty($epgdata)) {
            $data = array("code" => 200, "msg" => "请求成功!", "name" => $name, "tvid" => $tvid, "date" => date('Y-m-d'));
            foreach($epgdata as $row) {
                if ($row['id'] == $result[0]['id']) {
                    $data["data"][] = array("name" => $row['title'], "starttime" => preg_replace('{^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(.*?)$}u', '$4:$5', $row["start"]));
                } 
            } 
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } 
        $data = ["code" => 500, "msg" => "请求失败!", "name" => $name, "date" => null, "data" => null];
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    } 
} 
//天脉工具
function object_array($array) {
	if(is_object($array)) {
		$array = (array)$array;
	}
	if(is_array($array)) {
		foreach($array as $key=>$value) {
			$array[$key] = object_array($value);
		}
	}
	return $array;
}
// 频道映射对应表
function channel($id) {
    global $db;
    $id = urldecode($id);
    if ($row = $db->mGetRow("luo2888_epg", "*", "where status=1 AND FIND_IN_SET('$id',content)")) {
        return $row;
    } else {
        $data = ["code" => 500, "msg" => "频道不存在!", "name" => null, "date" => null, "data" => null];
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    } 
} 

?>