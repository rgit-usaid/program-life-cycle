<?php
include('config/config.inc.php');
include('include/function.inc.php');

//## get Detail operating unit ===========
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id'];
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
## Create frame  ===========
if(isset($_REQUEST['frame_name']))
	{	
		$frame_name = trim($_REQUEST['frame_name']); 
		if($frame_name!='')
		{
			$insert_data = "insert into usaid_frame set
			frame_name = '".$frame_name."',
			added_on = NOW(),
			operating_unit_id = '".$operating_unit_id."'";
			$result_data = $mysqli->query($insert_data);
			$current_id = $mysqli->insert_id;
			if($result_data)
			{
				$_SESSION['frame_id']= $current_id;
				$_SESSION['frame_name']= $frame_name;
				
				## Count draft frame if draft is only one than goto edit frame page=============
				$select_draft_frame="select * from usaid_frame where operating_unit_id='".$operating_unit_id."' and status='Draft'";
				$total_draft_res = $mysqli->query($select_draft_frame);
				if($total_draft_res->num_rows<=1)
				{
					header("location:add_frame.php?active_frame_id=$current_id");
				}
				else
				{
					header('location:framework_management.php');
				}
			} 
		}
	}
	
## Delete frame and insert into archive list of delete frame ===================	
if(isset($_REQUEST['delete']))
	{	
		$delete_id = trim($_REQUEST['delete_id']); 
		if($delete_id!='')
			{
				$frame_id = $delete_id;
				$insert_archive_frame_data = "insert into usaid_archive_frame set
					frame_id = '".$frame_id."'";
				$result_data = $mysqli->query($insert_archive_frame_data);
				$archive_frame_id = $mysqli->insert_id;
				## get archived frame id for insert data in chield archives table from frame chield tables =============
				if($archive_frame_id!='')
				{
					$select_goal="select * from usaid_development_goal where frame_id='$frame_id'";
					$total_res = $mysqli->query($select_goal);
					## insert usaid_archive_development_goal data from  usaid_development_goal =========
					if($total_res->num_rows>0)
					{
						while($fetch_goal_data = $total_res->fetch_array())
						{
							if($fetch_goal_data['location']!='')
							{
								$insert_archive_development_goal_data = "insert into usaid_archive_development_goal set
								development_goal_id = '".$fetch_goal_data['id']."',
								archive_id = '".$archive_frame_id."',
								gohashid = '".$fetch_goal_data['gohashid']."',
								goal_description = '".$fetch_goal_data['goal_description']."',
								operating_unit_id = '".$fetch_goal_data['operating_unit_id']."',
								location = '".$fetch_goal_data['location']."',
								goal_approval_date = '".$fetch_goal_data['goal_approval_date']."'";
								$result_data = $mysqli->query($insert_archive_development_goal_data);
								## Check association link and after insert association link from usaid_association to usaid_archive_association table ==============
								$select_association="select * from usaid_association where gohashid='".$fetch_goal_data['gohashid']."'";
								$total_association_res = $mysqli->query($select_association);
								if($total_association_res->num_rows>0)
								{
									while($fetch_association_data = $total_association_res->fetch_array())
									{
										$insert_archive_assoc = "insert into usaid_archive_association set 
										association_table_id = '".$fetch_association_data['id']."',
										archive_id = '".$archive_frame_id."',
										gohashid='".$fetch_association_data['gohashid']."',
										association_type='".$fetch_association_data['association_type']."',
										association_id='".$fetch_association_data['association_id']."',
										association_value='".$fetch_association_data['association_value']."'"; 
										$result_archive_assoc = $mysqli->query($insert_archive_assoc);
									}	 
								}
							}	
						}
					}
					## insert usaid_archive_development_objective data from  usaid_development_objective =========
					$select_DO="select * from usaid_development_objective where frame_id='$frame_id'";
					$total_DO_res = $mysqli->query($select_DO);
					if($total_DO_res->num_rows>0)
					{
						while($fetch_DO_data = $total_DO_res->fetch_array())
						{
						if($fetch_DO_data['location']!='')
							{
								$insert_archive_development_objective = "insert into usaid_archive_development_objective set
								development_objective_id = '".$fetch_DO_data['id']."',
								archive_id = '".$archive_frame_id."',
								gohashid = '".$fetch_DO_data['gohashid']."',
								objective_description = '".$fetch_DO_data['objective_description']."',
								operating_unit_id = '".$fetch_DO_data['operating_unit_id']."',
								location = '".$fetch_DO_data['location']."',
								objective_approval_date = '".$fetch_DO_data['objective_approval_date']."'"; 
								$result_data = $mysqli->query($insert_archive_development_objective);
								## Check association link and after insert association link from usaid_association to usaid_archive_association table ==============
								$select_association="select * from usaid_association where gohashid='".$fetch_DO_data['gohashid']."'";
								$total_association_res = $mysqli->query($select_association);
								if($total_association_res->num_rows>0)
								{
									while($fetch_association_data = $total_association_res->fetch_array())
									{
										$insert_archive_assoc = "insert into usaid_archive_association set 
										association_table_id = '".$fetch_association_data['id']."',
										archive_id = '".$archive_frame_id."',
										gohashid='".$fetch_association_data['gohashid']."',
										association_type='".$fetch_association_data['association_type']."',
										association_id='".$fetch_association_data['association_id']."',
										association_value='".$fetch_association_data['association_value']."'"; 
										$result_archive_assoc = $mysqli->query($insert_archive_assoc);
									}	 
								}
							}	
						}
					}	
					## insert usaid_archive_intermediate_result data from  usaid_intermediate_result =========
					$select_IR="select * from usaid_intermediate_result where frame_id='$frame_id'";
					$total_IR_res = $mysqli->query($select_IR);
					if($total_IR_res->num_rows>0)
					{
						while($fetch_IR_data = $total_IR_res->fetch_array())
						{
							if($fetch_IR_data['location']!='')
							{
								$insert_archive_intermediate_result = "insert into usaid_archive_intermediate_result set
								intermediate_result_id = '".$fetch_IR_data['id']."',
								archive_id = '".$archive_frame_id."',
								gohashid = '".$fetch_IR_data['gohashid']."',
								ir_description = '".$fetch_IR_data['ir_description']."',
								operating_unit_id = '".$fetch_IR_data['operating_unit_id']."',
								location = '".$fetch_IR_data['location']."',
								ir_approval_date = '".$fetch_IR_data['ir_approval_date']."'"; 
								$result_data = $mysqli->query($insert_archive_intermediate_result);
								## Check association link and after insert association link from usaid_association to usaid_archive_association table ==============
								$select_association="select * from usaid_association where gohashid='".$fetch_IR_data['gohashid']."'";
								$total_association_res = $mysqli->query($select_association);
								if($total_association_res->num_rows>0)
								{
									while($fetch_association_data = $total_association_res->fetch_array())
									{
										$insert_archive_assoc = "insert into usaid_archive_association set 
										association_table_id = '".$fetch_association_data['id']."',
										archive_id = '".$archive_frame_id."',
										gohashid='".$fetch_association_data['gohashid']."',
										association_type='".$fetch_association_data['association_type']."',
										association_id='".$fetch_association_data['association_id']."',
										association_value='".$fetch_association_data['association_value']."'"; 
										$result_archive_assoc = $mysqli->query($insert_archive_assoc);
									}	 
								}
							}
						}
					} 
					## insert usaid_archive_sub_intermediate_result data from  usaid_sub_intermediate_result =========
					$select_Sub_IR="select * from usaid_sub_intermediate_result where frame_id='$frame_id'";
					$total_Sub_IR_res = $mysqli->query($select_Sub_IR);
					if($total_Sub_IR_res->num_rows>0)
					{
						while($fetch_Sub_IR_data = $total_Sub_IR_res->fetch_array())
						{
							if($fetch_Sub_IR_data['location']!='')
							{
								$insert_archive_sub_intermediate_result = "insert into usaid_archive_sub_intermediate_result set
								sub_intermediate_result_id = '".$fetch_Sub_IR_data['id']."',
								archive_id = '".$archive_frame_id."',
								gohashid = '".$fetch_Sub_IR_data['gohashid']."',
								sub_ir_description = '".$fetch_Sub_IR_data['sub_ir_description']."',
								operating_unit_id = '".$fetch_Sub_IR_data['operating_unit_id']."',
								location = '".$fetch_Sub_IR_data['location']."',
								sub_ir_approval_date = '".$fetch_Sub_IR_data['sub_ir_approval_date']."'"; 
								$result_data = $mysqli->query($insert_archive_sub_intermediate_result);
								## Check association link and after insert association link from usaid_association to usaid_archive_association table ==============
								$select_association="select * from usaid_association where gohashid='".$fetch_Sub_IR_data['gohashid']."'";
								$total_association_res = $mysqli->query($select_association);
								if($total_association_res->num_rows>0)
								{
									while($fetch_association_data = $total_association_res->fetch_array())
									{
										$insert_archive_assoc = "insert into usaid_archive_association set 
										association_table_id = '".$fetch_association_data['id']."',
										archive_id = '".$archive_frame_id."',
										gohashid='".$fetch_association_data['gohashid']."',
										association_type='".$fetch_association_data['association_type']."',
										association_id='".$fetch_association_data['association_id']."',
										association_value='".$fetch_association_data['association_value']."'"; 
										$result_archive_assoc = $mysqli->query($insert_archive_assoc);
									}	 
								}
							}	
						}
					}
					
					## insert usaid_archive_data_relation data from  usaid_data_relation =========
					$select_data_relation="select * from usaid_data_relation where frame_id='$frame_id'";
					$total_data_relation_res = $mysqli->query($select_data_relation);
					if($total_data_relation_res->num_rows>0)
					{
						while($fetch_data_relation = $total_data_relation_res->fetch_array())
						{
							 $insert_archive_data_relation = "insert into usaid_archive_data_relation set 
							 	data_relation_id = '".$fetch_data_relation['id']."',
								archive_id='".$archive_frame_id."',
								from_id='".$fetch_data_relation['from_id']."',
								from_type='".$fetch_data_relation['from_type']."',
								to_id='".$fetch_data_relation['to_id']."',
								to_type='".$fetch_data_relation['to_type']."',
								from_port='".$fetch_data_relation['from_port']."',
								to_port='".$fetch_data_relation['to_port']."',
								location='".$fetch_data_relation['location']."'"; 
							 $result_data_relation = $mysqli->query($insert_archive_data_relation);
						}
					}
				}
			$updata_frame = "update usaid_frame set
			status = 'Delete', modified_on = NOW() where id='".$delete_id."'";
			$result_data = $mysqli->query($updata_frame);
		}
	}	
## Move frame into archived list ===================	
	if(isset($_REQUEST['move_archive']))
	{	
		$frame_active_id = trim($_REQUEST['frame_active_id']); 
		if($frame_active_id!='')
		{
			$updata_frame = "update usaid_frame set
			status = 'Archive', modified_on = NOW() where id='".$frame_active_id."'";
			$result_data = $mysqli->query($updata_frame);
		}
	}	
	
## Goto active frame ==================	
	if(isset($_REQUEST['make_active']))
	{	
		$frame_draft_id = trim($_REQUEST['frame_draft_id']); 
		if($frame_draft_id!='')
		{
			$updata_frame = "update usaid_frame set
			status = 'Active', modified_on = NOW() where id='".$frame_draft_id."'";
			$result_data = $mysqli->query($updata_frame);
		}
	}	

## Fetch frame accourding to operating unit================
	$select_frame_data = "select * from usaid_frame where operating_unit_id='".$operating_unit_id."' and status='Draft' order by id desc";
	$result_data = $mysqli->query($select_frame_data);
	$count_all_draft_frame = $result_data->num_rows;
	
	$select_frame_active = "select * from usaid_frame where operating_unit_id='".$operating_unit_id."' and status='Active' order by id desc limit 1";
	$result_active_data = $mysqli->query($select_frame_active);
	$fetch_active_frame = $result_active_data->fetch_array();
	
	$select_all_arcive_frame = "select * from usaid_frame where status='Archive' and operating_unit_id='".$operating_unit_id."'";
	$result_archived = $mysqli->query($select_all_arcive_frame);
	$total_archived_list=$result_archived->num_rows; 
$page_name="framework_management";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>USAID</title>
	<!-- Bootstrap -->
	<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">
	
	<script>
	function openArchiveDiv()
	{
		if(document.getElementById('show_list').style.display == "block"){
			document.getElementById('show_list').style.display = "none";
		}else{
			document.getElementById('show_list').style.display = "block";
		}
	}
	</script>
		
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="framework_management.php" class="remove-line"> Framework Management</a> >> Draft</div>
			</div>
		</div>
	</div>

	<!-- Frame Menu -->
	<div class="container-fluid">
		<div class="col-md-4 text-center">

			<button class="usa-button usa-button-outline-inverse active-class-white active" data-toggle="modal" data-target="#frameName">Create New Frame</button>
		</div>
		<div class="col-md-4 text-center">
			<?php if($fetch_active_frame!=''){ ?>
			<a class="usa-button usa-button-outline-inverse active-class-white disable active" href="add_frame.php?active_frame_id=<?php echo $fetch_active_frame['id']; ?>">Go to Active Frame</a>  <?php  } else { ?>
			<button class="usa-button usa-button-outline active-class-white disable active" disabled="disabled">No Active Frame</button>
			<?php  } ?>
		</div>
		<div class="col-md-4 text-center">
		<?php if($total_archived_list>0) { ?>
		
		<a class="usa-button usa-button-outline-inverse active-class-white disable active" href="archived_list.php">Go to Archived Frames </a>
		<?php } else {  ?>
		<button  class="usa-button usa-button-outline active-class-white disable active" disabled="disabled">No Archived Frames </button>
		<?php  } ?>
			<!--<a class="usa-button usa-button-outline-inverse active-class-white disable active" onClick="openArchiveDiv();" href="">Go to Archived Frames</a> -->
		</div>
	</div>

	<div class="container-fluid">
		<?php if($count_all_draft_frame==0) {?>
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td colspan="5"><h4> No Draft Available</h4></td>
				</tr>
			
			</tbody>
		</table>
		<?php } else { ?>
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<td colspan="7" style="background:white;"> List of Draft Frame </td>
				</tr>
				<tr>
					<th class="text-center">Frame Name</th>
					<th class="text-center">Status</th>
					<th class="text-center">Added On</th>
					<th class="text-center">Modified On</th>
					<th class="text-center">History</th>
					<th class="text-center">Action</th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
			<?php while($fetch_all_draft_frame = $result_data->fetch_array()) {  ?>
				<tr>
					<td><h4><?php echo ucwords($fetch_all_draft_frame['frame_name']); ?></h4></td>
					<td><h4>Draft</h4></td>
					<td><h4><?php echo dateFormatView($fetch_all_draft_frame['added_on']); ?></h4></td>
					<td><h4><?php echo dateFormatView($fetch_all_draft_frame['modified_on']); ?></h4></td>
					<td><?php  $select_archives_frame="select * from usaid_archive_frame where frame_id = '".$fetch_all_draft_frame['id']."'";
	$result_archives_frame = $mysqli->query($select_archives_frame);
	$total_num_row = $result_archives_frame->num_rows; if($total_num_row>0) {?>
					<a href="history_list_view.php?history_id=<?php echo $fetch_all_draft_frame['id']; ?>">View History </a> <?php } else echo "No History"; ?> </td>
					<td class="text-center">
							<form method="post">
								<input type="hidden" name="delete_id" value="<?php  echo $fetch_all_draft_frame['id']; ?>">
						<button name="delete" class="usa-button-outline" onClick="if(confirm('Are you sure, you want to delete this frame?')){ return true;} else { return false; }">Delete</button><a class="usa-button usa-button-outline active-class-white disable active" href="add_frame.php?active_frame_id=<?php echo $fetch_all_draft_frame['id']; ?>">Edit</a>
							</form>
					</td>
					<td class="text-center">
							<?php if($fetch_active_frame=='') {  ?>
							<form method="post">
								<input type="hidden" name="frame_draft_id" value="<?php  echo $fetch_all_draft_frame['id']; ?>">
						<button name="make_active"  onClick="if(confirm('Are you sure, you want to make this frame active?')){ return true;} else { return false; }">Move to Active</button>
							</form>
							<?php } else { ?>
							<button name=""  onClick="alert('One frame is already active')" title="One Frame is Already Active">Move to Active</button>
							<?php }?>	
					</td>
				</tr>
			<?php }?>
			
			</tbody>
		</table>
		<?php } ?>
	</div>
	<!-- Pop Up Dialog Box  -->
	<div class="container-fluid" id="show_list" style="display:none">
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="text-center">Frame Name</th>
					<th class="text-center">Status</th>
					<th class="text-center">Added On</th>
					<th class="text-center">Archived On</th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
			<?php   
					
					$i=1;
					while($fetch_all_arcive_frame = $result_archived->fetch_array())
					 { ?>
				<tr>
					<td><h5><?php echo ucwords($fetch_all_arcive_frame['frame_name']); ?></h5></td>
					<td><h5><?php echo $fetch_all_arcive_frame['status']; ?></h4></td>
   					<td><h5><?php $date_time=$fetch_all_arcive_frame['added_on']; echo $date = date('Y-m-d', strtotime($date_time));   ?></h5></td>
					<td><h5><?php echo $fetch_all_arcive_frame['modified_on']; ?></h5></td>
					<td class="text-center">
					<a class="usa-button usa-button-outline-inverse active-class-white disable active" href="archive_frame.php?active_frame_id=<?php echo $fetch_all_arcive_frame['id']; ?>">View Frame</a>
					</td>
				</tr>
			<?php $i++;   } ?>
			
			</tbody>
		</table>
	</div> 
	
	<div id="frameName" class="modal fade" role="dialog">
		<div class="modal-dialog">
		
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
					<legend class="usa-drop_text">Framework</legend>
				</div>
				<div class="modal-body">
					<form class="usa-form" action="" method="post">
						<fieldset>
							<div class="form-group">
								<label for="input-type-textarea">Framework Name</label>
								<input id="input-type-text" name="frame_name" type="text" placeholder="Please Enter Framework Name" required>
							</div>
							<!-- <a class="usa-button usa-button-outline-inverse active-class-white" href="add_frame.php">Save</a> -->
							<input type="submit" value="Save" style="margin-top:40px; margin-bottom: 0;" />
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

	
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/uswds.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			$(".make-active").click(function() {
				$(".active-class-white").removeClass('disable');
			});
		});
	</script>
</body>
</html>