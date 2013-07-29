<?php
$match = array(); 
//preg_match_all("/<a href=\"|'.*\"|'>.*?<\/a>/", 
preg_match_all("/<a href=(\"|\').*?(\"|\')>.*?<\/a>/", 
"<a href=\"he\"> ah </a>kkk <a href='lo'> ha </a> 125 <a href='> he </a>123 <a href='' hh </a>123", 
	$match);
var_dump($match);

?>
