<?php include('config/functions.inc.php');
##==validate user====
validate_user();
global $mysqli;
###request for get single project details using project id ===========
$project_id = '';

if(isset($_REQUEST['details']))
{
	$project_id = trim($_REQUEST['project_id']);
}

if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];	
}

if($project_id!="")
{
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
	$project_arr = requestByCURL($url);

    $url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url);
	$project_owner_id = $project_arr['data']['employee_id']; 
	
	$empinfo_url = API_HOST_URL_PROJECT."get_hr_employee.php?employee_id=".$project_owner_id;  
	$empinfo_arr = requestByCURL($empinfo_url);
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/project_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">View Procurement</div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project end-->
			<div class="project-detail-blk table-container">
				<header><h2 class="form-blk-head">Current Purchase Orders</h2></header>
				<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Award/Clin ID</th>
						<th class="text-center comm-width">Vendor ID</th>
						<th>Vendor Name</th>
						<th class="text-right comm-width">Obligated</th>
						<th class="text-right comm-width">Paid</th>
						<th class="text-right comm-width">Available</th>
					</tr>
					</thead>
					<tbody>
						<?php 
							 $data_row_counter= 0;
							 /*get all awards of projects*/
							 $url = API_HOST_URL_GLAAS."get_award_by_project.php?project_id=".$project_id;  
  							 $project_aws_arr = requestByCURL($url);
							 
							 if(count($project_aws_arr['data'])>0){
							 	$data_row_counter = 1;
							 }
								
							 /*get all project activity*/
							 $url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
  							 $project_activity_arr = requestByCURL($url);   
  							
							/*get all award clin of projects*/
							 $url = API_HOST_URL_GLAAS."get_award_clin_by_project.php?project_id=".$project_id;  
  							 $project_clin_arr = requestByCURL($url);
							 
							 if(count($project_clin_arr['data'])>0){
							 	$data_row_counter = 1;
							 }
							 	
							/*get all awards of projects activity*/
							$project_act_awd_arr= array(); $project_act_clin_arr= array();
							 foreach($project_activity_arr['data'] as $key => $act_obj){
								 //get all award clin of projects//
							 	$url = API_HOST_URL_GLAAS."get_award_by_activity.php?activity_id=".$act_obj['activity_id'];  
  							 	$projectActProc_arr = requestByCURL($url); 
								foreach($projectActProc_arr['data'] as $key => $awdObj){
									 $project_act_awd_arr[$awdObj['award_number']]['award_number']=  $awdObj['award_number'];
									 $project_act_awd_arr[$awdObj['award_number']]['DUNS_number']=  $awdObj['DUNS_number'];
									 $project_act_awd_arr[$awdObj['award_number']]['name']=  $awdObj['name'];
									 $obligate = $awdObj['amount'];  
									 $project_act_awd_arr[$awdObj['award_number']]['obligate']=  $obligate;
									 
									 $obligate = $project_act_awd_arr[$awdObj['award_number']]['obligate'];
								}
								if(count($projectActProc_arr['data'])>0){
							 		$data_row_counter = 1;
								}
							 }
							 
							 
							 /*loop in all awards of projects*/
							 foreach($project_aws_arr['data'] as $key => $awdObj){
							 
							 /*get account payable amount*/
							 $url = API_HOST_URL_PHOENIX."get_account_payble_by_award_and_project.php?project_id=".$project_id."&award_clin=".$awdObj['award_number']."&type=award";  
  							 $account_payble_amt_arr = requestByCURL($url);
							 $obligate = $awdObj['amount'];  
							 $paid = $account_payble_amt_arr['data']['total_paid_amount'];
							 $avail = $obligate - $paid;
						?>
						<tr>
							<td><?php echo $awdObj['award_number'];?></td>
							<td class="text-center comm-width"><?php echo $awdObj['DUNS_number'];?></td>
							<td><?php echo $awdObj['name'];?></td>
							<td class="text-right">$<?php echo number_format($obligate);?></td>
							<td class="text-right">$<?php echo number_format($paid);?></td>
							<td class="text-right">$<?php echo number_format($avail);?></td>
						</tr>
						<?php }			
							 /*loop in all awards of project*/
							 foreach($project_clin_arr['data'] as $key => $awdObj){
							 
							 /*get account payable amount*/
							 $url = API_HOST_URL_PHOENIX."get_account_payble_by_award_and_project.php?project_id=".$project_id."&award_clin=".$awdObj['clin_number']."&type=clin";  
  							 $account_payble_amt_arr = requestByCURL($url);
							 $obligate = $awdObj['amount'];  
							 $paid = $account_payble_amt_arr['data']['total_paid_amount'];
							 $avail = $obligate - $paid;
						?>
						<tr>
							<td><?php echo $awdObj['clin_number'];?></td>
							<td class="text-center comm-width"><?php echo $awdObj['DUNS_number'];?></td>
							<td><?php echo $awdObj['name'];?></td>
							<td class="text-right">$<?php echo number_format($obligate);?></td>
							<td class="text-right">$<?php echo number_format($paid);?></td>
							<td class="text-right">$<?php echo number_format($avail);?></td>
						</tr>
						<?php } 
						 /*loop in all award clins of project activity*/
						  foreach($project_act_clin_arr as $key => $awdObj){	
							  /*get account payable amount*/
							 $url = API_HOST_URL_PHOENIX."get_account_payble_by_award_and_project.php?project_id=".$project_id."&award_clin=".$awdObj['clin_number']."&type=clin";  
							 $account_payble_amt_arr = requestByCURL($url);
							 $obligate = $awdObj['obligate'];  
							 $paid = $account_payble_amt_arr['data']['total_paid_amount'];
							 $avail = $obligate - $paid;
						?>
						<tr>
							<td><?php echo $awdObj['clin_number'];?></td>
							<td class="text-center comm-width"><?php echo $awdObj['DUNS_number'];?></td>
							<td><?php echo $awdObj['name'];?></td>
							<td class="text-right">$<?php echo number_format($obligate);?></td>
							<td class="text-right">$<?php echo number_format($paid);?></td>
							<td class="text-right">$<?php echo number_format($avail);?></td>
						</tr>
						<?php } if($data_row_counter==0){?>
						<tr>
							<td colspan="6" class="text-danger bold" style="font-size:12px">No data Found</td>
						</tr>
						<?php }?>	
					</tbody>
				</table>
			</div>
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
