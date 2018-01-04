<?php
include("../config/functions.inc.php");

$data = array(); 
###insert project finance========
if(isset($_REQUEST['save_finance']))
{
	$activity_id = $_REQUEST['activity_id'];
	$finance_month = $_REQUEST['finance_month'];
	$finance_year = $_REQUEST['finance_year'];
	$finance_value = $_REQUEST['finance_value'];
	$finance_type = $_REQUEST['finance_type'];
	$default_finance_year = array_values(array_unique($finance_year));
	
	/*---every time delete old data of finance for this year and forecaster and planner---*/
	if(count($_REQUEST['finance_month'])>0){
		for($i=0;$i<count($finance_year);$i++){
			$del = "DELETE From usaid_project_activity_finance WHERE activity_id='".$activity_id."' AND finance_month='".$finance_month[$i]."'  AND finance_year = ".$finance_year[$i]." AND finance_type='".$finance_type."'";
			$exe = $mysqli->query($del);
			if(!$exe){
				$data['msg_type'] = "Error";
				$data['msg'] = "Something went wrong..";
			}
		}
		
		if($data['msg_type']==""){
			$ins = "Insert Into usaid_project_activity_finance (activity_id, finance_month, finance_year, finance_value, finance_type) VALUES";	
			
			for($i=0;$i<count($_REQUEST['finance_month']);$i++){
				$value = str_replace("$","",$finance_value[$i]);
				$value = str_replace(",","",$value);
				if($value!=""){
					$ins.="('".$activity_id."',".$finance_month[$i].",".$finance_year[$i].",".$value.",'".$finance_type."'),";
				}
			}
			
			$ins = substr_replace($ins,"","-1");
			$exe = $mysqli->query($ins);
			if($ins){
				$data['msg_type'] = "Success";
				$data['msg'] = "Finance amount updated successfully";
			}
			else{
				$data['msg_type'] = "Error";
				$data['msg'] = "Something went wrong..";
			}
		}
	}
	else{
		$data['msg_type'] = "Error";
		$data['msg'] = "Something went wrong..";	
	}
	
	echo json_encode($data);
}
?>