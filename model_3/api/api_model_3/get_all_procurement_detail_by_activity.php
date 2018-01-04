<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all archive project evalution============
define('API_HOST_URL_PROJECT','http://rgdemo.com/usaid/api-amp3/');
define('API_HOST_URL_GLAAS','http://rgdemo.com/usaid/api-glaas3/');
define('API_HOST_URL_PHOENIX','http://rgdemo.com/usaid/api-phoenix3/');


if(isset($_REQUEST['activity_id']))
{
	$activity_id = $_REQUEST['activity_id'];
	$project_activity= explode("-",$_REQUEST['activity_id']);
	$project_id = $project_activity[0];
	//$activity_id = $project_activity[1];

	 $url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id.""; 
   // $project_arr = requestByCURL($url);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $project_arr = json_decode($output,true); 
	
	
	$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id; 
   // $project_activity_arr = requestByCURL($url);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $project_activity_arr = json_decode($output,true); 
	
	/*get all awards of projects activity*/
	$project_act_proc_arr= array(); $project_act_awd_arr= array(); $project_act_clin_arr= array();
	
	//get all award of projects activity/
	$url = API_HOST_URL_GLAAS."get_award_by_activity.php?activity_id=".$activity_id;
	//$projectActProc_arr = requestByCURL($url);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $projectActProc_arr = json_decode($output,true); 
	 
	for($i=0;$i<count($projectActProc_arr['data']);$i++){
		$award_number = $projectActProc_arr['data'][$i]['award_number'];
		$project_act_proc_arr[$award_number]['award_number'] = $projectActProc_arr['data'][$i]['award_number'];
		$project_act_proc_arr[$award_number]['id'] = $projectActProc_arr['data'][$i]['award_id'];
		$project_act_proc_arr[$award_number]['vendor_name'] = $projectActProc_arr['data'][$i]['name'];
		$project_act_proc_arr[$award_number]['DUNS_number'] = $projectActProc_arr['data'][$i]['DUNS_number'];
		$project_act_proc_arr[$award_number]['obligate'] = $projectActProc_arr['data'][$i]['amount']; 	
	    $project_act_proc_arr[$award_number]['actual_obligate'] = $projectActProc_arr['data'][$i]['amount']; 	
		$project_act_proc_arr[$award_number]['paid'] = 0;
		$project_act_proc_arr[$award_number]['available'] = $project_act_proc_arr[$award_number]['obligate'] - $project_act_proc_arr[$award_number]['paid'];
	}
	
	//get all award clin of projects//
	$url = API_HOST_URL_GLAAS."get_award_clin_by_activity.php?activity_id=".$activity_id;  
	//$projectActProc_arr = requestByCURL($url); 
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $projectActProc_arr = json_decode($output,true); 
	
	for($i=0;$i<count($projectActProc_arr['data']);$i++){
		$award_number = $projectActProc_arr['data'][$i]['award_number'];
		$clin_number = $projectActProc_arr['data'][$i]['clin_number'];
		if(!array_key_exists($award_number,$project_act_proc_arr)){
			$project_act_proc_arr[$clin_number]['award_number'] = $projectActProc_arr['data'][$i]['clin_number'];
			$project_act_proc_arr[$clin_number]['id'] = $projectActProc_arr['data'][$i]['clin_id'];
			$project_act_proc_arr[$clin_number]['vendor_name'] = $projectActProc_arr['data'][$i]['name'];
			$project_act_proc_arr[$clin_number]['DUNS_number'] = $projectActProc_arr['data'][$i]['DUNS_number'];
			$project_act_proc_arr[$clin_number]['obligate'] = $projectActProc_arr['data'][$i]['amount']; 
			
			//get total paid amount on award clin
			$url = API_HOST_URL_PHOENIX."get_account_payble_by_clin.php?clin_no=".$clin_number;  
			//$account_payble_amt_arr = requestByCURL($url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);                               
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			curl_close($ch);
			$account_payble_amt_arr = json_decode($output,true); 
			
			if(count($account_payble_amt_arr['data'])>0){
				$project_act_proc_arr[$clin_number]['paid'] = $account_payble_amt_arr['data']['total_paid_amount'];
			}
			else{
				$project_act_proc_arr[$clin_number]['paid'] = 0;
			}
			
			$project_act_proc_arr[$clin_number]['available'] = $project_act_proc_arr[$clin_number]['obligate'] - $project_act_proc_arr[$clin_number]['paid'];
		}
		else{
			$project_act_proc_arr[$award_number]['CLINS'][$clin_number]['clin_number'] = $clin_number;
			$project_act_proc_arr[$award_number]['CLINS'][$clin_number]['obligate'] = $projectActProc_arr['data'][$i]['amount']; 
			
			//get total paid amount on award clin
			$url = API_HOST_URL_PHOENIX."get_account_payble_by_clin.php?clin_no=".$clin_number;  
			//$account_payble_amt_arr = requestByCURL($url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);                               
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			curl_close($ch);
			$account_payble_amt_arr = json_decode($output,true);
			
			if(count($account_payble_amt_arr['data'])>0){
				$project_act_proc_arr[$award_number]['CLINS'][$clin_number]['paid'] = $account_payble_amt_arr['data']['total_paid_amount'];			
			}
			else{
				$project_act_proc_arr[$award_number]['CLINS'][$clin_number]['paid'] = 0;
			}
			$project_act_proc_arr[$award_number]['CLINS'][$clin_number]['available'] = $project_act_proc_arr[$award_number]['CLINS'][$clin_number]['obligate'] - $project_act_proc_arr[$award_number]['CLINS'][$clin_number]['paid'];
			
			$project_act_proc_arr[$award_number]['obligate'] = $project_act_proc_arr[$award_number]['obligate'] + $project_act_proc_arr[$award_number]['CLINS'][$clin_number]['obligate'];
			$project_act_proc_arr[$award_number]['paid'] = $project_act_proc_arr[$award_number]['paid'] + $project_act_proc_arr[$award_number]['CLINS'][$clin_number]['paid'];
			$project_act_proc_arr[$award_number]['available'] = $project_act_proc_arr[$award_number]['obligate'] - $project_act_proc_arr[$award_number]['paid'];	 	
		}
	}	
							
    $data = array();
	$i=0;

	if(count($project_act_proc_arr)>0)
	{
		foreach($project_act_proc_arr as $awdObjKey => $awdObj)
		{
			$data[$i]['activity_id'] = $activity_id;
			$data[$i]['award_number'] = $awdObj['award_number'];
			$data[$i]['DUNS_number'] = $awdObj['DUNS_number'];
			$data[$i]['vendor_name'] = $awdObj['vendor_name'];
			$data[$i]['obligate'] = $awdObj['obligate'];
			$data[$i]['actual_obligate'] = $awdObj['actual_obligate'];
			$data[$i]['paid'] = $awdObj['paid'];
			$data[$i]['available'] = $awdObj['available'];
			if(count($awdObj['CLINS'])>0)
			{
				$j=0;
				foreach($awdObj['CLINS'] as $key => $clinObj)
				{
					$data[$i]['clin_data'][$j]['award_number'] = $awdObj['award_number'];
					$data[$i]['clin_data'][$j]['clin_number'] = $clinObj['clin_number'];	
					$data[$i]['clin_data'][$j]['obligate'] = $clinObj['obligate'];
					$data[$i]['clin_data'][$j]['paid'] = $clinObj['paid'];
					$data[$i]['clin_data'][$j]['available'] = $clinObj['available'];
					$data[$i]['clin_data'][$j]['obligate'] = $clinObj['obligate'];	
					$j++;
				}
			}
			$i++;
		}
	}	
		
	if(count($data)>0){
		deliverResponse(200,'Record Found',$data);
	}
	else{
	   deliverResponse(200,'No Record Found',NULL);
	}  
}
else{
	 deliverResponse(200,'No Record Found',NULL);
}
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data){
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
?>