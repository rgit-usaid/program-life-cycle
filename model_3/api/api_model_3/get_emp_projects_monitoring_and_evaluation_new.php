<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['employee_id']))
{
    $data = array();
    $employee_id = $mysqli->real_escape_string(trim($_REQUEST['employee_id']));
    $select_project = "select p.*,ps.stage_name ,ps.stage_percentage
                        from usaid_project as p
                        left join usaid_project_stage as ps ON ps.stage_id = p.project_stage_id
                        group by p.project_id";
    $result_project = $mysqli->query($select_project);
    $i=0; 
    while($fetch_project = $result_project->fetch_array())
    {
        $data[$i]['project_id'] = $fetch_project['project_id']; 
        $data[$i]['title'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['title']));
        $data[$i]['project_purpose'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['project_purpose']));
        $data[$i]['estimated_total_funding_amount'] = $fetch_project['estimated_total_funding_amount'];
        $data[$i]['originating_operating_unit_id'] = $fetch_project['originating_operating_unit_id'];
        $data[$i]['originating_operating_unit_desc'] = get_ou_details_by_id($fetch_project['originating_operating_unit_id']); // call function
        $data[$i]['implementing_operating_unit_id'] = $fetch_project['implementing_operating_unit_id'];
        $data[$i]['implementing_operating_unit_desc'] =  get_ou_details_by_id($fetch_project['implementing_operating_unit_id']); //call function
        $data[$i]['use_of_govt_to_govt_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['use_of_govt_to_govt_plan']));  
        $data[$i]['conducting_analyses_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['conducting_analyses_plan']));
        $data[$i]['proposed_design_schedule'] = $fetch_project['proposed_design_schedule'];  
        $data[$i]['proposed_design_cost'] = $fetch_project['proposed_design_cost'];
        $data[$i]['project_stage_id'] = $fetch_project['project_stage_id'];
        $data[$i]['stage_percentage'] = $fetch_project['stage_percentage'];
        $data[$i]['stage_name'] = $fetch_project['stage_name'];
        $data[$i]['project_description'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['project_description']));
        $data[$i]['engaging_local_actor_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['engaging_local_actor_plan'])); 
        $data[$i]['context'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['context']));
        $data[$i]['leveraged_resources'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['leveraged_resources']));
        $data[$i]['conclusions_and_analyses_summary'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['conclusions_and_analyses_summary']));
        $data[$i]['management_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['management_plan']));
        $data[$i]['financial_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['financial_plan']));
        $data[$i]['monitoring_evaluation_and_learning_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['monitoring_evaluation_and_learning_plan']));
        $data[$i]['activity_plan'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['activity_plan']));
        $data[$i]['logical_framework_discretion'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['logical_framework_discretion']));
       
        $data[$i]['planned_start_date'] ="";
        if($fetch_project['planned_start_date']!=""){
            $data[$i]['planned_start_date'] = dateFormat($fetch_project['planned_start_date']);
        }
        $data[$i]['planned_end_date'] = "";
        if($fetch_project['planned_end_date']!=""){
            $data[$i]['planned_end_date'] = dateFormat($fetch_project['planned_end_date']);
        }
        $data[$i]['next_review_date'] ="";
        if($fetch_project['next_review_date']!=""){
            $data[$i]['next_review_date'] = dateFormat($fetch_project['next_review_date']);
        } 
 	    
        $data[$i]['evaluations'] = getProjectEvaluation($fetch_project['project_id']); //function for get all evalution
        $data[$i]['monitorings'] = getProjectMonitoring($fetch_project['project_id']); //function for get all monitoring
        $data[$i]['activities'] = get_project_activity($fetch_project['project_id']); //function for gey all activity
        $i++; 
    }
     
    if(count($data)>0){
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
}
else{
     deliverResponse(200,'Invalid Request',NULL);
}    
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}

## fundtion for get evaluations of a project===
function getProjectEvaluation($project_id)
{
    global $mysqli;
    $data = array();       
    $select_project_evaluation = "select * from usaid_project_evaluation where project_id = '".$project_id."' ORDER BY start_date";
    $result_project_evaluation = $mysqli->query($select_project_evaluation);
    if($result_project_evaluation->num_rows>0)
    {   
        $i=0;
        while($fetch_project_evaluation = $result_project_evaluation->fetch_array())
        {
            $data[$i]['evaluation_id'] = $fetch_project_evaluation['id'];
            $data[$i]['project_id'] = $fetch_project_evaluation['project_id']; 
            $data[$i]['type'] = $fetch_project_evaluation['type'];
            $data[$i]['evaluation_type_description_other'] = $fetch_project_evaluation['evaluation_type_description_other'];
            $data[$i]['management_type'] = $fetch_project_evaluation['management_type'];
            $data[$i]['estimated_cost'] = $fetch_project_evaluation['estimated_cost'];
            $data[$i]['start_date'] = $fetch_project_evaluation['start_date'];
            $data[$i]['end_date'] = $fetch_project_evaluation['end_date'];
            $data[$i]['additional_comment'] = $fetch_project_evaluation['additional_comment'];
            $i++;
        }
        return $data;
    }
}
 
## fundtion for get monitoring of a project===
function getProjectMonitoring($project_id)
{
     global $mysqli;
    $select_monitoring = "select * from usaid_project_monitoring where project_id = '".$project_id."' ORDER BY review_due_date";
    $result_monitoring = $mysqli->query($select_monitoring);
    if($result_monitoring->num_rows>0)
    {   
        $i=0;
        while($fetch_monitoring = $result_monitoring->fetch_array())
        {
            $data[$i]['review_id'] = $fetch_monitoring['id'];
            $data[$i]['project_id'] = $fetch_monitoring['project_id']; 
            $data[$i]['review_type'] = $fetch_monitoring['review_type'];
            $data[$i]['review_due_date'] = $fetch_monitoring['review_due_date'];
            $data[$i]['review_prompt_date'] = $fetch_monitoring['review_prompt_date'];
            $data[$i]['actual_review_date'] = $fetch_monitoring['actual_review_date'];
            $data[$i]['overall_score'] = $fetch_monitoring['overall_score'];
            $data[$i]['annual_review_submission_comments'] = $fetch_monitoring['annual_review_submission_comments'];
            $data[$i]['annual_review_approval'] = $fetch_monitoring['annual_review_approval'];
            $data[$i]['annual_review_approver'] = $fetch_monitoring['annual_review_approver'];
            $data[$i]['annual_review_approver_comments'] = $fetch_monitoring['annual_review_approver_comments'];
            $data[$i]['output_scoring'] = get_monitoring_output_scoring($fetch_monitoring['id']); 
            $i++;
        }
        return $data;
    }
}

## functiona for get out put scoring for monitoring==========
function get_monitoring_output_scoring($review_id)
{
    global $mysqli;
    $select_data = "select * from usaid_project_monitoring_output_score where project_monitoring_id = '".$review_id."'";
    $result_data = $mysqli->query($select_data);
    if($result_data->num_rows>0)
    {   
        $i=0;
        while($fetch_data = $result_data->fetch_array())
        {
            $data[$i]['output_scoring_id'] = $fetch_data['id'];
            $data[$i]['monitoring_id'] = $fetch_data['project_monitoring_id']; 
            $data[$i]['output_score_description'] = $fetch_data['project_output_score_description'];
            $data[$i]['output_impact_weight'] = $fetch_data['project_output_impact_weight'];
            $data[$i]['output_performance'] = $fetch_data['project_output_performance'];
            $data[$i]['output_risk'] = $fetch_data['project_output_risk'];
            $i++;
        }
        return $data;
    }
}

## function for get project activities ==========
function get_project_activity($project_id)
{
    global $mysqli;
    $select_data = "select * from usaid_project_activity where project_id = '".$project_id."' and status='Active'";
    $result_data = $mysqli->query($select_data);
    if($result_data->num_rows>0)
    {   
        $i=0;
        while($fetch_data = $result_data->fetch_array())
        {
            $data[$i]['activity_id'] = $fetch_data['activity_id'];
            $data[$i]['project_id'] = $fetch_data['project_id'];
            $data[$i]['title'] = $fetch_data['title'];
            $data[$i]['description'] = $fetch_data['activity_description'];  
            $data[$i]['benefitting_country'] = $fetch_data['activity_benefitting_country'];  
            $data[$i]['planned_start_date'] = $fetch_data['planned_start_date'];
            $data[$i]['planned_end_date'] = $fetch_data['planned_end_date'];
            $data[$i]['actual_start_date'] = $fetch_data['actual_start_date'];
            $data[$i]['actual_end_date'] = $fetch_data['actual_end_date'];
            $data[$i]['employee_id'] = $fetch_data['employee_id']; 

	        $data[$i]['awards'] = get_awards_for_activity($fetch_data['activity_id']); //function for get all evalution
			$data[$i]['clin'] = get_clins_for_activity($fetch_data['activity_id']); //function for get all evalution
		
		    $data[$i]['lifetime_budget'] = '600000'; //function for get all evalution
		    $data[$i]['lifetime_actual_spent'] = '200000'; //function for get all evalution
		    $data[$i]['lifetime_forecast'] = '300000'; //function for get all evalution

			//data for graphs
		    $data[$i]['graph_monthly'] = get_monthly_graph_data_for_activity; //function to get monthly graph data
			
            $i++;
		
		}

		return $data;
    }
}

function get_monthly_graph_data_for_activity($activity_id)
{
	$data['spent'] = get_monthly_spent_for_activity($activity_id);
	$data['forecast'] = get_monthly_forecast_for_activity($activity_id);
	$data['budget'] = get_monthly_budget_for_activity($activity_id);
}

//graph functions
function get_monthly_spent_for_activity($activity_id)
{
		$data['period'] = 'Oct 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Nov 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Dec 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Jan 18';
		$data['value'] = rand(1,10) * 10000;
}


function get_monthly_forecast_for_activity($activity_id)
{
		$data['period'] = 'Oct 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Nov 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Dec 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Jan 18';
		$data['value'] = rand(1,10) * 10000;
}

function get_monthly_budget_for_activity($activity_id)
{
		$data['period'] = 'Oct 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Nov 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Dec 17';
		$data['value'] = rand(1,10) * 10000;

		$data['period'] = 'Jan 18';
		$data['value'] = rand(1,10) * 10000;
}


function get_awards_for_activity($activity_id)
{
	$award = array();

	$url = "http://rgdemo.com/usaid/api-glaas3/get_award_by_activity.php?activity_id=".$activity_id;
	//$projectActProc_arr = requestByCURL($url);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $award_arr = json_decode($output,true); 
	 
	for($i=0;$i<count($award_arr['data']);$i++){
		$award[$i]['award_number'] = $award_arr['data'][$i]['award_number'];
		$award[$i]['id'] = $award_arr['data'][$i]['award_id'];
		$award[$i]['vendor_name'] = $award_arr['data'][$i]['name'];
		$award[$i]['DUNS_number'] = $award_arr['data'][$i]['DUNS_number'];
		$award[$i]['obligate'] = $award_arr['data'][$i]['amount']; 	
	    $award[$i]['actual_obligate'] = $award_arr['data'][$i]['amount']; 	
		$award[$i]['paid'] = 0;
		$award[$i]['available'] = $award_arr[$award_number]['obligate'] - $award_arr[$award_number]['paid'];
	}
	return $award;
}

function get_clins_for_activity($activity_id)
{
	$clin = array();

	$url = "http://rgdemo.com/usaid/api-glaas3/get_award_by_activity.php?activity_id=".$activity_id;
	//$projectActProc_arr = requestByCURL($url);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $clin_arr = json_decode($output,true); 
	 
	for($i=0;$i<count($clin_arr['data']);$i++){
		$clin[$i]['award_number'] = $clin_arr['data'][$i]['award_number'];
		$clin[$i]['id'] = $clin_arr['data'][$i]['award_id'];
		$clin[$i]['vendor_name'] = $clin_arr['data'][$i]['name'];
		$clin[$i]['DUNS_number'] = $clin_arr['data'][$i]['DUNS_number'];
		$clin[$i]['obligate'] = $clin_arr['data'][$i]['amount']; 	
	    $clin[$i]['actual_obligate'] = $clin_arr['data'][$i]['amount']; 	
		$clin[$i]['paid'] = 0;
		$clin[$i]['available'] = $clin_arr[$award_number]['obligate'] - $clin_arr[$award_number]['paid'];
	}
	return $clin;
}


## function for get OU description by OU id
function get_ou_details_by_id($operating_unit_id)
{ 
    ## call the api using CURL ===========
    $url = "http://rgdemo.com/usaid/api-phoenix3/get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id."";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $data_arr = json_decode($output,true);
    return $data_arr['data']['operating_unit_description'];
}
 
?>