<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## mobile request data to sync============
$here = '';
if(isset($_REQUEST))
{
    ### get json string requested data==============
    $json = file_get_contents('php://input'); 
    $json_arr = json_decode($json,true); 

    ### auto commit false ===================
    $mysqli->autocommit(FALSE); 
    $insert_error = 0;
    ### code for insert/update evaluation===============
    for($i=0; $i<count($json_arr['Sync_Data']['evaluations']); $i++)
    { 
        $evaluation_id = $json_arr['Sync_Data']['evaluations'][$i]['evaluation_id']; 
        if($evaluation_id !='' and $evaluation_id>0)
        {   
            $update_data = "update usaid_project_evaluation set 
                    project_id = '".$json_arr['Sync_Data']['evaluations'][$i]['project_id']."',
                    type = '".$json_arr['Sync_Data']['evaluations'][$i]['type']."',
                    evaluation_type_description_other = '".$json_arr['Sync_Data']['evaluations'][$i]['evaluation_type_description_other']."',
                    management_type = '".$json_arr['Sync_Data']['evaluations'][$i]['management_type']."',
                    estimated_cost = '".$json_arr['Sync_Data']['evaluations'][$i]['estimated_cost']."',
                    start_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['evaluations'][$i]['start_date']))."',
                    end_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['evaluations'][$i]['end_date']))."',
                    additional_comment = '".$mysqli->real_escape_string(trim($json_arr['Sync_Data']['evaluations'][$i]['additional_comment']))."' where id='".$evaluation_id."'";       
            $result_data = $mysqli->query($update_data);
            if(!$result_data) $insert_error = 1;
        }
        else
        { 
            $insert_data = "insert into usaid_project_evaluation set 
                    project_id = '".$json_arr['Sync_Data']['evaluations'][$i]['project_id']."',
                    type = '".$json_arr['Sync_Data']['evaluations'][$i]['type']."',
                    evaluation_type_description_other = '".$json_arr['Sync_Data']['evaluations'][$i]['evaluation_type_description_other']."',
                    management_type = '".$json_arr['Sync_Data']['evaluations'][$i]['management_type']."',
                    estimated_cost = '".$json_arr['Sync_Data']['evaluations'][$i]['estimated_cost']."',
                    start_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['evaluations'][$i]['start_date']))."',
                    end_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['evaluations'][$i]['end_date']))."',
                    additional_comment = '".$mysqli->real_escape_string(trim($json_arr['Sync_Data']['evaluations'][$i]['additional_comment']))."'";
            $result_data = $mysqli->query($insert_data);
            if(!$result_data) $insert_error = 1;  
        } 
    }

    ### code for insert/update monitoring===============
    for($i=0; $i<count($json_arr['Sync_Data']['monitoring']); $i++)
    { 
        $monitoring_id = trim($json_arr['Sync_Data']['monitoring'][$i]['review_id']); 
        if($monitoring_id !='' and $monitoring_id>0)
        {   
            $update_data = "update usaid_project_monitoring set 
                    project_id = '".$json_arr['Sync_Data']['monitoring'][$i]['project_id']."',
                    review_type = '".$json_arr['Sync_Data']['monitoring'][$i]['review_type']."',
                    review_due_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['monitoring'][$i]['review_due_date']))."',
                    review_prompt_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['monitoring'][$i]['review_prompt_date']))."',
                    actual_review_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['monitoring'][$i]['actual_review_date']))."',  
                    overall_score = '".$json_arr['Sync_Data']['monitoring'][$i]['overall_score']."',
                    annual_review_submission_comments = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_submission_comments']."',
                    annual_review_approval = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_approval']."',
                    annual_review_approver = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_approver']."',
                    annual_review_approver_comments = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_approver_comments']."'
                         where id='".$monitoring_id."'";       
            $result_data = $mysqli->query($update_data);
            if(!$result_data) $insert_error = 1;
            else{
                ### insert output scoring==============
                $output_arr = $json_arr['Sync_Data']['monitoring'][$i]['output_scoring'];
                $output_id_arr = array();
                for($j=0; $j<count($output_arr); $j++)
                {   
                    if($output_arr[$j]['output_scoring_id']!='' and $output_arr[$j]['output_scoring_id']!=NULL)
                    {
                        $output_id_arr[] = $output_arr[$j]['output_scoring_id']; // output scoring id
                    }   
                } 
                ### delete output scoring if delete from app======
                $implode_str = implode(",", $output_id_arr);
                $delete_output = "delete from usaid_project_monitoring_output_score where id not IN(".$implode_str.") and project_monitoring_id='".$monitoring_id."'";
                $result_delete = $mysqli->query($delete_output);
                if(!$result_delete)$insert_error = 1; 

                ### insert new added data===============
                for($j=0; $j<count($output_arr); $j++)
                {
                    if($output_arr[$j]['output_scoring_id']!='' and $output_arr[$j]['output_scoring_id']!=NULL)
                    { 
                        $update_data = "update usaid_project_monitoring_output_score set
                        project_output_score_description = '".$output_arr[$j]['output_score_description']."',
                        project_output_impact_weight = '".$output_arr[$j]['output_impact_weight']."',
                        project_output_performance = '".$output_arr[$j]['output_performance']."',
                        project_output_risk = '".$output_arr[$j]['output_risk']."' where id='".$output_arr[$j]['output_scoring_id']."'";
                        $result_output = $mysqli->query($update_data);
                        if(!$result_output)$insert_error = 1;      
                    }
                    else
                    {
                        $insert_new_data = "insert into usaid_project_monitoring_output_score set
                        project_monitoring_id = '".$monitoring_id."',
                        project_output_score_description = '".$output_arr[$j]['output_score_description']."',
                        project_output_impact_weight = '".$output_arr[$j]['output_impact_weight']."',
                        project_output_performance = '".$output_arr[$j]['output_performance']."',
                        project_output_risk = '".$output_arr[$j]['output_risk']."'"; 
                        $result_output_new = $mysqli->query($insert_new_data); 
                        if(!$result_output_new)$insert_error = 1;  
                    } 
                }  
            }  
        }
        else
        { 
            $insert_data = "insert into usaid_project_monitoring set 
                    project_id = '".$json_arr['Sync_Data']['monitoring'][$i]['project_id']."',
                    review_type = '".$json_arr['Sync_Data']['monitoring'][$i]['review_type']."',
                    review_due_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['monitoring'][$i]['review_due_date']))."',
                    review_prompt_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['monitoring'][$i]['review_prompt_date']))."',
                    actual_review_date = '".date('Y-m-d',strtotime($json_arr['Sync_Data']['monitoring'][$i]['actual_review_date']))."',  
                    overall_score = '".$json_arr['Sync_Data']['monitoring'][$i]['overall_score']."',
                    annual_review_submission_comments = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_submission_comments']."',
                    annual_review_approval = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_approval']."',
                    annual_review_approver = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_approver']."',
                    annual_review_approver_comments = '".$json_arr['Sync_Data']['monitoring'][$i]['annual_review_approver_comments']."'";
            $result_data = $mysqli->query($insert_data);
            if(!$result_data) $insert_error = 1;
            else
            {
                $monitoring_id = $mysqli->insert_id;
                ### insert output scoring==============
                $output_arr = $json_arr['Sync_Data']['monitoring'][$i]['output_scoring'];
                for($j=0; $j<count($output_arr); $j++)
                {
                    $insert_data = "insert into usaid_project_monitoring_output_score set
                        project_monitoring_id = '".$monitoring_id."',
                        project_output_score_description = '".$output_arr[$j]['output_score_description']."',
                        project_output_impact_weight = '".$output_arr[$j]['output_impact_weight']."',
                        project_output_performance = '".$output_arr[$j]['output_performance']."',
                        project_output_risk = '".$output_arr[$j]['output_risk']."'";
                    $result_output = $mysqli->query($insert_data);
                    if(!$result_output)$insert_error = 1;    
                }
            }  
        } 
    }
    
    #### test insert==========
    $insert = $mysqli->query("insert into test set test_data='".$json."', description_logic='".$implode_str."', arr_obj_data='".$insert_error."'"); 
   ### if error found then rollback ===========
    if($insert_error > 0){
        $mysqli->rollback();
        $data['response'] = "Data sync failed";
    }
    else{
        $mysqli->commit();
         $data['response'] = "Data sync successfully";
    }  
    deliverResponse(200,'Record Found',$data);
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
 
?>