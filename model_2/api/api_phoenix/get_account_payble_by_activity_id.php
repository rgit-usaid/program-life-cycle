<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all award clin of a Activity ============

if(isset($_REQUEST['activity_id']) and $_REQUEST['activity_id']!='')
{   
    $data = array();
    $activity_id = trim($_REQUEST['activity_id']);
	$type = trim($_REQUEST['type']);
	$where_cond = "WHERE acpd.activity_id='".$activity_id."'";
	
	$select_data = "select sum(acpd.invoice_paid) as total_paid_amount, DATE_FORMAT(acp.invoice_date,'%Y') as invoice_date, 
	sum(acpd.invoice_amt) as total_invoice_amt
	FROM usaid_account_payble acp
	LEFT JOIN usaid_account_payble_detail acpd ON acpd.acc_payable_id = acp.id
	WHERE acpd.activity_id='".$activity_id."' group by acpd.activity_id, DATE_FORMAT(acp.invoice_date,'%Y')";
				
    $result_data = $mysqli->query($select_data);
			
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
		 ## get all account payble by  Award and Project ============
         $data[$i]['total_paid_amount'] = $fetch_data['total_paid_amount'];  
         $data[$i]['total_invoice_amt'] = $fetch_data['total_invoice_amt'];
		 $data[$i]['invoice_date'] = $fetch_data['invoice_date']; 
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
}?>
