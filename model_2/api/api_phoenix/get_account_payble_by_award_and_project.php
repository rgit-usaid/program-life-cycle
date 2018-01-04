<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all award clin of a Activity ============

if(isset($_REQUEST['award_clin']) and $_REQUEST['award_clin']!='' and isset($_REQUEST['project_id']) and $_REQUEST['project_id']!=''  and isset($_REQUEST['type']) and $_REQUEST['type']!='')
{   
    $data = array();
    $project_id = trim($_REQUEST['project_id']);
	$award_id = trim($_REQUEST['award_clin']);
	$type = trim($_REQUEST['type']);
	$where_cond='';
	if($type=="award"){
		$where_cond = "WHERE acp.award_instrument_no = '".$award_id."' and acpd.project_id='".$project_id."'";
	}
	else{
		$where_cond = "WHERE acpd.clin_number = '".$award_id."' and acpd.project_id='".$project_id."'";
	}	
		
	$select_data = "select acp.award_instrument_no, acpd.project_id,  sum(acpd.invoice_paid) as total_paid_amount, 
	sum(acpd.invoice_amt) as total_invoice_amt
	FROM usaid_account_payble acp
	LEFT JOIN usaid_account_payble_detail acpd ON acpd.acc_payable_id = acp.id
	".$where_cond." group by acp.award_instrument_no, acpd.project_id";
					
	/*$select_data = "select sum(acp.total_invoice_amt) as total_paid_amount, sum(acp.total_invoice_amt) as total_invoice_amt
	FROM usaid_account_payble acp
	LEFT JOIN usaid_account_payble_detail acpd ON acpd.acc_payable_id = acp.id
	WHERE acp.award_instrument_no = '".$award_id."' and acpd.project_id='".$project_id."' group by acp.award_instrument_no, acpd.project_id";*/
    $result_data = $mysqli->query($select_data);
				
	
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
		 ## get all account payble by  Award and Project ============
		 $data['award_instrument_no'] = $fetch_data['award_instrument_no'];  
         $data['project_id'] = $fetch_data['project_id'];
         $data['total_paid_amount'] = $fetch_data['total_paid_amount'];  
         $data['total_invoice_amt'] = $fetch_data['total_invoice_amt']; 
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
