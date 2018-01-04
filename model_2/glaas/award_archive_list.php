<?php
include('config/config.inc.php');
include('include/function.inc.php');
if(isset($_SESSION['requisition_number_award']))
{
	$requisition_number = $_SESSION['requisition_number_award'];
	$flag = 1;
}
## Archive award requisition id for get archive details================
if($_REQUEST['archive_requisition_award_id']!='')
{
	$archive_requisition_award_id = $_REQUEST['archive_requisition_award_id'];
	$_SESSION['archive_requisition_award_id'] = $archive_requisition_award_id;
}
else
{
	$archive_requisition_award_id = $_SESSION['archive_requisition_award_id'];
}
	$url = API_HOST_URL."get_all_archive_award_by_requisition_award_id.php?requisition_award_id=".$archive_requisition_award_id;
	$archive_award_req_arr = requestByCURL($url);
	
?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
	<!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Created By Abhilash">
	<title>USAID - GLAAS</title>
	
    <!-- CSS
    ================================================== -->
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" type="text/css" rel="stylesheet">
    <style>
 .remove_link_to_blk{
    		padding: 1px 6px;
    	}
#manage-vendor .new-form-content .manage-info {
    padding: 10px !important;
}
.btnstyle{
padding-top:20px;
}
.tablegap {
    margin: 10px;
    border: 1px solid gray;
    padding: 5px;
}
.pointer{
cursor:pointer;}
    </style>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/append.js"></script>
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.html'; ?>

	<div class="top-menu">
		<ul class="list-inline">
			<li><a href="vendor_management.php">Vendor Management</a></li>
			<li><a href="requisition.php">Requisition for Supplies or Services</a></li>
			<li><a class="menu-active" href="award_instrument.php">Award / Modifications of Instrument</a></li>
		</ul>
	</div>
	<div class="container-fluid" id="form-requisiton">
	    <div class="row">
			<div class="col-sm-offset-4 col-sm-4 col-xs-12 text-center">
			 <h2><?php echo $archive_award_req_arr['data'][0]['award_name']; ?></h2>
			</div>
			 <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-right btnstyle">
				<a href="award_instrument.php"><button type="button" class="btn btn-primary back_button">Back to Award</button></a>
			 </div>
		 </div>
 		 <table id="projects_table " class="display table table-bordered table-striped " cellspacing="0" width="100%">
					<thead>
					<tr>
						<th class="text-center comm-width">Archive On</th>
						<th class="text-center comm-width">View</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if(count($archive_award_req_arr['data'])>0)
					{
						for($k=0; $k<count($archive_award_req_arr['data']); $k++)
						{  ?>
						<tr>
							<td class="text-center"><?php echo dateTimeFormat($archive_award_req_arr['data'][$k]['archive_on']);  ?></td>
							<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>												
						<tr class="disp-none">
							<td colspan="3">
								 <div class="tablegap">
										<div class="container-fluid" id="form-requisiton">
											  <div class="row" style="padding-top: 10px;">
				<div class="col-md-offset-3 col-md-6">
					<label for="" class="col-md-5 control-label"><h4>Requisition Number:</h4></label>
					<div class="col-md-6">						
						<form action="" method="post" name="requistion_from">	
							<select class="form-control" name="requisition_number" disabled="disabled">
								<option selected="selected"><?php echo $archive_award_req_arr['data'][$k]['requisition_number']; ?></option>
							</select>
						</form>
					</div>
				</div>			
			</div>
											 <div class="row award" style="display: block;">
												  <form class="form-horizontal" role="form" method="post" action="">				
													<div class="col-md-6">
													<div class="container-fluid" id="manage-vendor">
														<div class="form-title" style="width:145px;">
															<i class="fa fa-minus" aria-hidden="true"></i>Award
														</div>
														<div class="new-form-content">
															<div class="manage-info">
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award Instrument Number:</label>
																	<div class="col-md-6">						
																			
																		<input type="text" class="form-control" name="award_number" value="<?php echo $archive_award_req_arr['data'][$k]['award_number']; ?>" readonly="">
																	</div>
																</div>
								
																<div class="form-group">
																	<label for="status" class="col-md-6 control-label">Award Implementing Mechanism Type:</label>
																	<div class="col-md-6">						
																	<select class="selectpicker" data-width="100%" disabled="disabled" > 
																			<option value="Host Country Grants" selected="selected" readonly=""><?php echo $archive_award_req_arr['data'][$k]['implementing_mechanism_type']; ?></option>
																		</select></div>							
																	</div>
																							
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Vendor:</label>
																	<div class="col-md-6">	
																		<select class="form-control" disabled="disabled">
																				<option value="19" selected="selected" readonly=""> <?php echo $archive_award_req_arr['data'][$k]['vendor_name']; ?></option>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award Name:</label>
																	<div class="col-md-6">						
																		<input type="text" class="form-control" name="award_name" value="<?php echo $archive_award_req_arr['data'][$k]['award_name']; ?>" maxlength="128" readonly="">
																	</div>
																</div>								
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award Description:</label>
																	<div class="col-md-6">						
																												<textarea class="form-control" name="award_description" rows="3" id="" readonly="readonly"><?php echo $archive_award_req_arr['data'][$k]['award_description']; ?></textarea>
																	</div>
																</div>
																
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award Amount:</label>
																	<div class="col-md-6">						
																			
																		<input type="text" class="form-control" name="amount" value="<?php echo '$'.number_format($archive_award_req_arr['data'][$k]['amount']); ?>" maxlength="128" readonly="">
																	</div>
																</div>
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award Date:</label>
																	<div class="col-md-6">
																		<div class="input-group">
																			<input type="text" name="award_date" value="<?php echo $archive_award_req_arr['data'][$k]['award_date']; ?>" readonly=""  class="form-control">	
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award Start Performance Period :</label>
																	<div class="col-md-6">
																		<div class="input-group">
																		<input type="text" name="start_performance_period" value="<?php echo $archive_award_req_arr['data'][$k]['start_performance_period']; ?>" readonly="" class="form-control">
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Award End Performance Period :</label>
																	<div class="col-md-6">
																		<div class="input-group">
																	<input type="text" name="end_performance_period" value="<?php echo $archive_award_req_arr['data'][$k]['end_performance_period']; ?>" readonly="" class="form-control">
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Operating Unit ID:</label>
																	<div class="col-md-6">
																	<?php
																	## get operating unit from master===========
																	$url = PH_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$archive_award_req_arr['data'][$k]['operating_unit_id'];
																	$operating_unit_arr = requestByCURL($url);
																	?>									
																		<input type="text" value="<?php echo $operating_unit_arr['data']['operating_unit_description']; ?>" class="form-control" readonly="">
																	</div>
																</div>
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">COR/AOR Employee ID:</label>
																	<div class="col-md-6">								 
																		<select name="employee_id" class="form-control" disabled="disabled">
																		<?php
																		### get employee from HR Connect===============
																		$url = HR_API_HOST_URL."get_hr_employee.php?employee_id=".$archive_award_req_arr['data'][$k]['employee_id'];
																		$employee_arr = requestByCURL($url); 
																		?>	
																				<option value="000002" selected="selected"><?php echo $employee_arr['data']['first_name'].' '.$employee_arr['data']['second_name'].''.$employee_arr['data']['last_name'].' ('.$archive_award_req_arr['data'][$k]['employee_id'].')'; ?></option>  
																		</select>
																	</div>	
																</div>
																
														<?php
														$url = API_HOST_URL."get_archive_link_to_by_archive_award.php?associate_from_archive_id=".$archive_award_req_arr['data'][$k]['archive_award_id']."&associate_type=Award";
														$archive_assoc_link_arr = requestByCURL($url);
														if(count($archive_assoc_link_arr['data'])>0)
														{
															for($l=0; $l<count($archive_assoc_link_arr['data']); $l++)
															{  ?>
															<div class="req_link_to" style="width:100%;">
																<div class="form-group">
																	<label for="" class="col-md-6 control-label">Link To:</label>
																	<div class="col-md-6">								 
																		<select name="employee_id" class="form-control" disabled="disabled">	
																				<option value="000002" selected="selected"><?php echo $archive_assoc_link_arr['data'][$l]['link_to_type']; ?></option>  
																		</select>
																	</div>
																</div>
																<div class="form-group">	
																	<label for="" class="col-md-6 control-label">Project:</label>
																	<div class="col-md-6">	
																	<?php
																	### get project from HR Connect===============
																	$url = HR_API_HOST_URL."get_project.php?project_id=".$archive_assoc_link_arr['data'][$l]['link_to_id'];
																	$project_arr = requestByCURL($url); 
																	?>							 
																		<select name="employee_id" class="form-control" disabled="disabled">	
																				<option value="000002" selected="selected"><?php echo $project_arr['data']['title'].' ('.$project_arr['data']['project_id'].')'; ?></option>  
																		</select>
																	</div>	
																</div>
															</div>	
														<?php } } ?>		
																
															</div>					
														</div>
													</div>
													
													</div> 	
													<div class="col-md-6 budget">
						 								<div class="row title-budget">
															<div class="col-md-7 budget-title">
																<h4><b>Budget </b></h4>
															</div>				
														</div>
														<table id="local_vendor" class="table table-striped table-bordered" cellspacing="0" width="100%">
															<thead>
																	<tr>						
																		<th class="text-center" width="120">Cost Code</th>
																		<th class="text-center" width="200">Code Description</th>
																		<th class="text-center">Amount</th>												
																	</tr>					
																</thead>
																<tbody id="append_Budget">
																<?php
																	$url = API_HOST_URL."get_all_archive_budget_of_archive_award.php?budget_from_archive_id=".$archive_award_req_arr['data'][$k]['archive_award_id']."&budget_type=Award";
																	$archive_award_budget_arr = requestByCURL($url);
																if(count($archive_award_budget_arr['data'])>0)
																{
																	for($j=0; $j<count($archive_award_budget_arr['data']); $j++)
																	{  ?>					
																	<tr class="Budget-info">						
																		<td class="text-center"><?php echo $archive_award_budget_arr['data'][$j]['cost_code']; ?></td>
																		<td class="text-center"><?php echo $archive_award_budget_arr['data'][$j]['code_description']; ?></td>
																		<td class="text-center"><?php echo '$'.number_format($archive_award_budget_arr['data'][$j]['budget_amount']); ?></td>														
																	</tr>
															<?php   } 
																} else {?>
																	<tr>
																		<td colspan="3" align="center"> No budget</td>
																	</tr>
																<?php } ?>	
																				
															</tbody>
														</table>
													</div>
					
												 </form>
											 </div>
								 		 </div>
								  </div>
							</td>
						</tr>
			<?php  		 }	
					} else { ?>
						<tr>
							<td colspan="2" align="center">No Archive Data </td>
						</tr>
					<?php }?>	
						
					</tbody> 
				</table>
			
    </div>
	<br>

	<script src="js/jquery-ui.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script type="text/javascript">	

		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		})	
	</script>

	<?php
	if($flag==1)
		{ ?>
	<script> 
		$('.award').fadeIn().removeClass('disp-none'); 
	</script>
	<?php	
}
if(count($edit_award_budget_arr['data'])>0)
	{ ?>
<script>
	$('.show_budget').trigger('click');
</script>
<?php	
}
?>

<script>
$(document).ready(function(){
	$('.show_table').click(function() {
	$(this).closest("tr").next().toggleClass("disp-none");
    if ($(this).hasClass('fa-chevron-circle-down')){
        $(this).removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
    }
	 else {
         $(this).addClass('fa-chevron-circle-down').removeClass('fa-chevron-circle-up');
      }
});
});
</script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>