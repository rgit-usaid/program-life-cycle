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
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		span>a:visited{
			color: #fff;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a></li>
			<li><a href="manage_activity.php">Manage Activity</a></li>
			<li class="active">View Activity</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<ul id="left-menu" style="padding-left: 0;">
				<li><a href="create_an_opportunity.php"><img src="img/skyblue.png" class="img-responsive" /><p>Enter Data</p></a></li>
				<li><a href="program_office_review.php"><img src="img/red.png" class="img-responsive" /><p>Program Office Review </p></a></li>
				<li><a href="decision_to_proceed.php"><img src="img/orange.png" class="img-responsive" /><p>Decision to proceed</p></a></li>
				
				<li class="active-left-menu"><a href="manage_activity.php"><img src="img/dark-composite.png" class="img-responsive" /><p>Manage Activities</p></a></li>
			</ul>
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<h3>View Activities<span><a href="manage_activity.php" class="btn btn-info pull-right">Back to Manage Activities</a></span></h3>
						<hr>
					<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center">Activity Id</th>
								<th class="text-center">Activity Title</th>
								<th class="text-center">Funding Type</th>
								<th class="text-center">Benefiting Country</th>
								<th class="text-center">Budget Center</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div> 
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>	
					
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>
	<script type="text/javascript">	
		$(document).ready(function() {
    		$('#manage-table').DataTable({"lengthMenu": [ 25, 50, 75, 100 ]});
		});	
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>