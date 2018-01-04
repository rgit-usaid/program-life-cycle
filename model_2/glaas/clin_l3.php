<?php
include('config/config.inc.php');
include('include/function.inc.php');

##generate CLIN number by CLIN 2=========
function generateClinNumber($clin_l2_number)
{
	$url = API_HOST_URL."get_all_clin_l3_by_clin2.php?flag=1&clin_l2_number=".$clin_l2_number;
	$clin_arr = requestByCURL($url);
	$total_count = count($clin_arr['data']);
	$total_count  = $total_count + 1;
	$last_number = sprintf("%03d",$total_count);
	$clin_number = $clin_l2_number.'-'.$last_number;
	return $clin_number;
}

###get requisition number or clin number from request=========
$requisition_number = '';
$level = 3;
if(isset($_REQUEST['requisition_number']))
{
	$requisition_number = trim($_REQUEST['requisition_number']);
	 $clin_l1_number = trim($_REQUEST['clin_l1_number']);
	$clin_l2_number = trim($_REQUEST['clin_l2_number']);
	$_SESSION['requisition_number'] = $requisition_number;
	$_SESSION['clin_l1_number'] = $clin_l1_number;
	$_SESSION['clin_l2_number'] = $clin_l2_number;
}
else
{
	$requisition_number = $_SESSION['requisition_number'];
	$clin_l1_number = $_SESSION['clin_l1_number'];
	$clin_l2_number = $_SESSION['clin_l2_number'];
}

## add/edit clin level 3 ============
if(isset($_REQUEST['save_clin_l3']))
{
	$clin_id = trim($_REQUEST['clin_id']);  
	$clin_number = trim($_REQUEST['clin_number']);
	$clin_name = $mysqli->real_escape_string(trim($_REQUEST['clin_name'])); 
	$clin_description = $mysqli->real_escape_string(trim($_REQUEST['clin_description']));
	$clin_amount = trim($_REQUEST['clin_amount']);
	$clin_amount = getNumericAmount($clin_amount); // use this function if amount has $ sign or comma   
	$start_performance_period = dateFormat(trim($_REQUEST['start_performance_period']));
	$end_performance_period = dateFormat(trim($_REQUEST['end_performance_period']));  
	
	## get association link information ==============
	$link_to_type = $_REQUEST['link_to_type']; // this is array of type
	$link_to_id = $_REQUEST['link_to_id'];  // this is array of assoc link 
	$assoc_link_id_arr = $_REQUEST['assoc_link_id_arr']; // this is array of edit link id 
			
	if(!isset($_REQUEST['share']))$share = 'Y';
	else $share = $_REQUEST['share'];
	
	##get clin budget information ==============
	$budget_id_arr = $_REQUEST['budget_id_arr'];
	$cost_code_arr = $_REQUEST['cost_code'];
	$code_description_arr = $_REQUEST['code_description'];  
	$budget_amount_arr = $_REQUEST['budget_amount']; 
	$budget_type = 'Clin';
	$error='';

	if($clin_number=='')
	{
		$error = "Please input CLIN number";
	}
	elseif($clin_name=='')
	{
		$error = "Please input CLIN name";

	}  
	else
	{  
		if($clin_id =='')
		{ 	
				 $insert_clin = "insert into usaid_requisition_clin set 
						requisition_number='".$requisition_number."', 
						clin_number='".$clin_number."', 
						clin_name='".$clin_name."', 
						clin_description='".$clin_description."', 
						clin_amount='".$clin_amount."', 
						start_performance_period='".$start_performance_period."', 
						end_performance_period='".$end_performance_period."',
						parent_clin_number='".$clin_l2_number."',  
						share='".$share."', 
						level='".$level."'"; 

			$result_clin = $mysqli->query($insert_clin);
 			$clin_id_new = $mysqli->insert_id;
			if($result_clin)
			{
				for($i=0; $i<count($cost_code_arr); $i++)
				{
					if(!empty($cost_code_arr[$i]))
					{
						$code_both = explode('=', $code_description_arr[$i]);
						$code_description = $code_both[0];
						$budget_amount = getNumericAmount($budget_amount_arr[$i]); // use this function if amount has $ sign or comma 
						 $insert_clin_budget = "insert into usaid_requisition_clin_budget set 
										budget_number='".$clin_number."',
										cost_code='".$cost_code_arr[$i]."',
										code_description='".$code_description."', 
										budget_amount='".$budget_amount."',
										budget_type='".$budget_type."'"; 
						$result_clin_budget = $mysqli->query($insert_clin_budget); 	
					}
				}
				## for assoc link =======
				for($i=0; $i<count($link_to_id); $i++)
				{
				
					if(!empty($link_to_id[$i]))
					{
						$insert_assoc_link = "insert into usaid_association_link set 
								associate_from_number='".$clin_number."',
								link_to_type='".$link_to_type[$i]."',
								link_to_id='".$link_to_id[$i]."'"; 
						$result_assoc_link = $mysqli->query($insert_assoc_link);  	
					}
				}  
			}
			header("location:clin_l3.php");
		}
		else
		{
			$update_clin = "update usaid_requisition_clin set  
						clin_name='".$clin_name."', 
						clin_description='".$clin_description."', 
						clin_amount='".$clin_amount."', 
						start_performance_period='".$start_performance_period."', 
						end_performance_period='".$end_performance_period."', 
						share='".$share."' where clin_number='".$clin_number."'";
			$result_clin = $mysqli->query($update_clin); 
			if($result_clin)
			{
				$url = API_HOST_URL."get_all_budget_of_clin.php?clin_number=".$clin_number;
				$check_budget_arr = requestByCURL($url);
				for($k=0; $k<count($check_budget_arr['data']);$k++)
				{
					if(!in_array($check_budget_arr['data'][$k]['budget_id'], $budget_id_arr))
					{
						$delete_budget = "delete from usaid_requisition_clin_budget where id='".$check_budget_arr['data'][$k]['budget_id']."'";
						$mysqli->query($delete_budget);
					}
				}
				for($i=0; $i<count($cost_code_arr); $i++)
				{
					$code_both = explode('=', $code_description_arr[$i]);
					$code_description = $code_both[0];
					if($budget_id_arr[$i]!='')
					{
						if($cost_code_arr[$i]!='')
						{
							$budget_amount = getNumericAmount($budget_amount_arr[$i]); // use this function if amount has $ sign or comma  
							$update_budget = "update usaid_requisition_clin_budget set 
										budget_number='".$clin_number."',
										cost_code='".$cost_code_arr[$i]."',
										code_description='".$code_description."', 
										budget_amount='".$budget_amount."'
										where id='".$budget_id_arr[$i]."'";
							$result_budget = $mysqli->query($update_budget); 			
						}	
					}
					else
					{
						if($cost_code_arr[$i]!='')
						{	
							$budget_amount = getNumericAmount($budget_amount_arr[$i]); // use this function if amount has $ sign or comma  
							$insert_clin_budget = "insert into usaid_requisition_clin_budget set 
										budget_number='".$clin_number."',
										cost_code='".$cost_code_arr[$i]."',
										code_description='".$code_description."', 
										budget_amount='".$budget_amount."', 
										budget_type='".$budget_type."'"; 
							$result_clin_budget = $mysqli->query($insert_clin_budget);
						}	 
					} 
				} 
				
				## for edit assoc link =====
				$url = API_HOST_URL."get_link_to_by_requisition.php?requisition_number=".$clin_number;
				$check_assoc_link_arr = requestByCURL($url);
				
				for($k=0; $k<count($check_assoc_link_arr['data']);$k++)
				{
					if(!in_array($check_assoc_link_arr['data'][$k]['id'], $assoc_link_id_arr))
					{
						$delete_assoc_link = "delete from usaid_association_link where id='".$check_assoc_link_arr['data'][$k]['id']."'";
						$mysqli->query($delete_assoc_link);
					}
				} 
				for($i=0; $i<count($assoc_link_id_arr); $i++)
				{
					if($assoc_link_id_arr[$i]!='')
					{
						if($link_to_id[$i]!='')
						{
							$update_budget = "update usaid_association_link set 
								associate_from_number='".$clin_number."',
								link_to_type='".$link_to_type[$i]."',
								link_to_id='".$link_to_id[$i]."'
								where id='".$assoc_link_id_arr[$i]."'";
							$result_budget = $mysqli->query($update_budget); 			
						}	
					}
					else
					{
						if($link_to_id[$i]!='' and $link_to_type[$i]!='')
						{	
							$insert_assoc_link = "insert into usaid_association_link set 
								associate_from_number='".$clin_number."',
								link_to_type='".$link_to_type[$i]."',
								link_to_id='".$link_to_id[$i]."'"; 
							$result_assoc_link = $mysqli->query($insert_assoc_link);  
						}	 
					} 
				} 
				header("location:clin_l3.php"); 
			}
		}
	}
}

### get requisition details=======
if($requisition_number!='')
{
### show in header============================================================
	$url = API_HOST_URL."get_requisition.php?requisition_number=".$requisition_number;
	$requisition_arr = requestByCURL($url);
	 $url = API_HOST_URL."get_clin_detail.php?clin_number=".$clin_l1_number; 
	$clin_l1_arr = requestByCURL($url); 
	$url = API_HOST_URL."get_clin_detail.php?clin_number=".$clin_l2_number;
	$clin_l2_arr = requestByCURL($url); 
########################################################
	## get all clin_l3 of this clin level 2
	$url = API_HOST_URL."get_all_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
	$requisition_clin_l3_arr = requestByCURL($url); 
	
	$clin_number = generateClinNumber($clin_l2_number);	
}

## edit clin level 1 =================
$flag = 0;
if(isset($_REQUEST['edit_clin_l3']))
{
	$edit_clin_number = $_REQUEST['edit_clin_number'];
	 
	## get clin detail for edit=============
	$url = API_HOST_URL."get_clin_detail.php?clin_number=".$edit_clin_number;
	$clin_l3_arr = requestByCURL($url); 

	### get all budget of a clin level 3===========
	$url = API_HOST_URL."get_all_budget_of_clin.php?clin_number=".$edit_clin_number;
	$edit_clin_budget_arr = requestByCURL($url);
	$clin_number = $edit_clin_number; 
	$flag = 1;
	
	## get all assoc link for clin level 3 =======	
	$url = API_HOST_URL."get_link_to_by_requisition.php?requisition_number=".$edit_clin_number;
	$assoc_link_arr = requestByCURL($url);
}

if(isset($_REQUEST['delete_clin_l3']))
{
	$edit_clin_number = $_REQUEST['edit_clin_number'];
	removeRequisitionClin_l3($edit_clin_number);
	header("location:clin_l3.php");
}
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
	<!-- <link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" /> -->	
	
    <!-- CSS
    ================================================== -->
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="css/font-awesome.min.css" type="text/css" rel="stylesheet">
   	<link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
   	<style>
    	.remove_link_to_blk{
    		padding: 1px 6px;
    	}
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
			<li><a class="menu-active" href="requisition.php">Requisition for Supplies or Services</a></li>
			<li><a href="award_instrument.php">Award / Modifications of Instrument</a></li>
			<!-- <li><a href="#">Instrument / CLIN Modifications</a></li> -->
		</ul>
	</div>
	<header class="container-fluid" id="requisition-detail">
		<div class="table-responsive">
			<table class="table head">
				<thead>
					<tr>
						<th colspan="6">
							<ul class="list-inline">
								<li>Requisition Information</li>
								<li class="pull-right">( <a href="requisition.php">Back to Requisition</a> )</li>
							</ul>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Requisition Number:</th><td><?php echo $requisition_number;?></td>
						<th>Requisition Type:</th><td><?php echo $requisition_arr['data']['type'];?></td>
						<th>Requisition Status:</th><td><?php echo $requisition_arr['data']['status'];?></td> 
					</tr>
					<tr>
						<th>Requisition Created Date:</th><td><?php echo $requisition_arr['data']['create_date'];?></td> 
						<th>Performance Start Date:</th><td><?php echo $requisition_arr['data']['period_of_performance_start_date'];?></td> 
						<th>Performance End Date:</th><td><?php echo $requisition_arr['data']['period_of_performance_end_date'];?></td> 
					</tr>
				</tbody>
				<thead>
					<tr>
						<th colspan="6">
							<ul class="list-inline">
								<li>CLIN - L1</li>
								<li class="pull-right">( <a href="clin_l1.php">Back to CLIN - L1</a> )</li>
							</ul>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>CLIN Number:</th><td><?php echo $clin_l1_arr['data']['clin_number']?></td> 
						<th>CLIN Name:</th>	<td><?php echo $clin_l1_arr['data']['clin_name']?></td> 
						<th>CLIN Description:</th><td> <?php echo $clin_l1_arr['data']['clin_description']?></td> 
					</tr>
					<tr>
						<th>CLIN Amount:</th><td><?php echo '$'.number_format($clin_l1_arr['data']['clin_amount']);?></td> 
						<th>CLIN Start Performance Period:</th>	<td><?php echo $clin_l1_arr['data']['start_performance_period']?></td> 
						<th>CLIN End Performance Period:</th><td><?php echo $clin_l1_arr['data']['end_performance_period']?></td> 
					</tr>
				</tbody>
				<thead>
					<tr>
						<th colspan="6">
							<ul class="list-inline">
								<li>CLIN - L2	</li>
								<li class="pull-right">( <a href="clin_l2.php">Back to CLIN - L2</a> )</li>
							</ul>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>CLIN Number:</th><td><?php echo $clin_l2_arr['data']['clin_number']?></td> 
						<th>CLIN Name:</th>	<td><?php echo $clin_l2_arr['data']['clin_name']?></td> 
						<th>CLIN Description:</th><td> <?php echo $clin_l2_arr['data']['clin_description']?></td> 
					</tr>
					<tr>
						<th>CLIN Amount:</th><td><?php echo '$'.number_format($clin_l2_arr['data']['clin_amount']);?></td> 
						<th>CLIN Start Performance Period:</th>	<td><?php echo $clin_l2_arr['data']['start_performance_period']?></td> 
						<th>CLIN End Performance Period:</th><td><?php echo $clin_l2_arr['data']['end_performance_period']?></td> 
					</tr>
				</tbody>
			</table>
		</div>		
	</header>
	<div class="container-fluid" id="form-requisiton">
		<div class="row">
		<form class="form-horizontal" role="form" method="post" action="">			
		<input type="hidden" name="clin_id" value="<?php echo $clin_l3_arr['data']['clin_id'];?>">		
			<div class="col-md-6">
				<div class="container-fluid" id="manage-vendor">
					<div class="form-title" style="width:145px;">
						<i class="fa fa-minus" aria-hidden="true"></i>CLIN - L3
					</div>
					<div class="new-form-content">
						<div class="manage-info">				
							
								<!-- <h5 class="pull-right"><a href="requisition.php">Back to requisition</a></h5> -->
								<div class="form-group">
									<label for="clin_number" class="col-md-6 control-label">CLIN Number:</label>
									<div class="col-md-6">								
										<input type="text" class="form-control" <?php if($flag==1)echo 'readonly' ?> name="clin_number" value="<?php echo $clin_number;?>" maxlength="128">
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-md-6 control-label">CLIN Name:</label>
									<div class="col-md-6">								
										<?php 
										$clin_name=''; 
										if(isset($_REQUEST['clin_name']))$clin_name = trim($_REQUEST['clin_name']);
										if(isset($clin_l3_arr['data'])){$clin_name = $clin_l3_arr['data']['clin_name'];}
										?>		
										<input type="text" class="form-control" name="clin_name" value="<?php echo $clin_name;?>" maxlength="128">
									</div>
								</div>

								<div class="form-group">
									<label for="" class="col-md-6 control-label">CLIN Description:</label>
									<div class="col-md-6">								
										<?php 
										$clin_description=''; 
										if(isset($_REQUEST['clin_description']))$clin_description = trim($_REQUEST['clin_description']);
										if(isset($clin_l3_arr['data'])){$clin_description = $clin_l3_arr['data']['clin_description'];}
										?>								
										<textarea class="form-control" name="clin_description" rows="3" id=""><?php echo $clin_description;?></textarea>
									</div>
								</div> 

								<div class="form-group">
									<label for="" class="col-md-6 control-label">CLIN Amount:</label>
									<div class="col-md-6">								
										<?php 
										$clin_amount=''; 
										if(isset($_REQUEST['clin_amount']))$clin_amount = trim($_REQUEST['clin_amount']);
										if(isset($clin_l3_arr['data'])){$clin_amount = '$'.number_format($clin_l3_arr['data']['clin_amount']);}
										?>	
										<input type="text" class="form-control" name="clin_amount" value="<?php echo $clin_amount;?>">
									</div>
								</div> 

								<div class="form-group">
									<label for="" class="col-md-6 control-label"></label>
									<div class="col-md-6">							
										<h5 class="show_budget" id="show_budget">Show Budget</h5>
									</div>
								</div> 

								<div class="form-group">
									<label for="" class="col-md-6 control-label">CLIN Start Performance Period:</label>
									<div class="col-md-6">
										<div class="input-group">
										<?php 
										$start_performance_period=''; 
										if(isset($_REQUEST['start_performance_period']))$start_performance_period = trim($_REQUEST['start_performance_period']);
										if(isset($clin_l3_arr['data'])){$start_performance_period = $clin_l3_arr['data']['start_performance_period'];}
										?>	
										<input type="text" id="datepicker" class="form-control" name="start_performance_period" value="<?php echo $start_performance_period;?>" />
        									<span class="input-group-addon" id="btn" style="cursor:pointer;">
                    							<span class="glyphicon glyphicon-calendar"></span>
        									</span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="" class="col-md-6 control-label">CLIN End Performance Period:</label>
									<div class="col-md-6">
										<div class="input-group">
										<?php 
										$end_performance_period=''; 
										if(isset($_REQUEST['end_performance_period']))$end_performance_period = trim($_REQUEST['end_performance_period']);
										if(isset($clin_l3_arr['data'])){$end_performance_period = $clin_l3_arr['data']['end_performance_period'];}
										?>
										<input type="text" id="datepicker1" class="form-control" name="end_performance_period" value="<?php echo $end_performance_period;?>" />
        									<span class="input-group-addon" id="btn1" style="cursor:pointer;">
                    							<span class="glyphicon glyphicon-calendar"></span>
        									</span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="" class="col-md-6 control-label">CLIN do not share:</label>
									<div class="col-md-6">								
										<?php 
										$share=''; 
										if(isset($_REQUEST['share']))$share = trim($_REQUEST['share']);
										if(isset($clin_l3_arr['data'])){$share = $clin_l3_arr['data']['share'];}
										 
										?>		
										<input type="checkbox" name="share" value="N" <?php if($share=='N')echo 'checked="checked"'; ?>>
									</div>
								</div>

								<?php
							if(count($assoc_link_arr['data'])>0)  ## fetch from api data
							{	
								for($k=0; $k<count($assoc_link_arr['data']); $k++)
								{		
									?>
									<div class="req_link_to" style="width:100%"> 
										<div class="form-group">
											<div class="col-md-12"> 
												<a class="remove_link_to_blk btn btn-danger pull-right<?php if($k==0) echo " disp-none"; ?>"><span class="fa fa-times"></span></a>
												<div style="height:5px; clear:both"></div>
											</div>
											<label for="associate_type" class="col-md-6 control-label"> Link To:</label>
											<div class="col-md-6">
													<input type="hidden" class="form-control assoc_link_id_arr" name="assoc_link_id_arr[]" value="<?php echo $assoc_link_arr['data'][$k]['id']; ?>" />	
													<select class="form-control associate_type" id="sel1" name="link_to_type[]" onChange="showAssociate(this);">
													<option value="">Select</option>
													<option value="Project" <?php if($assoc_link_arr['data'][$k]['link_to_type']=='Project') echo "selected"; ?>>Project</option>  
													<option value="Project Activity" <?php if($assoc_link_arr['data'][$k]['link_to_type']=='Project Activity') echo "selected"; ?>>Project Activity</option> 
												<!--	<option value="DOAG" <?php if($assoc_link_arr['data'][$k]['link_to_type']=='DOAG') echo "selected"; ?>>DOAG</option> -->
												</select>
											</div>	
											</div> 
											<?php
											
												if($assoc_link_arr['data'][$k]['link_to_type']=='Project')
												{ 
													 $sel_val = $assoc_link_arr['data'][$k]['link_to_id'];
												}
												if($assoc_link_arr['data'][$k]['link_to_type']=='Project Activity')
												{ 
													 $sel_val = $assoc_link_arr['data'][$k]['link_to_id'];
												}
												if($assoc_link_arr['data'][$k]['link_to_type']=='DOAG')
												{ 
													$sel_val = $assoc_link_arr['data'][$k]['link_to_id'];
												}
												
											?>
										<div class="form-group associate disp-none"> 
										</div>
									</div>
					 				<script>
										$('.req_link_to:last').find('.associate_type').each(function(index, elem){
											showAssociate($(elem), "<?php echo $sel_val;?>");
										});
									</script>	
								 <?php
								 }
							}
							else
							{ ?>
								<div class="req_link_to" style="width:100%"> 
									<div class="form-group">
										<div class="col-md-12"> 
											<a class="remove_link_to_blk btn btn-danger pull-right disp-none"><span class="fa fa-times"></span></a>
											<div style="height:5px; clear:both"></div>
										</div>
										<label for="associate_type" class="col-md-6 control-label"> Link To:</label>
										<div class="col-md-6"> 
											<input type="hidden" class="form-control" name="assoc_link_id_arr[]" value="" />
											<select class="form-control associate_type" id="sel1" name="link_to_type[]" onChange="showAssociate(this);">
												<option value="">Select</option>
												<option value="Project">Project</option>  
												<option value="Project Activity">Project Activity</option>
											<!--	<option value="DOAG">DOAG</option> -->
											</select>
										</div>	
								</div> 
									<div class="form-group associate disp-none"> 
									</div>
								</div>
					 	
					  <?php } ?>					
								
								
								<div class="form-group">
									<div class="col-md-offset-6 col-md-6">
										<button type="button" class="pull-right btn btn-primary" id="add_more_link_to">Add Link</button>
										<div class="clearfix"></div>
									</div>
								</div> 
								
								<div class="form-group">
									<div class="col-md-6">
										<h5 class="pull-right"><a href="clin_l2.php">Back to CLIN L2</a></h5>
									</div>
									<div class="col-md-6">
										<a href="clin_l3.php" class="btn btn-default" style="margin-right:25px;">Cancel</a>
										<button type="submit" class="btn btn-primary save" name="save_clin_l3" >Save</button>
									</div>
								</div> 
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 budget disp-none">
				<table id="local_vendor" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<div class="row title-budget">
								<div class="col-md-7 budget-title">
									<h4><b>Budget for <span> <?php echo $clin_number;?></span> </b></h4>
								</div>				
								<div class="col-md-5 add_budget_item">
									<button type="button" class="btn btn-info pull-right" class="add_budget" id="add_budget" onclick="addBudget()"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Budget Item</button>
								</div>
							</div>	
						</tr>
						<tr>						
							<th class="text-center">Cost Code</th>
							<th class="text-center">Code Description</th>
							<th class="text-center">Amount</th>												
							<th class="text-center">Delete</th>
						</tr>					
					</thead>
					<tbody id="append_Budget">					
						<?php
						if(count($edit_clin_budget_arr['data'])>0)
						{	
							for($k=0; $k<count($edit_clin_budget_arr['data']); $k++)
							{		
							?>	
								<tr class="Budget-info">						
									<td>
										<input type="text" class="form-control cost_code" name="cost_code[]" value="<?php echo $edit_clin_budget_arr['data'][$k]['cost_code'];?>" readonly>
										<input type="hidden" class="form-control" name="budget_id_arr[]" value="<?php echo $edit_clin_budget_arr['data'][$k]['budget_id']; ?>">
									</td>
									<td>
									<select class="form-control" name="code_description[]" onChange="getCodeDescription(this)"; >
													<option value="">Select</option>
													<option value="Supplies=2001"<?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Supplies') echo "selected"; ?>>Supplies </option>
													<option value="Travel and Per diem=3001" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Travel and Per diem') echo "selected"; ?>>Travel and Per diem </option>
													<option value="Benefits=1002" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Benefits') echo "selected"; ?>>Benefits </option>
													<option value="Equipment=2002" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Equipment') echo "selected"; ?>>Equipment </option>
													<option value="Leases and Rentals=4001" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Leases and Rentals') echo "selected"; ?>>Leases and Rentals </option>
													<option value="Consultants=1003" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Consultants') echo "selected"; ?>>Consultants </option>
													<option value="Operating expenses=2003" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Operating expenses') echo "selected"; ?>>Operating expenses </option>
													<option value="Other expenses=5001" <?php if($edit_clin_budget_arr['data'][$k]['code_description']=='Other expenses') echo "selected"; ?>>Other expenses </option>
									</select>
									</td>
									<td>
										<input type="text" class="form-control" name="budget_amount[]" value="<?php if($edit_clin_budget_arr['data'][$k]['budget_amount']!='') echo '$'.number_format($edit_clin_budget_arr['data'][$k]['budget_amount']);?>">
									</td>														
									<td width="120" class="text-center">				
										<button type="button" class="btn btn-danger" onClick="removeBudget(this)" id="remove_vendor"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
									</td>
								</tr>
							<?php
							}
						}
						else
						{ ?>
							<tr class="Budget-info">						
								<td>
									<input type="text" class="form-control cost_code" name="cost_code[]" value="" readonly>
								</td>
								<td>
									<select class="form-control" name="code_description[]" onChange="getCodeDescription(this)"; >
										<option value="">Select</option>
										<option value="Supplies=2001">Supplies </option>
										<option value="Travel and Per diem=3001">Travel and Per diem </option>
										<option value="Benefits=1002">Benefits </option>
										<option value="Equipment=2002">Equipment </option>
										<option value="Leases and Rentals=4001">Leases and Rentals </option>
										<option value="Consultants=1003">Consultants </option>
										<option value="Operating expenses=2003">Operating expenses </option>
										<option value="Other expenses=5001">Other expenses </option>
									</select>
								</td>
								<td>
									<input type="text" class="form-control" name="budget_amount[]" value="">
								</td>														
								<td width="120" class="text-center">				
									<button type="button" class="btn btn-danger" onClick="removeBudget(this)" id="remove_vendor"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
								</td>
							</tr>
						<?php	
						}
						?>			
					</tbody>
				</table>
			</div>
		</div>
							
		</form>					
	</div>
</div>

<!-- Table Data -->

<div class="container-fluid">
	<table id="manage-parent-table" class="table table-striped" border="2" style="" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th class="text-left">L3 <span style="margin-left:30px;"> CLIN Number </span></th>				
				<th class="text-center">Name</th>
				<th class="text-center">Description</th>
				<th class="text-center">Amount</th>
				<th class="text-center">Start Performance Period</th>
				<th class="text-center">End Performance Period</th>
				<th class="text-center">Linked To</th>
				<th class="text-center" style="width:280px">Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(count($requisition_clin_l3_arr['data'])>0)
		{
			for($k=0; $k<count($requisition_clin_l3_arr['data']); $k++)
			{
				$url = API_HOST_URL."get_all_budget_of_clin.php?clin_number=".$requisition_clin_l3_arr['data'][$k]['clin_number'];
				$clin_budget_arr = requestByCURL($url);
				$url = API_HOST_URL."get_link_to_by_requisition.php?requisition_number=".$requisition_clin_l3_arr['data'][$k]['clin_number'];
				$list_assoc_link_arr = requestByCURL($url);
				?>	
				<tr class="parent-tbl parent-link">
					<td class="text-center"><span onClick="showClinTwo(this)" class="btn action" title="Level-3"> <img src="images/plus.png"></span> <?php echo $requisition_clin_l3_arr['data'][$k]['clin_number']; ?></td>
					<td class="text-center"><?php echo $requisition_clin_l3_arr['data'][$k]['clin_name']; ?></td>
					<td class="text-center"><?php echo $requisition_clin_l3_arr['data'][$k]['clin_description']; ?></td>
					<td class="text-right"><?php echo '$'.number_format($requisition_clin_l3_arr['data'][$k]['clin_amount']); ?></td>
					<td class="text-center"><?php echo $requisition_clin_l3_arr['data'][$k]['start_performance_period']; ?></td>
					<td class="text-center"><?php echo $requisition_clin_l3_arr['data'][$k]['end_performance_period']; ?></td>
					<td class="text-center">
						<button type="button" class="btn btn-default action" data-placement="top" onClick="showLink(this)">Show Link</button>
						</td>
					<td class="text-center"> 
						<div class="row">
						<ul class="list-inline" style="">
							<li>
							<form method="post" action="">
								<input type="hidden" name="edit_clin_number" value="<?php echo $requisition_clin_l3_arr['data'][$k]['clin_number'];?>">			
								<button type="submit" name="edit_clin_l3" class="btn btn-success action" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
								<button type="submit" name="delete_clin_l3" class="btn btn-danger action" data-toggle="tooltip" data-placement="top" title="Remove" onClick="return window.confirm('Are you sure you want to remove this?');"><i class="fa fa-times" aria-hidden="true" ></i></button>
								<button type="button" class="btn btn-default action" data-toggle="tooltip" data-placement="top" title="BUDGET" onClick="showChild(this);"><i class="btn btn-xs action arrow fa fa-arrow-down" style="font-size: 12px;"> BUDGET</i></button>
							</form>
							</li>
							<li>
								<form method="post" action="clin_l4.php" class="form-inline"> 
									<input type="hidden" name="clin_l1_number" value="<?php echo $clin_l1_number; ?>">
									<input type="hidden" name="clin_l2_number" value="<?php echo $clin_l2_number; ?>">
									<input type="hidden" name="clin_l3_number" value="<?php echo $requisition_clin_l3_arr['data'][$k]['clin_number'];?>">
									<input type="hidden" name="requisition_number" value="<?php echo $requisition_number;?>"> 
									<button type="submit" class="btn btn-default action" data-toggle="tooltip" data-placement="top" title="CLIN - L4" style="margin:0 2px; color:#000; font-size: 12px; text-decoration:none" >CLIN - L4</button>
								</form>
							</li>									
						</ul>
					</div>			
					</td>
				</tr>
				<tr class="child-link disp-none">
					<td colspan="8">
						<div style="padding:10px;">
							<table id="manage-child-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
								<thead>
									<tr>										
										<th class="text-center">ID</th>
										<th class="text-center">Type</th> 
									</tr>
								</thead>
								<tbody>
								<?php
								if(count($list_assoc_link_arr['data'])>0)
								{
								for($j=0; $j<count($list_assoc_link_arr['data']); $j++)
								{
									?>
									<tr>										
										<td><?php echo $list_assoc_link_arr['data'][$j]['link_to_id']; ?></td>
										<td><?php echo $list_assoc_link_arr['data'][$j]['link_to_type']; ?></td>
									</tr>
							<?php }
								  }
								  else
								  {	
									 echo '<tr><td colspan="2">No Data Available</td></tr>';
								  } 
								 ?>
									
								</tbody>								
							</table>
						</div>
					</td>
				</tr>
				<tr class="child-table disp-none">
					<td colspan="8">
						<div style="padding:10px;">
							<table id="manage-child-table" class="table table-striped" border="1" cellspacing="0" width="100%">
								<thead>
									<tr>									
										<th class="text-center code-field">Budget Cost Code</th>
										<th class="text-center">Budget Code Description</th>
										<th class="text-center">Budget Amount</th>
										 
									</tr>
								</thead>
								<tbody>
									<?php
									for($j=0; $j<count($clin_budget_arr['data']); $j++)
									{
									?>	
										<tr>										
											<td class="text-center"><?php echo $clin_budget_arr['data'][$j]['cost_code']; ?></td>
											<td class="text-center"><?php echo $clin_budget_arr['data'][$j]['code_description']; ?></td>
											<td class="text-right" style="margin-right: 2px;"><?php echo '$'.number_format($clin_budget_arr['data'][$j]['budget_amount']); ?></td>								
										 </tr>
									<?php
									}
									?> 
								</tbody>								
							</table>
						</div>
					</td>
				</tr>
				<tr class="child-clin2 disp-none">
						<td colspan="9">
							<div style="padding:10px;">
								<table id="manage-parent-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
									<thead>
										<tr>
										<th class="text-left">L4 <span style="margin-left:30px;"> CLIN Number </span></th>				
										<th class="text-center">Name</th>
										<th class="text-center">Description</th>
										<th class="text-center">Amount</th>
										<th class="text-center">Start Performance Period</th>
										<th class="text-center">End Performance Period</th>										
										</tr>
									</thead>
									<tbody>
									<?php
							## get all clin_l4 of this clin level 3
							$url = API_HOST_URL."get_all_clin_l4_by_clin3.php?clin_l3_number=".$requisition_clin_l3_arr['data'][$k]['clin_number'];
							$requisition_clin_l4_arr = requestByCURL($url); 
							if(count($requisition_clin_l4_arr['data'])>0)
								{		
									for($n=0; $n<count($requisition_clin_l4_arr['data']); $n++)
									{	
									?>
										<tr>										
											<td><?php echo $requisition_clin_l4_arr['data'][$n]['clin_number']; ?></td>
											<td><?php echo $requisition_clin_l4_arr['data'][$n]['clin_name']; ?></td>
											<td><?php echo $requisition_clin_l4_arr['data'][$n]['clin_description']; ?></td>
											<td><?php echo '$'.number_format($requisition_clin_l4_arr['data'][$n]['clin_amount']); ?></td>
											<td><?php echo $requisition_clin_l4_arr['data'][$n]['start_performance_period']; ?></td>
											<td><?php echo $requisition_clin_l4_arr['data'][$n]['end_performance_period']; ?></td>
										</tr>
								<?php }
									} else { echo '<tr><td colspan="6">No Clin Level Available</td></tr>'; } ?>	
									</tbody>								
								</table>
							</div>
						</td>
						</tr>
			<?php
			}
		}else echo '<tr><td colspan="8">No Data Available</td></tr>';
		?> 
		</tbody>
	</table>	
</div>

<br>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">	
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})	
</script>
<?php
if(count($edit_clin_budget_arr['data'])>0)
	{ ?>
<script>

	$('.show_budget').trigger('click');

</script>
<?php	
}
?>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>