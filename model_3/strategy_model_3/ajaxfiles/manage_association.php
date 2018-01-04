<?php
include('../config/config.inc.php');
include('../include/function.inc.php'); 

if(isset($_REQUEST) && ( count($_REQUEST['prgm_elem'])>0 || count($_REQUEST['st_indicator']) >0 || count($_REQUEST['cs_indicator'])>0 || count($_REQUEST['projects'])>0 || count($_REQUEST['activities'])>0 || $_REQUEST['budget']!="")){
	
	$gohashid = $_REQUEST['gohashid'];
	$program_element = $_REQUEST['prgm_elem'];
	$standard_indicator = $_REQUEST['st_indicator'];
	$custom_indicator = $_REQUEST['cs_indicator'];
	$projects = $_REQUEST['projects'];
	$activities = $_REQUEST['activities'];
	$budget = str_replace("$","",$_REQUEST['budget']);
	$budget = str_replace(",","",$budget);
	$disp_msg ="";
	
	$del = "DELETE FROM usaid_association WHERE gohashid ='".$gohashid."'";
	$mysqli->query($del);
	
	//===loop in program_element===
	if(count($program_element)>0){
		$ins = "INSERT INTO usaid_association(gohashid,association_type,association_id) VALUES";
		for($i=0;$i<count($program_element);$i++){
			$ins.= "('".$gohashid."','Program Element','".$program_element[$i]."'),";
		}
		
		$ins = substr_replace($ins,"",-1);
		$mysqli->query($ins);
		if(!$ins){
			$disp_msg['msg'] ="Error Occur";
			$disp_msg['msg_type'] ="error";
			echo json_encode($disp_msg);
			exit;
		}
	}
	
	//===loop in standard_indicator===
	if(count($standard_indicator)>0){
		$ins = "INSERT INTO usaid_association(gohashid,association_type,association_id) VALUES";
		for($i=0;$i<count($standard_indicator);$i++){
			$ins.= "('".$gohashid."','Standard Indicator','".$standard_indicator[$i]."'),";
		}
		
		$ins = substr_replace($ins,"",-1);
		$mysqli->query($ins);
		if(!$ins){
			$disp_msg['msg'] ="Error Occur";
			$disp_msg['msg_type'] ="error";
			echo json_encode($disp_msg);
			exit;
		}
	}
	
	//===loop in standard_indicator===
	if(count($custom_indicator)>0){
		$ins = "INSERT INTO usaid_association(gohashid,association_type,association_id) VALUES";
		for($i=0;$i<count($custom_indicator);$i++){
			$ins.= "('".$gohashid."','Custom Indicator','".$custom_indicator[$i]."'),";
		}
		
		$ins = substr_replace($ins,"",-1);
		$mysqli->query($ins);
		if(!$ins){
			$disp_msg['msg'] ="Error Occur";
			$disp_msg['msg_type'] ="error";
			echo json_encode($disp_msg);
			exit;
		}
	}
	
	//===loop in projects===
	if($_REQUEST['association']=="Project"){
		if(count($projects)>0){
			$ins = "INSERT INTO usaid_association(gohashid,association_type,association_id) VALUES";
			for($i=0;$i<count($projects);$i++){
				$ins.= "('".$gohashid."','Project','".$projects[$i]."'),";
			}
			
			$ins = substr_replace($ins,"",-1);
			$mysqli->query($ins);
			if(!$ins){
				$disp_msg['msg'] ="Error Occur";
				$disp_msg['msg_type'] ="error";
				echo json_encode($disp_msg);
				exit;
			}
		}
	}
	
	//===loop in activities===
	if($_REQUEST['association']=="Activity"){
		if(count($activities)>0){
			$ins = "INSERT INTO usaid_association(gohashid,association_type,association_id) VALUES";
			for($i=0;$i<count($activities);$i++){
				$ins.= "('".$gohashid."','Activity','".$activities[$i]."'),";
			}
			
			$ins = substr_replace($ins,"",-1);
			$mysqli->query($ins);
			if(!$ins){
				$disp_msg['msg'] ="Error Occur";
				$disp_msg['msg_type'] ="error";
				echo json_encode($disp_msg);
				exit;
			}
		}
	}
	
	//===if budget is not blank insert it the table===
	if($budget!=""){
		$ins = "INSERT INTO usaid_association(gohashid,association_type,association_value) VALUES ('".$gohashid."','Budget','".$budget."')";
		$mysqli->query($ins);
		if(!$ins){
			$disp_msg['msg'] ="Error Occur";
			$disp_msg['msg_type'] ="error";
			echo json_encode($disp_msg);
			exit;
		}
	}
	
	
	$disp_msg['msg'] ="Association successfully inserted.";
	$disp_msg['msg_type'] ="success";
	echo json_encode($disp_msg);
	exit;
}
?>