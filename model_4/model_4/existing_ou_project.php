<?php 
$lp_sel="operating_unit";
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
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		span>a:visited{
			color: #fff;
		}
		.dataTables_length{display:none;}
		#left-menu > li >a >p{
			display: table;
		}
		#left-menu > li >a >img{
			margin-top: 18px;
			margin-right: 10px;
			-ms-transform: rotate(270deg); /* IE 9 */
			-webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
			transform: rotate(270deg);
		}
		#left-menu > li >a {
			text-decoration: none;
		}
		#manage-table_wrapper{
			margin-top: 5px;
		}
		.btn-warning,.btn-danger{
			border-radius: 3px;
		}
		#manage-table_filter{display:none;}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li class="active">Home</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/left_panel.php'; ?>
		</div>
		<div class="col-md-6">
			<div class="wrap">
				<div class="container-fluid">
					<h3 style="font-size:18px">Showing projects related to Bureau of Africa in the User’s personnel record</h3>
					<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center">Project Id</th>
								<th class="text-center">Project Title</th>
								<th class="text-center">Estimated Fund</th>
								<th class="text-center">Stage</th>
								<th class="text-center" style="width:150px">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>000002</td>
								<td>Access To Potable Water In South-Western Coastal Areas Where The Water Table Is Polluted</td>
								<td>$53,601,235	</td>
								<td>Project Design Plan</td>
								<td class="text-center">
									<a class="btn btn-warning view_project_details" style="display:inline-block"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
									<a class="btn btn-danger remove_project" style="display:inline-block"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
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
			$('#manage-table').DataTable({"lengthMenu": [ 10, 25, 50, 100 ],responsive: true});
		});	
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>