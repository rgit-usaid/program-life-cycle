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
			<?php include 'include/cdcs_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no">
						<h3>Do You Have an Approved CDCS ?</h3>
						<p></p>
						<hr>
						<div class="form-group">
							<ul class="usa-unstyled-list list-inline" style="margin-top: 10px;">
								<li>
									<input type="radio" name="activity" value="new_activity" id="cdcs_approved">
									<label for="add">Yes</label>
								</li>
								<li style="margin-left: 30px;">
									<input  type="radio" name="activity" value="view_activity" id="no_cdcs_approved">
									<label for="view">No</label>
								</li>
							</ul>
						</div>
						<div class="new box" style="display: none;">
							<a class="btn btn-primary" id="cdcs-yes-btn">Proceed</a>
						</div>
						<div class="view box" style="display: none;">
							<a href="javascript:void(0);" class="btn btn-primary myalert">Proceed</a>
						</div>
					</form> 
					<form class="form-cdcs-no-blk form-horizontal disp-none">
						<hr>
						<h3>Is the Mission implementing programs in non-presence countries?</h3>
						<div class="form-group qes1">
							<ul class="usa-unstyled-list list-inline" style="margin-top: 10px;">
								<li>
									<input id="ckhc_yes" type="radio" name="country" value="yes" class="chk_country">
									<label for="add">Yes</label>
								</li>
								<li style="margin-left: 30px;">
									<input id="ckhc_no" type="radio" name="country" value="no" class="chk_country">
									<label for="view">No</label>
								</li>
							</ul>
						</div>
						<h3>Is the Mission implementing single-sector programs?</h3>
						<div class="form-group qes2">
							<ul class="usa-unstyled-list list-inline" style="margin-top: 10px;">
								<li>
									<input id="ckhp_yes" type="radio" name="program" value="yes" class="chk_program">
									<label for="add">Yes</label>
								</li>
								<li style="margin-left: 30px;">
									<input id="ckhp_no" type="radio" name="program" value="no" class="chk_program">
									<label for="view">No</label>
								</li>
							</ul>
						</div>
						<div class="yes disp-none">
							<a href="create_an_opportunity.php" class="btn btn-primary">Proceed</a>
						</div>
						<div class="no disp-none">
							<a href="javascript:void(0);" class="btn btn-primary myalert">No</a>
						</div>
					</form>
					<form class="form-cdcs-yes-blk form-horizontal disp-none">
						<hr>
						<h3>Is the CDCS expiring in the next 9 months or is the CDCS being heavily modified?</h3>
						<div class="form-group qes1">
							<ul class="usa-unstyled-list list-inline" style="margin-top: 10px;">
								<li>
									<input  type="radio" name="country" value="yes" id="chk_expt_yes">
									<label for="add">Yes</label>
								</li>
								<li style="margin-left: 30px;">
									<input type="radio" name="country" value="no" id="chk_expt_no">
									<label for="view">No</label>
								</li>
							</ul>
						</div>
						<div>
							<a class="btn btn-primary">Cancel</a>
							<a href="create_an_opportunity.php" class="btn btn-primary">Save & Proceed</a>
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
					<p class="first-stage disp-none blk">In the absence of a CDCS, the Mission should use a preliminary Results Frameworks, sector strategies, or other relevant multi-year frameworks to define the project purpose.</p>				
					<div class="second-stage disp-none blk">Would you like to stop here so that you can check if a CDCS will be approved before you continue with this Project Purpose Statement?
						<ul class="usa-unstyled-list list-inline" style="margin-top: 10px;">
						<li>
							<input  type="radio" name="program" value="yes" class="cdcs_exit">
							<label for="add">Yes</label>
						</li>
						<li style="margin-left: 30px;">
							<input  type="radio" name="program" value="no" class="cdcs_proceed">
							<label for="view">No</label>
						</li>
						</ul>
						
						<div style="height:10px"></div>
						<div class="clearfix"><a href="javascript:void(0);" class="btn btn-primary disp-none cdcs_approve_href">Proceed</a></div>
					<div>				
				</div>
			</div>
					<div class="third-stage disp-none blk">A Mission must notify its Regional Bureau and PPL of its plans to extend its CDCS at least nine months, but no more than 18 months, before its CDCS expiration date. Notification of an intended extension in less than nine months before the CDCS expiration, due to emergency circumstances will be considered on a case-by-case basis. A request for extension, which is submitted but not approved, cannot serve as a justification for a Mission failing to complete a new CDCS prior to the expiration of its existing CDCS</div>				
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#cdcs_approved").click(function(){
				$(".app_cdcs_no,.form-cdcs-no-blk").addClass('disp-none');
				$(".form-cdcs-yes-blk").removeClass('disp-none');
			});
			
			$("#no_cdcs_approved").click(function(){
				$('.app_cdcs_no').addClass('disp-none');
				$('.first-stage').removeClass('disp-none');
				$(".form-cdcs-no-blk").removeClass('disp-none');
			});
			
			$('.second-stage input[type="radio"]').click(function(){
				if($(this).val()=="yes"){
					window.location = "index.php";
				}
				else if($(this).val()=="no"){
					window.location = "create_an_opportunity.php";
				}
			});
			/*$('input[type="radio"]').click(function(){
				if($(this).val()=="new_activity"){
					$(".box").not(".new").hide();
					$(".new").show();
					$(".form-cdcs-no-blk").addClass('disp-none');
				}
				else if($(this).val()=="view_activity"){
					$(".box").not(".view").hide();
					$(".view").show();
				}
			});*/
		});
		/*$(document).ready(function(){
			$('input[type="radio"]').click(function(){
				if($(this).attr("value")=="no"){
					$(".box").not(".new").hide();
					$(".yes").show();
					
				}
				if($(this).attr("value")=="yes"){
					$(".box").not(".view").hide();
					$(".no").show();
					
				}
			});
		});*/
		$(document).ready(function(){
			$('.myalert').click(function() {
				$('.app_cdcs_no').addClass('disp-none');
				$('.first-stage').removeClass('disp-none');
				$(".form-cdcs-no-blk").removeClass('disp-none');
			});
			
			$('.form-cdcs-no-blk input[type="radio"]').click(function(){
				if($('.qes1 input[type="radio"]:checked').val()=="no" && $('.qes2 input[type="radio"]:checked').val()=="no"){
					$('.wrap-right-menu .first-stage').addClass('disp-none');
					$('.wrap-right-menu .second-stage').removeClass('disp-none');
					$('.form-cdcs-no-blk .yes').addClass('disp-none');
				}
				else{
					$('.wrap-right-menu .second-stage').addClass('disp-none');
					$('.wrap-right-menu .first-stage').removeClass('disp-none');
					$('.form-cdcs-no-blk .yes').removeClass('disp-none');
				}	
			});
			
			$('.wrap-right-menu .second-stage input[type="radio"]').click(function(){
				if($(this).val()=="yes"){
					$('.cdcs_approve_href').text("Exit");
					$('.cdcs_approve_href').attr("href","index.php");
					$('.cdcs_approve_href').removeClass('disp-none');
				}
				else{
					$('.cdcs_approve_href').text("Proceed");
					$('.cdcs_approve_href').attr("href","create_an_opportunity.php");
					$('.cdcs_approve_href').removeClass('disp-none');
				}
			});
			
			$('#cdcs-yes-btn').click(function(){
				$(".app_cdcs_no,.form-cdcs-no-blk").addClass('disp-none');
				$(".form-cdcs-yes-blk").removeClass('disp-none');
				
			});
			
			$('.form-cdcs-yes-blk input[type="radio"]').click(function(){
				if($(this).val()=="yes"){
					$('.third-stage').removeClass('disp-none');
				}
				else{
					$('.third-stage').addClass('disp-none');
				}
			});
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>