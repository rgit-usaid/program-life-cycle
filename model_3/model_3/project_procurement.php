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
	
	/*get all project activity*/
	$url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
	$project_activity_arr = requestByCURL($url);   
	
	/*get all awards of projects activity*/
	$project_act_proc_arr= array(); 
	
	for($j=0;$j<count($project_activity_arr['data']);$j++){
		$activity_id = $project_activity_arr['data'][$j]['activity_id'];
		
		//get all award of projects activity/
		$url = API_HOST_URL_GLAAS."get_award_by_activity.php?activity_id=".$activity_id;  
		$projectActProc_arr = requestByCURL($url); 
		for($i=0;$i<count($projectActProc_arr['data']);$i++){
			$award_number = $projectActProc_arr['data'][$i]['award_number'];
			$project_act_proc_arr[$activity_id][$award_number]['award_number'] = $projectActProc_arr['data'][$i]['award_number'];
			$project_act_proc_arr[$activity_id][$award_number]['id'] = $projectActProc_arr['data'][$i]['award_id'];
			$project_act_proc_arr[$activity_id][$award_number]['vendor_name'] = $projectActProc_arr['data'][$i]['name'];
			$project_act_proc_arr[$activity_id][$award_number]['DUNS_number'] = $projectActProc_arr['data'][$i]['DUNS_number'];
			$project_act_proc_arr[$activity_id][$award_number]['obligate'] = $projectActProc_arr['data'][$i]['amount'];
			$project_act_proc_arr[$activity_id][$award_number]['actual_obligate'] = $projectActProc_arr['data'][$i]['amount']; 	
			$project_act_proc_arr[$activity_id][$award_number]['paid'] = 0;
			$project_act_proc_arr[$activity_id][$award_number]['available'] = $project_act_proc_arr[$activity_id][$award_number]['obligate'] - $project_act_proc_arr[$activity_id][$award_number]['paid'];
		}
		
		//get all award clin of projects//
		$url = API_HOST_URL_GLAAS."get_award_clin_by_activity.php?activity_id=".$activity_id;  
		$projectActProc_arr = requestByCURL($url); 
		for($i=0;$i<count($projectActProc_arr['data']);$i++){
			$award_number = $projectActProc_arr['data'][$i]['award_number'];
			$clin_number = $projectActProc_arr['data'][$i]['clin_number'];
			if(!array_key_exists($award_number,$project_act_proc_arr[$activity_id])){
				$project_act_proc_arr[$activity_id][$clin_number]['award_number'] = $projectActProc_arr['data'][$i]['clin_number'];
				$project_act_proc_arr[$activity_id][$clin_number]['id'] = $projectActProc_arr['data'][$i]['clin_id'];
				$project_act_proc_arr[$activity_id][$clin_number]['vendor_name'] = $projectActProc_arr['data'][$i]['name'];
				$project_act_proc_arr[$activity_id][$clin_number]['DUNS_number'] = $projectActProc_arr['data'][$i]['DUNS_number'];
				$project_act_proc_arr[$activity_id][$clin_number]['obligate'] = $projectActProc_arr['data'][$i]['amount']; 
				
				//get total paid amount on award clin
				$url = API_HOST_URL_PHOENIX."get_account_payble_by_clin.php?clin_no=".$clin_number;  
				$account_payble_amt_arr = requestByCURL($url);
				if(count($account_payble_amt_arr['data'])>0){
					$project_act_proc_arr[$activity_id][$clin_number]['paid'] = $account_payble_amt_arr['data']['total_paid_amount'];
				}
				else{
					$project_act_proc_arr[$activity_id][$clin_number]['paid'] = 0;
				}
				
				$project_act_proc_arr[$activity_id][$clin_number]['available'] = $project_act_proc_arr[$activity_id][$clin_number]['obligate'] - $project_act_proc_arr[$activity_id][$clin_number]['paid'];
			}
			else{
				$project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['clin_number'] = $clin_number;
				$project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['obligate'] = $projectActProc_arr['data'][$i]['amount']; 
				
				//get total paid amount on award clin
				$url = API_HOST_URL_PHOENIX."get_account_payble_by_clin.php?clin_no=".$clin_number;  
				$account_payble_amt_arr = requestByCURL($url);
				if(count($account_payble_amt_arr['data'])>0){
					$project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['paid'] = $account_payble_amt_arr['data']['total_paid_amount'];			
				}
				else{
					$project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['paid'] = 0;
				}
				$project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['available'] = $project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['obligate'] - $project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['paid'];
				
				$project_act_proc_arr[$activity_id][$award_number]['obligate'] = $project_act_proc_arr[$activity_id][$award_number]['obligate'] + $project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['obligate'];
				$project_act_proc_arr[$activity_id][$award_number]['paid'] = $project_act_proc_arr[$activity_id][$award_number]['paid'] + $project_act_proc_arr[$activity_id][$award_number]['CLINS'][$clin_number]['paid'];
				$project_act_proc_arr[$activity_id][$award_number]['available'] = $project_act_proc_arr[$activity_id][$award_number]['obligate'] - $project_act_proc_arr[$activity_id][$award_number]['paid'];	 	
			}
		}
	}
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
			<div class="extra_ht"></div><div class="extra_ht"></div>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">View Procurement</div>
				<div class="clear"></div>
			</div>
			<!--add new project end-->
			<div class="project-detail-blk table-container">
				<header><h2 class="form-blk-head">Current Purchase Orders</h2></header>
				<div class="extra_ht"></div>
				<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Award ID</th>
						<th class="text-center comm-width">Vendor ID</th>
						<th>Vendor Name</th>
						<th class="text-center comm-width">Activity ID</th>
						<th class="text-center comm-width">Obligated</th>
						<th class="text-center">Paid</th>
						<th class="text-center comm-width">Available</th>
						<th class="text-center"></th>
					</tr>
					</thead>
					<tbody>
						<?php 
						if(count($project_act_proc_arr)>0){
						foreach($project_act_proc_arr as $actKey => $actObj){
						foreach($actObj as $awdObjKey => $awdObj){?>
						<tr>
							<td><?php echo $awdObj['award_number'];?></td>
							<td class="text-center comm-width"><?php echo $awdObj['DUNS_number'];?></td>
							<td><?php echo $awdObj['vendor_name'];?></td>
							<td class="text-center"><?php echo $actKey;?></td>
							<td class="text-right">
								<div title="Total Obligate" data-toggle="tooltip" data-placement="right">
									<?php echo priceFormat($awdObj['obligate']);?>
								</div>
								<div title="Obligated only on Award" data-toggle="tooltip" style="border-top:solid 1px #000;" class="text-danger" data-placement="right">
									<?php echo priceFormat($awdObj['actual_obligate']);?>
								</div>
							</td>
							<td class="text-right"><?php echo priceFormat($awdObj['paid']);?></td>
							<td class="text-right"><?php echo priceFormat($awdObj['available']);?></td>
							<td class="text-center"><?php if(count($awdObj['CLINS'])>0){?><i class="fa fa-chevron-circle-down show_hide_btn pointer"></i><?php }?></td>
						</tr>
						<?php if(count($awdObj['CLINS'])>0){?>
						<tr class="clin_table disp-none">
						    <td colspan="8">
								<table class="table table-bordered table-striped" width="100%" cellspacing="0">
									<tr class="bold">
										<th>CLIN ID</th>
										<th class="text-center comm-width">Obligated</th>
										<th class="text-center comm-width">Paid</th>
										<th class="text-center comm-width">Available</th>
									</tr>
									<?php foreach($awdObj['CLINS'] as $key => $clinObj){?>
									<tr>
										<td><?php echo $clinObj['clin_number'];?></td>
										<td class="text-right"><?php echo priceFormat($clinObj['obligate']);?></td>
										<td class="text-right"><?php echo priceFormat($clinObj['paid']);?></td>
										<td class="text-right"><?php echo priceFormat($clinObj['available']);?></td>
									</tr>
									<?php }?>
								</table>
							</td>
						</tr>
						<?php }?>
						<?php }}} else {?>
							<tr><td colspan="7" class="bold text-danger text-left" style="font-size:14px">No data found</td></tr>
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
<script>
$(document).ready(function(){
	$('.show_hide_btn').click(function() {
		$(this).closest("tr").next('.clin_table').toggleClass("disp-none");
    	if ($(this).hasClass('fa-chevron-circle-down')){
        	$(this).removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
    	}
	 	else {
        	 $(this).addClass('fa-chevron-circle-down').removeClass('fa-chevron-circle-up');
      	}
	});
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
</body>
</html>
