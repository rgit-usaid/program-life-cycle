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
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a class="active">Edit Project Info</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal project_design_plan" action="" method="post">
						<div  class="form_grp" style="padding:20px 5px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Project Info</h3>
							<div>Activities scheduled for concurrent design are located in the <span style="font-style:italic">Edit Activities</span> Process</div>
						</div>
						<div class="form_grp">
							<div class="row from_label">
								<div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">Title of Proposed Project:</div>
								<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12"><span class="bold">Access To Potable Water In South-Western Coastal Areas Where The Water Table Is Polluted</span></div>
							</div>
						</div>
						<div class="form_grp">
							<div class="row from_label">
								<div class="col-lg-4 col-md-5 col-sm-5 col-xs-12">Project Stage :</div>
								<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12"><span class="bold">Project Purpose Statement</span></div>
							</div>
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
						You are editing an existing project. When you press the "Proceed" button on the left, you will be led through the process of edit project details, assign or change the details, assign or change the project team, modify the geo-coding and align the project and its activities to your strategy. 
					</div>	
				</div>
			</div>
			<div class="third-stage disp-none blk">A Mission must notify its Regional Bureau and PPL of its plans to extend its CDCS at least nine months, but no more than 18 months, before its CDCS expiration date. Notification of an intended extension in less than nine months before the CDCS expiration, due to emergency circumstances will be considered on a case-by-case basis. A request for extension, which is submitted but not approved, cannot serve as a justification for a Mission failing to complete a new CDCS prior to the expiration of its existing CDCS
			</div>				
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('#project_stage').change(function(){
			var stage = parseInt($(this).val());
			if(stage>2){
				$('form').attr('action','project_appraisal_document.php');
				$('form').submit();
			}
			else{
				$('form').attr('action','project_purpose_statement.php');
				$('form').submit();
			}	
		});
		
		$('#proceed').click(function(){
			window.location ="manage_project_design_plan.php";
		});
	</script>
</body>
</html>