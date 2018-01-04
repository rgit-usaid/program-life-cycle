<?php
include('config/config.inc.php');
include('include/function.inc.php');

//## get all operating unit from master===========
$url = PHOENIX_API_HOST_URL."get_all_operating_unit.php";
$operating_unit_arr = requestByCURL($url); 
$page_name=="index";
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
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">	
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	<div class="menu">
		<div class="container">
			<div class="usa-navbar site-header-navbar">
				
			</div>
		</div>
	</div>
	<!-- Select Operating Unit -->
	<div class="main-form">
		<div class="container-fluid">
			<div class="col-md-12">
				<div class="heading">
					<h2>Select Operating Unit</h2>
					<p>Select an Operating Unit in order to manage its strategic frameworks,indicators or development objective agreements (DOAGs)</p>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form" style="margin-top: 10px;">
					<form class="usa-form" role="form" method="post" action="manage.php" id="form">
						<div class="form-group">
							<select name="operating_unit_id" id="options">
								<option value="">Select</option>
								<?php 
								for($i=0; $i<count($operating_unit_arr['data']); $i++)
								{
									?>	
									<option value="<?php echo $operating_unit_arr['data'][$i]['operating_unit_id'];?>"><?php echo $operating_unit_arr['data'][$i]['operating_unit_description'].' ('.$operating_unit_arr['data'][$i]['operating_unit_abbreviation'].')';?></option>
									<?php
								} ?> 
							</select>
						</div>
						<input type="button" class="usa-button" style="margin: 0 auto" value="Manage" onClick="return check_op()">
					</form>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/uswds.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		function check_op(){
			if($('#options').val()!=""){
				$('#form').submit();
			}
		}
	</script>
</body>
</html>