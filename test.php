<?php

header("Content-Type:text/plain;chartset=utf-8");

function decompress($str)
    {
        $str = base64_decode($str);
        $binArr = unpack("c*", $str);
        $head = [80, 75, 1, 2, 20, 0, 20, 0, 8, 8, 8, 0];
        $headcrc = [];
        for ($i = 11; $i < 15; $i++) {
            $headcrc[] = $binArr[$i];
        }
        $sufcrc = [];
        $binlength = sizeof($binArr);
        for ($i = -11; $i <= 0; $i++) {
            $sufcrc[] = $binArr[$binlength + $i];
        }
        $suf = [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 48, 80, 75, 5, 6, 0, 0, 0, 0, 1, 0, 1, 0, 47, 0, 0, 0, 50, 0, 0, 0, 0, 0];
        $byte = array_merge($binArr, $head, $headcrc, $sufcrc, $suf);
        $pack = pack("c*", ...$byte);
        $zipPath = "/tmp/" .md5(rand(1000,99999999)) . "/";
        mkdir($zipPath);
        $name = "0.zip";
        $filename = $zipPath . $name;
        file_put_contents($filename, $pack);
        //解压 zip解压有一个文件大小的问题没法拼 有可能报错 采取以下方式解压zip
        exec("unzip -o -d " . $zipPath . " " . $filename);
        $r = file_get_contents($zipPath ."/0");
        exec("rm -rf  " . $zipPath );
        //创建simpleXML对象
        $xml = simplexml_load_string($r);
        return base64_decode($xml->BODY[0], true)??[];
    }

$str = 'eJwVizEOwjAQBLCrqaI72zHvh9Q8AjHOUsIQSSgQ/kEogOJigaJAglKnkPyDS7V7s7uHmEHDMP1Md7uMIeNhmg51CwVm4Zb5NooT6tWm9CUlA0lihh8svV06JQvF7Ogfqt22qqMl/dweo7nz/7UnTYAxsXKXoii5q7tUxrFFdCkZxJWjSYHDkUT7bKlWBGhP4PXXUwTQ==';

$str = decompress($str);
echo $str;

?>