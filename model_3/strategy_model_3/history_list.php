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
	$select_all_arcive_frame = "select af.*,f.frame_name,f.status,f.added_on 
								from usaid_archive_frame as af
								left join usaid_frame as f ON f.id = af.frame_id
								where operating_unit_id='".$operating_unit_id."' and modified_on is not null group by af.frame_id";
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
	<style>
	.parent-tbl h5{margin:1px !important;}
	.parent-iframe { height:5px;}
	.btn-default{padding:1px 2px !important;}
	.sub-table{background:#D3D3D3 !important;} 
	.sub-table{ background: #A9A9A9 !important;} 
	.manage-child-table{margin:0 !important;}
	</style>
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href="framework_management.php">Back </a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12 info">Operating Unit: <span class="disc"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></span></div>
			</div>
		</div>
	</div>
	<!-- Pop Up Dialog Box  -->
	<div class="container-fluid" id="show_list" style="display:block">
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<td colspan="4"> List of archives frame </td>
				</tr>
				<tr>
					<th class="text-center">Frame Name</th>
					<th class="text-center">Status</th>
					<th class="text-center">Added On</th>
					<th class="text-center">Archives List</th>
				</tr>
			</thead>
			<tbody>
			<?php	$i=1;
					while($fetch_all_arcive_frame = $result_archived->fetch_array())
						 { ?>
						<tr class="parent-tbl" >
							<td ><h5><?php echo ucwords($fetch_all_arcive_frame['frame_name']); ?></h5></td>
							<td><h5><?php echo $fetch_all_arcive_frame['status']; ?></h4></td>
							<td><h5><?php $date_time=$fetch_all_arcive_frame['added_on']; echo $date = date('Y-m-d', strtotime($date_time)); ?></h5></td>
							<td><i class="btn btn-xs fa fa-chevron-circle-down" style="margin-right:20px;" onClick="showChild(this);"></i></td> 
							<tr class="child-link" style="display:none">
							<td colspan="8">
								<div style="padding:-2px;">
									<table id="manage-child-table" class="table table-striped manage-child-table"  cellspacing="0" width="90%" border="0">
										<thead>
											<tr class="collapse in" >					
												<th class="text-center sub-table">Archive On</th> 
												<th class="text-center sub-table">View Frame</th>
											</tr>
										</thead>
										<tbody>
									 <?php $select_archives_frame_list="select af.*,f.frame_name
																	from usaid_archive_frame as af 
																	left join usaid_frame as f ON f.id = af.frame_id
																	where af.frame_id = '".$fetch_all_arcive_frame['frame_id']."' order by archived_on desc";
											$result_archives_frame = $mysqli->query($select_archives_frame_list);
											if($result_archives_frame->num_rows>0)
											{
												while($fetch_archives_frame = $result_archives_frame->fetch_array()) 
												{
										?>		
												<tr class="parent-iframe">
													<td><?php echo date( "Y-m-d h:i", strtotime($fetch_archives_frame['archived_on']));?></td> 
													<td colspan="2" class="show-view" onClick="showArchiveIframe(this,<?php echo $fetch_archives_frame['id']; ?>);"> <span class="btn btn-default action" data-placement="top">Show Frame</span></i></td> 
												</tr>
												<tr class="child-iframe" style="display:none">
													<td colspan="2">
														<table  class="table inner-tbl"  cellspacing="0" style="border:none !important;">
															<tbody>
																<tr>
																	<td class="show-iframe"></td>  									
																</tr>
															</tbody>								
														</table>
													</td>
												</tr>
									  <?php }		
												 } 
												 else 
												 { echo '<tr><td colspan="3">No Archive Available</td></tr>'; } ?>	
										</tbody>								
									</table>
								</div>
							</td>
						</tr>
						</tr>
			<?php $i++;  } ?>
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

	function showChild(elem)
	{
		//alert($(elem).closest('.parent-tbl').length);
		$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-link').toggle();
	}

/* show iframe of archive view */
function showArchiveIframe(elem,archive_frame_id)
{ 
	$.ajax({
		context: $(elem),
		type: 'post',
		url: 'ajaxfiles/get_archive_frame.php',
		data: {
			archive_frame_id:archive_frame_id
		}, 
		success: function (data) {

			if($(elem).closest('.parent-iframe').next('.child-iframe').css('display')=="none"){
				$(elem).closest('.parent-iframe').next('.child-iframe').find('.show-iframe').html(data);
				$(elem).closest('.parent-iframe').next('.child-iframe').css('display','table-row');
				$(elem).closest('.parent-iframe').find('.show-view').html('<span class="btn btn-default action" data-placement="top">Hide Frame</span>');
			}
			else{
				$(elem).closest('.parent-iframe').next('.child-iframe').css('display','none');
				$(elem).closest('.parent-iframe').find('.show-view').html('<span class="btn btn-default action" data-placement="top">Show Frame</span>');
			}
		}
	});	
}
	</script>
</body>
</html>