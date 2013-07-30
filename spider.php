<?php
class Spider{
	private $url="www.baidu.com";
	private $depth = 1;
	public function __get($val_name) {
		if(isset($this->$val_name)){
			return ($this->$val_name);
		}else {
			return (NULL);
		}
	}
	public function __set($val_name, $val_value) {
		$this->$val_name = $val_value;
	}
	public function __isset($val_name) {
		return isset($this->$val_name);
	}
	public function __unset($val_name) {
		unset($this->$val_name);
	}
	function get_content($urlvalue){
		$url_parts = parse_url($urlvalue);
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

	function match_urltitle($urlvalue) {
		$content = $this->get_content($urlvalue);
		$urltitle = array();
		//"<a href="http://image.baidu.com name="hello"">图&nbsp;片</a>"
		preg_match_all("/<a href=(\"|\')(.*?)(\"|\')[^>]*>(.*?)<\/a>/", $content, $urltitle);
		$utvalue = array();
		$url = array();
		$title = array();
		$i=0;
		$j=0;
		$k=0;
		foreach($urltitle[2] as $node) {
			$url[$i] = $node;
			$i++;	
		}
		foreach($urltitle[4] as $node1) {
			$title[$j] = $node1;
			$j++;	
		}
		for($k=0; $k<count($url);$k++) {
			$urltitle[$k] = array("url"=>$url[$k], "title"=>$title[$k]);
		}
		var_dump($urltitle);
		return $urltitle;
	}
}

function run($spider) {
	$urltitle = $spider->match_urltitle($spider->url);
	$spider->__set(depth, $spider->depth-1);
	
	//$allurltitle2 = array();
	if($spider->depth >= 1) {	
		foreach($urltitle as $node) {
			$spider2 = new Spider();
			$spider2->__set(url, $node["url"]);
			$spider2->__set(depth, $spider->depth);
			run($spider2);
			//$urltitle2 = $spider2->match_urltitle($spider->url);
			//var_dump($urltitle2);
			//array_push($allurltitle2, $urltitle2);
		}
		//var_dump($allurltitle2);
	}
}
$spider = new Spider();
$spider->__set(url, $argv[1]);
$depth = (int)$argv[2];
$spider->__set(depth, $depth);
$urltitle1 = run($spider);

//$allurltitle2 = array();
//
//foreach($urltitle1 as $node) {
//		$spider2 = new Spider();
//		$spider2->__set(url, $node["url"]);
//		var_dump($spider2->url);
//		$urltitle2 = $spider2->match_urltitle();
//		var_dump($urltitle2);
//		array_push($allurltitle2, $urltitle2);
//	}
//	//var_dump($allurltitle2);

?>
