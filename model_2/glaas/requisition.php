<?php
include('config/config.inc.php');
include('include/function.inc.php');
## add new vendor wither thier address ============
$error = '';
if(isset($_REQUEST['save_requisition']))
{	
	$requisition_id = trim($_REQUEST['requisition_id']); 
	$requisition_number = trim($_REQUEST['requisition_number']); 
	$type = trim($_REQUEST['type']);
	$status = trim($_REQUEST['status']);
	$create_date = dateFormat(trim($_REQUEST['create_date']));
	$period_of_performance_start_date =  dateFormat(trim($_REQUEST['period_of_performance_start_date']));
	$period_of_performance_end_date =  dateFormat(trim($_REQUEST['period_of_performance_end_date']));
	
	## get association link information ==============
	 $link_to_type = $_REQUEST['link_to_type']; // this is array of type
	 $link_to_id = $_REQUEST['link_to_id'];  // this is array of assoc link 
	 $assoc_link_id_arr = $_REQUEST['assoc_link_id_arr']; // this is array of edit link id 
	 
	
	if($requisition_number=='')
	{
		$error = 'Requisition number shoul not be blank';
	}
	elseif($type=='')
	{
		$error = 'Please select Requisition type';
	}
	else
	{	
		if($requisition_id=='')
		{
			$insert_data = "insert into usaid_requisition set
			requisition_number = '".$requisition_number."',
			type = '".$type."',
			status = '".$status."',
			create_date = '".$create_date."',
			period_of_performance_start_date = '".$period_of_performance_start_date."',
			period_of_performance_end_date = '".$period_of_performance_end_date."'"; 
			$result_data = $mysqli->query($insert_data);
			
			if($result_data)
			{
			 
				for($i=0; $i<count($link_to_id); $i++)
				{
				
					if(!empty($link_to_id[$i]))
					{
						$insert_assoc_link = "insert into usaid_association_link set 
								associate_from_number='".$requisition_number."',
								link_to_type='".$link_to_type[$i]."',
								link_to_id='".$link_to_id[$i]."'"; 
						$result_assoc_link = $mysqli->query($insert_assoc_link);  	
					}
				} 
			}
				header("location:requisition.php");
				
		}
		else
		{
			$update_data = "update usaid_requisition set
			type = '".$type."',
			status = '".$status."',
			create_date = '".$create_date."',
			period_of_performance_start_date = '".$period_of_performance_start_date."',
			period_of_performance_end_date = '".$period_of_performance_end_date."'		
			where id='".$requisition_id."'"; 
			$result_data = $mysqli->query($update_data);
			if($result_data)
			{
				$url = API_HOST_URL."get_link_to_by_requisition.php?requisition_number=".$requisition_number;
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
								associate_from_number='".$requisition_number."',
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
									associate_from_number='".$requisition_number."',
									link_to_type='".$link_to_type[$i]."',
									link_to_id='".$link_to_id[$i]."'"; 
							$result_assoc_link = $mysqli->query($insert_assoc_link); 
						}	
						
					} 
				} 
				
				header("location:requisition.php");
			}
		}
	}
}

## API for get all requisition list=======
$url = API_HOST_URL."get_all_requisition.php";
$all_requisition_arr = requestByCURL($url);

if(isset($_REQUEST['edit_requisition']))
{
	$edit_requisition_number = trim($_REQUEST['edit_requisition_number']);
	$url = API_HOST_URL."get_requisition.php?requisition_number=".$edit_requisition_number;
	$requisition_arr = requestByCURL($url);
## get all assoc link for requisition =======	
	$url = API_HOST_URL."get_link_to_by_requisition.php?requisition_number=".$edit_requisition_number;
	$assoc_link_arr = requestByCURL($url);
	
}

if(isset($_REQUEST['delete_requisition']))
{
	$edit_requisition_number = trim($_REQUEST['edit_requisition_number']);
	removeRequisition($edit_requisition_number);
	header("location:requisition.php"); 
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
		</ul>
	</div>

	<div class="container-fluid" id="manage-vendor">
		<div class="form-title" style="width:155px;">
			<i class="fa fa-minus" aria-hidden="true"></i>Requisition
		</div>
		<div class="new-form-content">
			<div class="manage-info">
				<?php
				if($error!='')
				{
					echo '<div style="text-align:center; color:red; margin-bottom:10px;">'.$error.'</div>';	
				}	
				?>				
				<form class="form-horizontal" role="form" method="post" action="">
					<input type="hidden" name="requisition_id" value="<?php echo $requisition_arr['data']['requisition_id'];?>">
					<div class="form-group">
						<label for="requisition_number" class="col-md-3 control-label">Requisition Number:</label>
						<div class="col-md-3">								
							<?php
							$requisition_number = '';
							$flag = 0;
							if(isset($_REQUEST['requisition_number']))$requisition_number = $_REQUEST['requisition_number'];
							if(isset($requisition_arr['data'])){$requisition_number = $requisition_arr['data']['requisition_number']; $flag=1;}
							?>	
							<input type="text" class="form-control" <?php if($flag==1)echo 'readonly'; ?> name="requisition_number" value="<?php echo $requisition_number;?>">
						</div>
					</div>

					<div class="form-group">
						<label for="type" class="col-md-3 control-label">Requisition Type:</label>
						<div class="col-md-3">								
							<?php
							$type = '';
							if(isset($_REQUEST['type']))$type = $_REQUEST['type'];
							if(isset($requisition_arr['data']))$type = $requisition_arr['data']['type'];
							?>	
							<input type="radio"  name="type" value="Acquisition" <?php if($type=='')echo 'checked="checked"';if($type=='Acquisition')echo 'checked="checked"'; ?> style="margin-right:5px;" >Acquisition
							<input type="radio"  name="type" <?php if($type=='Assistance')echo 'checked="checked"'; ?> value="Assistance" style="margin-right:5px;">Assistance
						</div>
					</div> 

					<div class="form-group">
						<label for="status" class="col-md-3 control-label">Requisition Status:</label>
						<div class="col-md-3">								
							<?php
							$status = '';
							if(isset($_REQUEST['status']))$status = $_REQUEST['status'];
							if(isset($requisition_arr['data']))$status = $requisition_arr['data']['status'];
							?>	
							<select class="form-control" id="sel1" name="status">
								<option value="">Select</option>
								<option value="Incomplete" <?php if($status=='Incomplete')echo 'selected="selected"'; ?>>Incomplete</option>
								<option value="Pre-Approved" <?php if($status=='Pre-Approved')echo 'selected="selected"'; ?>>Pre-Approved</option>
								<option value="Rejected" <?php if($status=='Rejected')echo 'selected="selected"'; ?>>Rejected</option>
								<option value="Returned" <?php if($status=='Returned')echo 'selected="selected"'; ?>>Returned</option>
								<option value="In-Process" <?php if($status=='In-Process')echo 'selected="selected"'; ?>>In-Process</option>
								<option value="Approved" <?php if($status=='Approved')echo 'selected="selected"'; ?>>Approved</option>
								<option value="Canceled" <?php if($status=='Canceled')echo 'selected="selected"'; ?>>Canceled</option>
								<option value="Requires Re-approval" <?php if($status=='Requires Re-approval')echo 'selected="selected"'; ?>>Requires Re-approval</option>
							</select>							
						</div>
					</div> 
					<div class="form-group">
						<label for="create_date" class="col-md-3 control-label">Requisition Created Date:</label>
						<div class="col-md-3">
							<div class="input-group">								
							<?php
							$create_date = '';
							if(isset($_REQUEST['create_date']))$create_date = $_REQUEST['create_date'];
							if(isset($requisition_arr['data']))$create_date = $requisition_arr['data']['create_date'];
							?>
								<input type="text" id="datepicker2" class="form-control" name="create_date" value="<?php echo $create_date;?>" />
        							<span class="input-group-addon" id="btn2" style="cursor:pointer;">
                    					<span class="glyphicon glyphicon-calendar"></span>
        							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="requisition_performance_start_date" class="col-md-3 control-label">Requisition Period of Performance Start Date:</label>
						<div class="col-md-3">
							<div class="input-group">								
							<?php
							$period_of_performance_start_date = '';
							if(isset($_REQUEST['period_of_performance_start_date']))$period_of_performance_start_date = $_REQUEST['period_of_performance_start_date'];
							if(isset($requisition_arr['data']))$period_of_performance_start_date = $requisition_arr['data']['period_of_performance_start_date'];
							?>
								<input type="text" id="datepicker" class="form-control" name="period_of_performance_start_date" value="<?php echo $period_of_performance_start_date;?>" />
        							<span class="input-group-addon" id="btn" style="cursor:pointer;">
                    					<span class="glyphicon glyphicon-calendar"></span>
        							</span>
							</div>	
						</div>
					</div>

					<div class="form-group">
						<label for="requisition_performance_end_date" class="col-md-3 control-label">Requisition Period of Performance End Date:</label>
						<div class="col-md-3">
							<div class="input-group">								
							<?php
							$period_of_performance_end_date = '';
							if(isset($_REQUEST['period_of_performance_end_date']))$period_of_performance_end_date = $_REQUEST['period_of_performance_end_date'];
							if(isset($requisition_arr['data']))$period_of_performance_end_date = $requisition_arr['data']['period_of_performance_end_date'];
							?>
							
								<input type="text" id="datepicker1" class="form-control" name="period_of_performance_end_date" value="<?php echo $period_of_performance_end_date;?>" />
        							<span class="input-group-addon" id="btn1" style="cursor:pointer;">
                    					<span class="glyphicon glyphicon-calendar"></span>
        							</span>
							</div>	
						</div>
					</div> 
					<?php
					if(count($assoc_link_arr['data'])>0)  ## fetch from api data
					{	
						for($k=0; $k<count($assoc_link_arr['data']); $k++)
						{		
							?>
							<div class="req_link_to"> 
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
										<!--<option value="DOAG" <?php if($assoc_link_arr['data'][$k]['link_to_type']=='DOAG') echo "selected"; ?>>DOAG</option> -->
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
					<div class="req_link_to"> 
						<div class="form-group">
							<div class="col-md-12"> 
								<a class="remove_link_to_blk btn btn-danger pull-right disp-none"><span class="fa fa-times"></span></a>
								<div style="height:5px; clear:both"></div>
							</div>
							<label for="associate_type" class="col-md-6 control-label"> Link To:</label>
							<div class="col-md-6"> 
								<select class="form-control associate_type" id="sel1" name="link_to_type[]" onChange="showAssociate(this);">
									<option value="">Select</option>
									<option value="Project">Project</option>  
									<option value="Project Activity">Project Activity</option> 
								<!--	<option value="DOAG">DOAG</option>  -->
								</select>
							</div>	
					</div> 
						<div class="form-group associate disp-none"> 
						</div>
					</div>
					 	
			<?php 	} ?>					
					<div class="form-group">
						<div class="col-md-offset-3 col-md-3">
							<button type="button" class="pull-right btn btn-primary" id="add_more_link_to">Add Link</button>
							<div class="clearfix"></div>
						</div>
					</div>	
						
					<div class="form-group">
						<div class="col-md-offset-3 col-md-3">
							<a href="" class="btn btn-default" style="margin-right:25px;">Cancel</a>
							<button type="submit" class="btn btn-primary" name="save_requisition" >Save</button>
						</div>
					</div>					
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Table Data -->

<div class="container-fluid">
	<table id="manage-parent-table" class="table table-striped" border="2" style="" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th class="text-center" width="170">Requisition Number</th>
				<th class="text-center">Type</th>
				<th class="text-center">Status</th>				
				<th class="text-center">Created Date</th>				
				<th class="text-center" width="15%">Period of Performance Start Date</th>
				<th class="text-center" width="15%">Period of Performance End Date</th>
				<th class="text-center">Linked To</th>
				<th class="text-center" style="width:220px">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			for($i=0; $i<count($all_requisition_arr['data']); $i++)
			{
					$url = API_HOST_URL."get_link_to_by_requisition.php?requisition_number=".$all_requisition_arr['data'][$i]['requisition_number'];
					$list_assoc_link_arr = requestByCURL($url);
				?>	
				<tr class="parent-link">
					<td class="text-center"><span onClick="showClinOne(this)" class="btn action" title="Level-1"> <img src="images/plus.png"></span> <?php echo $all_requisition_arr['data'][$i]['requisition_number']; ?></td>
					<td class="text-center"><?php echo $all_requisition_arr['data'][$i]['type']; ?></td>
					<td class="text-center"><?php echo $all_requisition_arr['data'][$i]['status']; ?></td>
					<td class="text-center"><?php echo $all_requisition_arr['data'][$i]['create_date']; ?></td>			
					<td class="text-center"><?php echo $all_requisition_arr['data'][$i]['period_of_performance_start_date']; ?></td>
					<td class="text-center"><?php echo $all_requisition_arr['data'][$i]['period_of_performance_end_date']; ?></td>
					<td class="text-center">
						<button type="button" class="btn btn-default action" data-placement="top" onClick="showLink(this);">Show Link</button>
						<!-- <a href="javascript:void(0)" class="show-link">Show Link</a> -->
					</td>			
					<td class="text-center">
						<div class="row">
							<ul class="list-inline">
								<li>
									<form method="post" action="" class="form-inline"> 
										<input type="hidden" name="edit_requisition_number" value="<?php echo $all_requisition_arr['data'][$i]['requisition_number']; ?>">	
										<button type="submit" name="edit_requisition" class="btn btn-success action list-inline" data-toggle="tooltip" data-placement="top" title="Edit" style="margin-right: 5px;"><i class="fa fa-pencil" aria-hidden="true"></i></button>
										<button type="submit" name="delete_requisition" class="btn btn-danger action list-inline" data-toggle="tooltip" data-placement="top" title="Remove" onClick="return window.confirm('Are you sure you want to remove this?');"><i class="fa fa-times" aria-hidden="true"></i></button>									 
									</form>
								</li>
								<li>
									<form method="post" action="clin_l1.php" class="form-inline"> 
										<input type="hidden" name="requisition_number" value="<?php echo $all_requisition_arr['data'][$i]['requisition_number']; ?>">	
										<button type="submit" class="btn btn-default action list-inline" data-toggle="tooltip" data-placement="top" title="CLIN - L1" style="margin:0 2px; color:#000; font-size: 12px; text-decoration:none"> CLIN - L1</button>									
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
								for($j=0; $j<count($list_assoc_link_arr['data']); $j++)
								{
									?>
									<tr>										
										<td><?php echo $list_assoc_link_arr['data'][$j]['link_to_id']; ?></td>
										<td><?php echo $list_assoc_link_arr['data'][$j]['link_to_type']; ?></td>
									</tr>
							<?php } ?>
								</tbody>								
							</table>
						</div>
					</td>
				</tr>

				<tr class="child-clin1 disp-none">
					<td colspan="9">
						<div style="padding:10px;">
							<table id="manage-parent-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
								<thead>
									<tr>
									<th class="text-left">L1 <span style="margin-left:30px;"> CLIN Number </span></th>				
									<th class="text-center">Name</th>
									<th class="text-center">Description</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Start Performance Period</th>
									<th class="text-center">End Performance Period</th>										
									</tr>
								</thead>
								<tbody>
								<?php
						## get all clin_l1 of this requisition
						$url = API_HOST_URL."get_all_clin_l1_by_requisition.php?requisition_number=".$all_requisition_arr['data'][$i]['requisition_number'];
						$requisition_clin_l1_arr = requestByCURL($url);
						if(count($requisition_clin_l1_arr['data'])>0)
							{		
								for($n=0; $n<count($requisition_clin_l1_arr['data']); $n++)
								{	
								?>
									<tr class="parent-tbl">										
										<td><span onClick="showClinTwo(this)" class="btn action" title="Level-1"> <img src="images/plus.png"></span><?php echo $requisition_clin_l1_arr['data'][$n]['clin_number']; ?></td>
										<td><?php echo $requisition_clin_l1_arr['data'][$n]['clin_name']; ?></td>
										<td><?php echo $requisition_clin_l1_arr['data'][$n]['clin_description']; ?></td>
										<td><?php echo '$'.number_format($requisition_clin_l1_arr['data'][$n]['clin_amount']); ?></td>
										<td><?php echo $requisition_clin_l1_arr['data'][$n]['start_performance_period']; ?></td>
										<td><?php echo $requisition_clin_l1_arr['data'][$n]['end_performance_period']; ?></td>
									</tr>
							
						<tr class="child-clin2 disp-none">
									<td colspan="9">
										<div style="padding:10px;">
											<table id="manage-parent-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
												<thead>
													<tr>
													<th class="text-left">L2 <span style="margin-left:30px;"> CLIN Number </span></th>				
													<th class="text-center">Name</th>
													<th class="text-center">Description</th>
													<th class="text-center">Amount</th>
													<th class="text-center">Start Performance Period</th>
													<th class="text-center">End Performance Period</th>										
													</tr>
												</thead>
												<tbody>
												
												<?php 
											## get all clin_l2 of this clin level 1
											$url = API_HOST_URL."get_all_clin_l2_by_clin1.php?clin_l1_number=".$requisition_clin_l1_arr['data'][$n]['clin_number']; 
											$requisition_clin_l2_arr = requestByCURL($url); 
											if(count($requisition_clin_l2_arr['data'])>0)
											{
												for($j=0; $j<count($requisition_clin_l2_arr['data']); $j++)
													{	?>
														<tr class="child-clin3">										
															<td><span onClick="showClinThree(this)" class="btn action" title="Level-2"> <img src="images/plus.png"></span> <?php echo $requisition_clin_l2_arr['data'][$j]['clin_number']; ?></td>
															<td><?php echo $requisition_clin_l2_arr['data'][$j]['clin_name']; ?></td>
															<td><?php echo $requisition_clin_l2_arr['data'][$j]['clin_description']; ?></td>
															<td><?php echo '$'.number_format($requisition_clin_l2_arr['data'][$j]['clin_amount']); ?></td>
															<td><?php echo $requisition_clin_l2_arr['data'][$j]['start_performance_period']; ?></td>
															<td><?php echo $requisition_clin_l2_arr['data'][$j]['end_performance_period']; ?></td>
														</tr>
														<tr class="child-clin4 disp-none">
														<td colspan="9">
															<div style="padding:10px;">
																<table id="manage-parent-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
																	<thead>
																		<tr>
																		<th class="text-left">L3 <span style="margin-left:30px;"> CLIN Number </span></th>				
																		<th class="text-center">Name</th>
																		<th class="text-center">Description</th>
																		<th class="text-center">Amount</th>
																		<th class="text-center">Start Performance Period</th>
																		<th class="text-center">End Performance Period</th>										
																		</tr>
																	</thead>
																	<tbody>
																<?php 
																## get all clin_l3 of this clin level 2
																$url = API_HOST_URL."get_all_clin_l3_by_clin2.php?clin_l2_number=".$requisition_clin_l2_arr['data'][$j]['clin_number'];
																$requisition_clin_l3_arr = requestByCURL($url); 
																if(count($requisition_clin_l3_arr['data'])>0)
																{	
																	for($m=0; $m<count($requisition_clin_l3_arr['data']); $m++)
																		{	
																	?>
																			<tr class="child-clin5">										
																				<td><span onClick="showClinFour(this)" class="btn action" title="Level-3"> <img src="images/plus.png"></span><?php echo $requisition_clin_l3_arr['data'][$m]['clin_number']; ?></td>
																				<td><?php echo $requisition_clin_l3_arr['data'][$m]['clin_name']; ?></td>
																				<td><?php echo $requisition_clin_l3_arr['data'][$m]['clin_description']; ?></td>
																				<td><?php echo '$'.number_format($requisition_clin_l3_arr['data'][$m]['clin_amount']); ?></td>
																				<td><?php echo $requisition_clin_l3_arr['data'][$m]['start_performance_period']; ?></td>
																				<td><?php echo $requisition_clin_l3_arr['data'][$m]['end_performance_period']; ?></td>
		
																			</tr>
																			
																			<tr class="child-clin6 disp-none">
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
																				$url = API_HOST_URL."get_all_clin_l4_by_clin3.php?clin_l3_number=".$requisition_clin_l3_arr['data'][$m]['clin_number'];
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
																	<?php }
																		} else { echo '<tr><td colspan="6">No Clin Level Available</td></tr>'; } ?>		
																			
																	</tbody>								
																</table>
															</div>
														</td>
														</tr>
												<?php }
													} else { echo '<tr><td colspan="6">No Clin Level Available</td></tr>'; } ?>
												</tbody>								
											</table>
										</div>
									</td>
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
			?>
		</tbody>
	</table>	
</div>

<br>

<script src="js/jquery-ui.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>