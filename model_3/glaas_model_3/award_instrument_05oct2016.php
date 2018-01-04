<?php
include('config/config.inc.php');
include('include/function.inc.php');

## API for get all requisition for showing in drop down=======
$url = API_HOST_URL."get_all_requisition.php";
$all_requisition_arr = requestByCURL($url);

### api for get all vendor dropdown ============
$url = API_HOST_URL."get_all_vendor.php";
$all_vendor_arr = requestByCURL($url);

$flag = 0;
if(isset($_REQUEST['requisition_number']))
{ 
	$requisition_number = trim($_REQUEST['requisition_number']);
	$_SESSION['requisition_number'] = $requisition_number;
	if($requisition_number=='')
	{
		unset($_SESSION['requisition_number']);
		$flag = 0;
	}
	else $flag = 1;
}
if(isset($_SESSION['requisition_number']))
{
	$requisition_number = $_SESSION['requisition_number'];
	$flag = 1;
}

## add new vendor wither thier address ============
$error = '';
if(isset($_REQUEST['save_requisition_award']))
{
	$flag = 1;
	$award_id = trim($_REQUEST['award_id']);
	$award_number = trim($_REQUEST['award_number']); 
	$vendor_id = trim($_REQUEST['vendor_id']);  
	$award_name = $mysqli->real_escape_string(trim($_REQUEST['award_name']));
	$award_description = $mysqli->real_escape_string(trim($_REQUEST['award_description']));
	$award_date = dateFormat(trim($_REQUEST['award_date'])); 
	$start_performance_period =  dateFormat(trim($_REQUEST['start_performance_period']));
	$end_performance_period =  dateFormat(trim($_REQUEST['end_performance_period']));
	$implementing_mechanism_type = trim($_REQUEST['implementing_mechanism_type']);
	$do_not_share = trim($_REQUEST['do_not_share']);
	if($do_not_share=='')$do_not_share = 'N';

	##get award budget information ==============
	$budget_id_arr = $_REQUEST['budget_id_arr'];
	$cost_code_arr = $_REQUEST['cost_code'];
	$code_description_arr = $_REQUEST['code_description'];  
	$budget_amount_arr = $_REQUEST['budget_amount']; 
	$budget_type = 'Award';
 
	if($requisition_number=='')
	{
		$error = 'Requisition number shoul not be blank';
	}
	elseif($award_number=='')
	{
		$error = 'Award number shoul not be blank';
	}
	elseif($implementing_mechanism_type=='')
	{
		$error = 'Please select implementing mechanism type';
	}
	else
	{	
		if($award_id=='')
		{
			$insert_data = "insert into usaid_requisition_award set
						requisition_number = '".$requisition_number."',
						award_number = '".$award_number."',
						vendor_id = '".$vendor_id."',
						award_name = '".$award_name."',
						award_description = '".$award_description."', 
						award_date = '".$award_date."',
						start_performance_period = '".$start_performance_period."',
						end_performance_period = '".$end_performance_period."',
						implementing_mechanism_type = '".$implementing_mechanism_type."',
						do_not_share = '".$do_not_share."'"; 

			$result_data = $mysqli->query($insert_data);
			if($result_data)
			{
				for($i=0; $i<count($cost_code_arr); $i++)
				{
					if(!empty($cost_code_arr[$i]))
					{
						$insert_budget = "insert into usaid_requisition_clin_budget set 
										budget_number='".$award_number."',
										cost_code='".$cost_code_arr[$i]."',
										code_description='".$code_description_arr[$i]."', 
										budget_amount='".$budget_amount_arr[$i]."',
										budget_type='".$budget_type."'"; 
						$result_budget = $mysqli->query($insert_budget); 	
					}
				} 
				header("location:award_instrument.php");
			}
		}
		else
		{
			$update_data = "update usaid_requisition_award set
					vendor_id = '".$vendor_id."',
					award_name = '".$award_name."',
					award_description = '".$award_description."', 
					award_date = '".$award_date."',
					start_performance_period = '".$start_performance_period."',
					end_performance_period = '".$end_performance_period."',
					implementing_mechanism_type = '".$implementing_mechanism_type."',
					do_not_share = '".$do_not_share."'		
					where id='".$award_id."'"; 

			$result_data = $mysqli->query($update_data);

			if($result_data)
			{
				$url = API_HOST_URL."get_all_budget_of_award.php?award_number=".$award_number;
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
					if($budget_id_arr[$i]!='')
					{
						if($cost_code_arr[$i]!='')
						{
							$update_budget = "update usaid_requisition_clin_budget set 
										budget_number='".$award_number."',
										cost_code='".$cost_code_arr[$i]."',
										code_description='".$code_description_arr[$i]."', 
										budget_amount='".$budget_amount_arr[$i]."'
										where id='".$budget_id_arr[$i]."'";
							$result_budget = $mysqli->query($update_budget); 			
						}	
					}
					else
					{
						if($cost_code_arr[$i]!='')
						{	
							$insert_clin_budget = "insert into usaid_requisition_clin_budget set 
										budget_number = '".$award_number."',
										cost_code='".$cost_code_arr[$i]."',
										code_description='".$code_description_arr[$i]."', 
										budget_amount='".$budget_amount_arr[$i]."', 
										budget_type='".$budget_type."'"; 
							$result_clin_budget = $mysqli->query($insert_clin_budget);
						}	 
					} 
				} 
				header("location:award_instrument.php"); 
			} 
		}
	}
}

if($requisition_number!='')
{
	## API for get all requisition award=======
	$url = API_HOST_URL."get_all_award_by_requisition.php?requisition_number=".$requisition_number;
	$all_requisition_award_arr = requestByCURL($url); 
}
## edit award =================
if(isset($_REQUEST['edit_award']))
{
	$edit_award_number = $_REQUEST['edit_award_number'];
	## get clin detail for edit=============
	$url = API_HOST_URL."get_award_detail.php?award_number=".$edit_award_number;
	$award_arr = requestByCURL($url); 
	 
	### get all budget of a clin level 1===========
	$url = API_HOST_URL."get_all_budget_of_award.php?award_number=".$edit_award_number;
	$edit_award_budget_arr = requestByCURL($url);
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
	
    <!-- CSS
    ================================================== -->
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" type="text/css" rel="stylesheet">
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
		<div class="row" style="padding-top: 10px;">
			<div class="col-md-offset-3 col-md-6">
				<label for="" class="col-md-5 control-label"><h4>Select Requisition Number:</h4></label>
				<div class="col-md-6">						
					<form action="" method="post" name="requistion_from">	
						<select class="form-control" name="requisition_number"  id="selectBox1" onchange="this.form.submit();">
							<option value="">Select</option>
							<?php
							for($i=0; $i<count($all_requisition_arr['data']); $i++)
							{
								?>
								<option value="<?php echo $all_requisition_arr['data'][$i]['requisition_number'];?>" <?php if($requisition_number== $all_requisition_arr['data'][$i]['requisition_number'])echo 'selected="selected"';?>><?php echo $all_requisition_arr['data'][$i]['requisition_number'];?></option>
								<?php
							} ?>
						</select>
					</form>
				</div>
			</div>			
		</div>
		<div class="row award disp-none">
			<form class="form-horizontal" role="form" method="post" action="">				
				<input type="hidden" name="award_id" value="<?php echo $award_arr['data']['award_id'];?>">	
				<div class="col-md-6">
					<div class="container-fluid" id="manage-vendor">
						<div class="form-title" style="width:145px;">
							<i class="fa fa-minus" aria-hidden="true"></i>Award
						</div>
						<div class="new-form-content">
							<div class="manage-info">
								<?php
									if($error!='')
									{
										echo '<div style="text-align:center; color:red; margin-bottom:10px;">'.$error.'</div>';	
									}	
									?>	 
									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award Instrument Number:</label>
										<div class="col-md-6">						
											<?php
											$award_number = '';
											$fl = 0;
											if(isset($_REQUEST['award_number']))$award_number = $_REQUEST['award_number'];
											if(isset($award_arr['data'])){$award_number = $award_arr['data']['award_number']; $fl=1;}
											?>	
											<input type="text" class="form-control" <?php if($fl==1)echo 'readonly'; ?> name="award_number" value="<?php echo $award_number;?>">
										</div>
									</div>

									<div class="form-group">
										<label for="status" class="col-md-6 control-label">Award Implementing Mechanism Type:</label>
										<div class="col-md-6">						
											<select class="selectpicker" data-width="100%" name="implementing_mechanism_type">
												<option value="">Select</option> 
													<option value="Host Country Contracts" <?php if($award_arr['data']['implementing_mechanism_type']=='Host Country Contracts')echo 'selected="selected"';?>>Host Country Contracts</option>
													<option value="Host Country Grants" <?php if($award_arr['data']['implementing_mechanism_type']=='Host Country Grants')echo 'selected="selected"';?>>Host Country Grants</option>
													<option value="Fixed Amount Reimbursement" <?php if($award_arr['data']['implementing_mechanism_type']=='Fixed Amount Reimbursement')echo 'selected="selected"';?>>Fixed Amount Reimbursement (FAR)</option>
													<option value="Performance Disbursement" <?php if($award_arr['data']['implementing_mechanism_type']=='Performance Disbursement')echo 'selected="selected"';?>>Performance Disbursement (Expanded FAR)</option>
													<option value="Implementation Letter" <?php if($award_arr['data']['implementing_mechanism_type']=='Implementation Letter')echo 'selected="selected"';?>>Implementation Letter (IL) Financing:</option>
													<option value="General Budget and Balance of Payments Support" <?php if($award_arr['data']['implementing_mechanism_type']=='General Budget and Balance of Payments Support')echo 'selected="selected"';?>>General Budget and Balance of Payments Support</option> 
											</select>							
										</div>
									</div> 								
									
									<div class="form-group">
										<label for="" class="col-md-6 control-label">Vendor:</label>
										<div class="col-md-6">	
										<select class="form-control" name="vendor_id"  id="vendor-select">
												<option value="">Select</option>
											<?php 
											for($c=0; $c<count($all_vendor_arr['data']);  $c++)
											{	
											?>	
												<option value="<?php echo $all_vendor_arr['data'][$c]['vendor_id'];?>" <?php if($award_arr['data']['vendor_id']==$all_vendor_arr['data'][$c]['vendor_id'])echo 'selected="selected"'; ?>><?php echo $all_vendor_arr['data'][$c]['DUNS_number'].' ('.$all_vendor_arr['data'][$c]['name'].')';?></option>
											<?php
											}
											?>	 					
											</select>
										</div>
									</div>
									<div class="vendor-hide1 disp-none">
										<div class="form-group">
											<label for="" class="col-md-6 control-label">Vendor DUNS Number:</label>
											<div class="col-md-6"> 
												<input type="text" class="form-control" name="" value="" >
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-6 control-label">Vendor Name:</label>
											<div class="col-md-6">						
												<input type="text" class="form-control" name="" value="" >
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-6 control-label">Vendor Address City:</label>
											<div class="col-md-6">						
												<input type="text" class="form-control" name="" value="" >
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-6 control-label">Vendor Address State:</label>
											<div class="col-md-6">						
												<input type="text" class="form-control" name="" value="">
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award Name:</label>
										<div class="col-md-6">						
											<?php
											$award_name = ''; 
											if(isset($_REQUEST['award_name']))$award_name = $_REQUEST['award_name'];
											if(isset($award_arr['data'])){$award_name = $award_arr['data']['award_name'];}
											?>	
											<input type="text" class="form-control" name="award_name" value="<?php echo $award_name;?>" maxlength="128">
										</div>
									</div>								
									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award Description:</label>
										<div class="col-md-6">						
											<?php
											$award_description = ''; 
											if(isset($_REQUEST['award_description']))$award_description = $_REQUEST['award_description'];
											if(isset($award_arr['data'])){$award_description = $award_arr['data']['award_description'];}
											?>
											<textarea class="form-control" name="award_description" rows="3" id=""><?php echo $award_description;?></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label for="" class="col-md-6 control-label"></label>
										<div class="col-md-6">							
											<h5 class="show_budget" id="show_budget">Show Budget</h5>
										</div>
									</div> 

									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award Date:</label>
										<div class="col-md-6">
											<div class="input-group">
												<?php
												$award_date = ''; 
												if(isset($_REQUEST['award_date']))$award_date = $_REQUEST['award_date'];
												if(isset($award_arr['data'])){$award_date = $award_arr['data']['award_date'];}
												?>
												<input type="text" id="datepicker2" name="award_date" value="<?php echo $award_date;?>" class="form-control" />
												<span class="input-group-addon" id="btn2" style="cursor:pointer;">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award Start Performance Period :</label>
										<div class="col-md-6">
											<div class="input-group">
												<?php
												$start_performance_period = ''; 
												if(isset($_REQUEST['start_performance_period']))$start_performance_period = $_REQUEST['start_performance_period'];
												if(isset($award_arr['data'])){$start_performance_period = $award_arr['data']['start_performance_period'];}
												?>
												<input type="text" id="datepicker" name="start_performance_period" value="<?php echo $start_performance_period; ?>" class="form-control" />
												<span class="input-group-addon" id="btn" style="cursor:pointer;">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award End Performance Period :</label>
										<div class="col-md-6">
											<div class="input-group">
												<?php
												$end_performance_period = ''; 
												if(isset($_REQUEST['end_performance_period']))$end_performance_period = $_REQUEST['end_performance_period'];
												if(isset($award_arr['data'])){$end_performance_period = $award_arr['data']['end_performance_period'];}
												?>
												<input type="text" id="datepicker1" name="end_performance_period" value="<?php echo $end_performance_period; ?>" class="form-control" />
												<span class="input-group-addon" id="btn1" style="cursor:pointer;">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-md-6 control-label">Award do not share:</label>
										<div class="col-md-6">	
											<?php
											$do_not_share = ''; 
											if(isset($_REQUEST['do_not_share']))$do_not_share = $_REQUEST['do_not_share'];
											if(isset($award_arr['data'])){$do_not_share = $award_arr['data']['do_not_share'];}
											?>
											<input type="checkbox" name="do_not_share" value="Y" <?php if($do_not_share=='Y')echo 'checked="checked"'; ?>>
										</div>
									</div> 
									<div class="form-group">
										<div class="col-md-offset-6 col-md-6">
											<a href="" class="btn btn-default" style="margin-right:25px;">Cancel</a>
											<button type="submit" class="btn btn-primary save" name="save_requisition_award">Save</button>
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
										<h4><b>Budget </b></h4>
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
							if(count($edit_award_budget_arr['data'])>0)
							{	
								for($k=0; $k<count($edit_award_budget_arr['data']); $k++)
								{		
								?>	
									<tr class="Budget-info">						
										<td>
											<input type="text" class="form-control" name="cost_code[]" value="<?php echo $edit_award_budget_arr['data'][$k]['cost_code'];?>">
											<input type="hidden" class="form-control" name="budget_id_arr[]" value="<?php echo $edit_award_budget_arr['data'][$k]['budget_id']; ?>">
										</td>
										<td>
											<input type="text" class="form-control" name="code_description[]" value="<?php echo $edit_award_budget_arr['data'][$k]['code_description'];?>">
										</td>
										<td>
											<input type="text" class="form-control" name="budget_amount[]" value="<?php echo $edit_award_budget_arr['data'][$k]['budget_amount'];?>">
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
										<input type="text" class="form-control" name="cost_code[]" value="">
									</td>
									<td>
										<input type="text" class="form-control" name="code_description[]" value="">
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
			</form>
		</div>
	</div>
</div>

<!-- Table Data -->

<div class="container-fluid award disp-none">
	<table id="manage-parent-table" class="table table-striped" border="2" style="" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th class="text-center">Award Instrument Number</th>				
				<!-- <th class="text-center">Type</th> -->
				<th class="text-center">Name</th>
				<th class="text-center" style="width:280px">Description</th>
				<th class="text-center">Start Performance Period</th>
				<th class="text-center">End Performance Period</th>
				<th class="text-center">Implementing Mechanism Type</th>				
				<th class="text-center" style="width:304px">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($all_requisition_award_arr['data'])>0)
			{
				for($k=0; $k<count($all_requisition_award_arr['data']); $k++)
				{
					$url = API_HOST_URL."get_all_budget_of_award.php?award_number=".$all_requisition_award_arr['data'][$k]['award_number'];
					$award_budget_arr = requestByCURL($url); 
					?>	
					<tr class="parent-tbl">
						<td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['award_number'];?></td>
						<!-- <td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['type'];?></td> -->
						<td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['award_name'];?></td>
						<td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['award_description'];?></td>
						<td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['start_performance_period'];?></td>
						<td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['end_performance_period'];?></td>
						<td class="text-center"><?php echo $all_requisition_award_arr['data'][$k]['implementing_mechanism_type'];?></td>
						<td class="text-center">
							<form method="post" action="">			
								<div class="row">
									<ul class="list-inline">
									 	<li style="padding-right: 0">
									 		<form method="post" action="">
												<input type="hidden" name="edit_award_number" value="<?php echo $all_requisition_award_arr['data'][$k]['award_number'];?>">		
												<button type="submit" class="btn btn-success action" name="edit_award" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
												<button type="button" class="btn btn-danger action" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-times" aria-hidden="true"></i></button>
												<i class="btn btn-xs btn-default action arrow fa fa-arrow-down" data-toggle="tooltip" data-placement="top" title="BUDGET" onClick="showChild(this);" style="padding: 5px 8px; font-size: 12px;"> BUDGET</i>
											</form>
										</li>
										<li style="padding-left: 0; padding-right: 0;">
											<form method="post" action="award_clin_l1.php">
												<input type="hidden" name="award_number_clin" value="<?php echo $all_requisition_award_arr['data'][$k]['award_number'];?>">		
												<button type="submit" class="btn btn-default action" data-toggle="tooltip" data-placement="top" title="AWARD CLIN Level - 1" style="margin:0 2px; color:#000; font-size: 12px; text-decoration:none" >AWARD CLIN - L1</button> 
											</form>
										</li>
									</ul>
								</div>							
							</form>
						</td>
					</tr>
					<tr class="child-table disp-none">
						<td colspan="8">
							<div style="padding:10px;">
								<table id="manage-child-table" class="table table-striped" border="1" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="text-center">Budget Cost Code</th>
											<th class="text-center">Budget Code Description</th>
											<th class="text-center">Budget Amount</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if(count($award_budget_arr['data'])>0)
									{
										for($j=0; $j<count($award_budget_arr['data']); $j++)
										{
										?>	
											<tr>										
												<td class="text-center"><?php echo $award_budget_arr['data'][$j]['cost_code']; ?></td>
												<td class="text-center"><?php echo $award_budget_arr['data'][$j]['code_description']; ?></td>
												<td class="text-right" style="margin-right: 2px;"><?php echo $award_budget_arr['data'][$j]['budget_amount']; ?></td>								
											 </tr>
										<?php
										}
									}else echo '<tr><td colspan="3">No Data Available</td></tr>';
									?> 
									</tbody>								
								</table>
							</div>
						</td>
					</tr>
					<?php
				}
			}else echo '<tr><td colspan="8">No Award</td></tr>';
			?>  
		</tbody>
	</table>	
</div>

<br>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/append.js"></script>
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

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>