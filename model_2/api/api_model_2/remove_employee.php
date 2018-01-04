<?php
include("config/config.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *"); 
##request for delete project==============
if(isset($_REQUEST['employee_id']) and $_REQUEST['employee_id']!='')
{
    $employee_id = trim($_REQUEST['employee_id']);
    if($employee_id!='')
    {
        global $mysqli;
        $remove_employee = "delete from usaid_employee where employee_id='".$employee_id."'";
        $result_remove = $mysqli->query($remove_employee);
        if($result_remove)
        {
            deliverResponse(200,'Remove Successfuly');
        }
        else
        {
            deliverResponse(400,'Invalid Request',NULL);
        } 
    }
    else
    { 
        deliverResponse(400,'Invalid Request',NULL);
    }
}
 

###function for deliver reponse on request===================
function deliverResponse($status,$status_msg)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $json_response = json_encode($response);
    echo $json_response;
}

?>