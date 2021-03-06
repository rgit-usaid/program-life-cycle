<?php 
	$clp_sel ="cdcs";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>USAID-4</title>
	<link href="css/uswds.min.css" rel="stylesheet">
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		
		.disp-none{
			display: none;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="activity_list.php">Edit Activity Info</a> &raquo; <a class="active">Activity Procurement</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/activity_info_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Activity Procurement</h3>
							<hr>
						</div>
						<div class="form_grp"></div>
						<div class="blk_head">
							Current Purchase Orders
						</div>
						<div>
							<table>
								<tr>
									<td>Award/Clin ID</td>
									<td>Vendor ID</td>
									<td>Vendor Name</td>
									<td style="width:50px">Project/Activity</td>
									<td>Obligated</td>
									<td>Paid</td>
									<td>Available</td>
								</tr>
								<tr>
									<td>AW-123-17-00099-001-00211</td>
									<td>201600003</td>
									<td>Chemonics</td>
									<td>000051</td>
									<td>$90</td>
									<td>$0</td>
									<td>
										
									</td>
								</tr>
							</table>
						</div>
						<div class="form_grp">
							<button type="button" id="proceed">Proceed</button> 
						</div>
					</form> 
				</div> 
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>
					<div class="blk">
						Please use GLAAS to manage acquisition and assistance data for activities. <br/>
						<a id="project_finance">Move to GLAAS</a> 
					</div>		
				</div>
			</div>		
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('#show_team_member_blk').click(function(){
			$('#add_team_member').toggleClass('disp-none');
		});
		
		
		$('#show_team_blk').click(function(){
			$('#team_history_blk').toggleClass('disp-none');
		});
		
		$('#proceed').click(function(){
			window.location = "manage_activity_program_element.php";
		});
		
		$('#project_procurement').click(function(){
			//window.location = "project_procurement.php";
		});
	</script>
<style>
	.chart-cont{min-width:650px}
</style>
</body>
</html>