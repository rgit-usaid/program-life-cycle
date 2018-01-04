<?php
function requestByCURL($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);                               
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	$data_arr = json_decode($output,true);
	return $data_arr;
}
?>