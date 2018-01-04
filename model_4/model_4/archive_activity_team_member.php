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
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="activity_list.php">Edit Activity Info</a> &raquo; <a href="manage_activity_team.php">Activity Team</a> &raquo; <a class="active">Achive Activity Team</a></li>
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
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Team History</h3>
							<hr>
						</div>
						<div>
							<div id="team_history_blk">
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
						</div>
						<div class="form_grp" style="height:20px; clear:both"></div>
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
				</div>
			</div>			
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('#proceed').click(function(){
			window.location = "manage_activity_finance.php";
		});
	</script>
</body>
</html>