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
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="project_info.php">Edit Project Info</a> &raquo; <a class="active">Project Team</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_info_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px 10px 0; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Current Team</h3>
							<hr>
						</div>
						<div class="form_grp" style="padding-top:20px">
							<div>Anyone who has an active role in the project. At minimum a project must have an Project Manager and COR/AOR. Anyone can edit the team.</div>
						</div>
						<div class="row form_grp">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="img_blk">
									<img src="img/user.png" class="img-responsive center-block"/>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="img_blk">
									<img src="img/user.png" class="img-responsive center-block"/>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="img_blk">
									<img src="img/user.png" class="img-responsive center-block"/>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="img_blk">
									<img src="img/user.png" class="img-responsive center-block"/>
								</div>
							</div>
						</div>
						<div class="form_grp"></div>
						<div class="seprator"></div>
						<div>
							<div class="form_grp">
								<div class="blk_head">Team Marker</div>
								<div>A marker to identify what the team is working on.</div>
							</div>
							<div class="form_grp">
								<select>
									<option value="">Select</option>
									<option value="Peace and Security">Peace and Security</option>
									<option value="Democracy, Human Rights and Governance">Democracy, Human Rights and Governance</option>
									<option value="Health">Health</option>
									<option value="Education and Social Services">Education and Social Services</option>
									<option value="Economic Growth">Economic Growth</option>
									<option value="Humanitarian Assistance">Humanitarian Assistance</option>
								</select>
							</div>
						</div>
						<div class="seprator"></div>
						<div class="form_grp bold" style="padding:20px 0px 10px 0; font-size:16px;">
							Choose one :
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div style="padding-top:20px" >
									<input type="radio" name="manage_team_member" value="add_team_member"/><label>Add Team Member</label>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div style="padding-top:20px">
									<input type="radio" name="manage_team_member" value="edit_team_member"/><label>Edit Team Member</label>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div style="padding-top:20px">
									<input type="radio" name="manage_team_member" value="archive_team_member"/><label>Archive Team Member</label>
								</div>
							</div>
						</div>
						<div class="form_grp"></div>
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
				</div>
			</div>		
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('input[type="radio"][name="manage_team_member"]').click(function(){
			if($(this).val()=="add_team_member"){
				window.location = "add_project_team_member.php";
			}
			else if($(this).val()=="edit_team_member"){
				window.location = "edit_project_team_member.php";
			}
			else if($(this).val()=="archive_team_member"){
				window.location = "archive_project_team_member.php";
			}
		});
	
		$('#proceed').click(function(){
			window.location = "manage_project_geo_location.php";
		});
	</script>
</body>
</html>