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
else
{
	header('location:index.php');
}

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
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="framework_management.php" class="remove-line"> Framework Management</a> >> Archive</div>
			</div>
		</div>
	</div> 
	<!-- Pop Up Dialog Box  -->
	<div class="container-fluid" id="show_list" style="display:block">
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<td colspan="6"> List of archived frame </td>
				</tr>
				<tr>
					<th class="text-center">Frame Name</th>
					<th class="text-center">Status</th>
					<th class="text-center">Added On</th>
					<th class="text-center">Archived On</th> 
					<th class="text-center">History</th> 
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
   					<td><h5><?php $date_time=$fetch_all_arcive_frame['added_on']; echo dateFormatView($date_time);   ?></h5></td>
					<td><h5><?php echo dateFormatView($date_time); ?></h5></td>
					<td>
					<?php  
					$select_archives_frame="select * from usaid_archive_frame where frame_id = '".$fetch_all_arcive_frame['id']."'";
					$result_archives_frame = $mysqli->query($select_archives_frame);
					$total_num_row = $result_archives_frame->num_rows; if($total_num_row>0) {?>
					<a href="history_list_view.php?history_id=<?php echo $fetch_all_arcive_frame['id']; ?>">View History </a> <?php } else echo "No History"; ?> </td>
					
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