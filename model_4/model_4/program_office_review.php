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
		button{
			color: #fff;
			margin: 20px 0;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a></li>
			<li class="active">Create an Opportunity</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include('include/create_opportunity_leftpanel.php');?>
		</div>
		<div class="col-md-6">
			<div class="wrap">
				<div class="container-fluid">
					<form class="form-horizontal">
						<h3>Reviews</h3>					
						<hr>
						<div class="AR" id="review">
							<p>AR Required?</p>
							<div class="col-md-4">
								<ul class="usa-unstyled-list">
									<li>
										<input id="stanton" type="radio" name="ar" value="stanton" disabled="disabled">
										<label for="stanton">Yes</label>
									</li>
									<li>
										<input id="anthony" type="radio" checked name="ar" value="anthony">
										<label for="anthony">No</label>
									</li>
								</ul>
							</div>
							<div class="col-md-8" id="text-review">
								<p class="text-danger">No Annual Review Required.</p>
							</div>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="PAR" id="review">
							<p>PAR Required?</p>
							<div class="col-md-4">
								<ul class="usa-unstyled-list">
									<li>
										<input id="stanton" type="radio" name="par" value="stanton" disabled="disabled">
										<label for="stanton">Yes</label>
									</li>
									<li>
										<input id="anthony" type="radio" checked name="par" value="anthony">
										<label for="anthony">No</label>
									</li>
								</ul>
							</div>
							<div class="col-md-8" id="text-review">
								<p class="text-danger">No Project/ Activity Review Required.</p>
							</div>
						</div>
					</form>
					<div class="clearfix"></div>
					<hr>
					<h4>Add New Review</h4>
					<button class="usa-button-primary-alt usa-button-active"><i class="fa fa-plus" aria-hidden="true"></i> Add Review</button>
					<hr>
					<div class="project-list">
						<h4>Project Review List</h4>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Type of Review</th>
										<th>Due Date</th>
										<th>Prompt Date</th>
										<th>Approver</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="5" class="text-danger text-center">No review found for this project</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-group">
						<a href="decision_to_proceed.php" class="btn btn-primary save">Save & Proceed</a>
					</div>
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
	<script>
		$(document).ready(function(){
			$(".readmore").click(function(){
				$(".read").removeClass('disp-none');
				$(".readmore").hide();
			})
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>