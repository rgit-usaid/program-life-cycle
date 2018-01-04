<?php
include('../config/config.inc.php');
include('../include/function.inc.php'); 

if(isset($_REQUEST) && $_REQUEST['gohashid']!=""){
	$gohashid = $_REQUEST['gohashid'];
	$data =array();
	
	//===get all association===
	$sel = "SELECT * FROM usaid_association WHERE gohashid='".$gohashid."'";
	$exe= $mysqli->query($sel);
	$i=0;
	while($fetch = $exe->fetch_array()){
		$data[$i]['gohashid'] =$fetch['gohashid'];
		$data[$i]['association_type'] =$fetch['association_type'];
		$data[$i]['association_id'] =$fetch['association_id'];
		$data[$i]['association_value'] =$fetch['association_value'];
		$i++;
	}
	
	echo json_encode($data);
}
?>