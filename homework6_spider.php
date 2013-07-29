<?php

//************
//***匹配网页内容中所有的<a href= ""> xxx </a>
//***return array[][]
//************
function MatchA($content) {
	$match_a = array();
	preg_match_all("/<a href=(\"|\').*?(\"|\')>.*?<\/a>/", $content, $match_a);
	return $match_a;
}

//************
//***匹配<a href= ""> xxx </a>中的url和title 
//***return array[][]
//************
function MatchUrlTitle($matchA) {
	$urltitle = array();
	$i = 0;
	//"<a href="http://image.baidu.com">图&nbsp;片</a>"
	foreach($matchA[0] as $node)
	{
		//var_dump($node);
		preg_match_all("/\"(.*?)\"/", $node, $url);
		//var_dump($url[1][0]);
		
		preg_match_all("/\>(.*?)\</", $node, $title);
		//var_dump($title[1][0]);
		$urltitle[$i] = array("url"=>$url[1][0], "title"=>$title[1][0]);
		$i++;
	}
	return $urltitle;
}

//************
//***获取某页面所有内容 
//***return string
//************
function get_content($url){
	$url_parts = parse_url($url);
	//["scheme"]=>string(4) "http"	["host"]=>string(13) "www.baidu.com"	["path"]=>string(6) "/a/b/s"	["query"]=>string(13) "wd=123&wq=456"
	$fp = fsockopen($url_parts["host"], 80, $errno, $errstr, 30);
	if(!$fp) {
		echo "$errstr ($errno) </br>\n";
	} else
	{
		$out .= "GET /"." ".$url_parts["scheme"]."/1.1\r\n";
		$out .= "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0\r\n"; 
		$out .= "Path: ".$url_parts["path"]."\r\n";
		$out .= "Query: ".$url_parts["query"]."\r\n";
		$out .= "Host: ". $url_parts["host"]. "\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fputs($fp, $out);
		while(!feof($fp)) {
			$result .= fgets($fp, 128);			
		}
		fclose($fp);
	}
	return $result;
}

	//"http://www.baidu.com/a/b/s?wd=123&wq=456"
	$content = get_content($argv[1]);
	$match_a = matchA($content);
	$match_urltitle = matchUrlTitle($match_a);
	//var_dump($match_urltitle);
	
	$match_urltitle2 = array();
	foreach($match_urltitle as $node) {
		$content1 = get_content($node["url"]);
		$match_a1 = matchA($content1);
		$match_urltitle1 = matchUrlTitle($match_a1);
		array_push($match_urltitle2, $match_urltitle1);
	}
	var_dump($match_urltitle2);


?>


