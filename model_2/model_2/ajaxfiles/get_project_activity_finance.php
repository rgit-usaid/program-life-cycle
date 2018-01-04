<?php
include("../config/functions.inc.php");

$data = array(); 
###insert project finance========
if(isset($_REQUEST['get_finance']) && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!="" && isset($_REQUEST['finance_type']) && $_REQUEST['finance_type']!="" && isset($_REQUEST['finance_year']) && count($_REQUEST['finance_year'])>0)
{
	$activity_id = $_REQUEST['activity_id'];
	$byear = $_REQUEST['finance_year'];
	$finance_type = $_REQUEST['finance_type'];
	
	
	/*---get all finance data by finance type---*/
	$j=0;
	for($i=0; $i<count($byear); $i++){
		$eyear = $byear[$i]+1;
		$bdate = $byear[$i].'-10';
		$edate = $eyear.'-10';
	
			$sel = "SELECT finance_value, finance_month, finance_year FROM  usaid_project_activity_finance WHERE activity_id='".$activity_id."' AND finance_type='".$finance_type."' AND STR_TO_DATE(CONCAT(finance_year,'-',finance_month,'-','01'),'%Y-%c-%d') >= '".$bdate."' AND STR_TO_DATE(CONCAT(finance_year,'-',finance_month,'-','01'),'%Y-%c-%d') <= '".$edate."'";
		$exe = $mysqli->query($sel);
		if($exe->num_rows>0){
			while($res = $exe->fetch_array()){
				$label = $res['finance_year'].'-'.$res['finance_month'];
				$data['data'][$label]['finance_value'] = '$'.number_format($res['finance_value']);
				$data['data'][$label]['finance_month'] = $res['finance_month'];
				$data['data'][$label]['finance_year'] = $res['finance_year'];		
				$j++;
			}
			
			$data['msg_type'] = "Success";
			$data['msg'] = "Get data successfully";	
		}
	}
	
	echo json_encode($data);
}
?>