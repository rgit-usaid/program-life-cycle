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
			<li><a href=".">Home</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_design_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" method="post">
						<div style="padding:10px; font-size:16px">This process lets you convert a Project Purpose Statement into a Project Design Plan or create a new Project Design Plan.</div>
						<p></p>
						<hr>
						<div class="form_grp">
							<div class="from_label">What would you like to do?</div>
							<div><input type="radio" value="old_project" name="project_type" class="project_type"/><label></label>I want to convert a Project Purpose Statement into a Project Design Plan.</div>
						</div>
						<div class="form_grp">
							<div><input type="radio" value="new_project" name="project_type" class="project_type"/><label></label>I want to create a new Project Design Plan.</div>
						</div>
						<button type="button" class="usa-button-outline">Cancel</button> <button type="button" class="usa-button-hover" id="proceed">Save & Proceed</button> <button type="button" class="usa-button-hover">Save & Exit</button>
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
					<p class="blk">This process lets you convert a Project Purpose Statement into a Project Design Plan or create a new Project Design Plan.</p>				
					<p class="blk">Please choose one of the options on the left.</p>					
				</div>
			</div>
			<div class="third-stage disp-none blk">A Mission must notify its Regional Bureau and PPL of its plans to extend its CDCS at least nine months, but no more than 18 months, before its CDCS expiration date. Notification of an intended extension in less than nine months before the CDCS expiration, due to emergency circumstances will be considered on a case-by-case basis. A request for extension, which is submitted but not approved, cannot serve as a justification for a Mission failing to complete a new CDCS prior to the expiration of its existing CDCS
			</div>				
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script>
		$('#proceed').click(function(){
			if($('.project_type:checked').val()=="old_project"){
				$('form').attr('action','choose_project_for_edit.php');
				$('form').submit();
			}
			else if($('.project_type:checked').val()=="new_project"){
				$('form').attr('action','create_design_plan.php');
				$('form').submit();
			}
		});
	</script>
</body>
</html>