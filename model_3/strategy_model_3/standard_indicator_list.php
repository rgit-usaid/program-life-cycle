<?php
include('config/config.inc.php');
include('include/function.inc.php');
//## get Detail operating unit ===========
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id'];
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
else
{
	header('location:index.php');
}

$page_name="indicator_management";
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
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="indicator_management.php" class="remove-line"> Indicator Management</a> >> Standard Indicator</div>
			</div>
		</div>
		<nav id="nav">
			<ul class="list-inline">
				<li><a class="usa-button usa-button-outline-inverse active-class-white" data-target="standard_indicator_list" href="standard_indicator_list.php">Standard Indicators</a></li>
				<li><a class="usa-button usa-button-outline active-class-white disable active" data-target="custom_indicator_list" href="custom_indicator_list.php">Custom Indicators</a></li>	
			</ul>
		</nav>
	</div>

	<section id="main-content">
		<div class="container-fluid" id="standard_indicator">
			<div class="col-md-12 text-center"><h2 style="text-transform: uppercase; margin-top: 0">Standard Indicator</h2></div>
			<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
				<tr>
					<th class="text-center">Code</th>
					<th class="text-left">Title</th>
				</tr>
				</thead>
				<tbody>
				<?php 
					/*get standard indicator group name*/
					$sel_indicator_grph = "SELECT * FROM usaid_standard_indicator_group WHERE parent_id IS NULL order by id";
					$exe_indicator_grph = $mysqli->query($sel_indicator_grph);
					while($res_indicator_grph = $exe_indicator_grph->fetch_array())
					{?>
					<tr>
						<td class="td-left group_head"><?php echo $res_indicator_grph['indicator_id'];?></td>
						<td class="td-left group_head"><?php echo $res_indicator_grph['indicator_title'];?></td>
					</tr>	
				<?php 
					/*get standard indicator*/
					$sel_indicator_par = "SELECT * FROM usaid_standard_indicator_group WHERE parent_id IS NOT NULL and parent_id = '".$res_indicator_grph['id']."' order by id asc";
					$exe_indicator_par = $mysqli->query($sel_indicator_par);
					while($res_indicator_par = $exe_indicator_par->fetch_array()){
				?>
					<tr class="parent">
						<td class="td-left"><?php echo $res_indicator_par['indicator_id']?></td>
						<td class="td-left"><?php echo $res_indicator_par['indicator_title']?></td>				
					</tr>
				<?php 
					/*get standard indicator child*/
					$sel_indicator = "SELECT * FROM usaid_standard_indicator WHERE parent_id IS NOT NULL and parent_id ='".$res_indicator_par['id']."'";
					$exe_indicator = $mysqli->query($sel_indicator);
					while($res_indicator = $exe_indicator->fetch_array()){
				?>	
					<tr class="child">
						<td class="td-left td-pad"><?php echo $res_indicator['indicator_id']?></td>
						<td class="td-left td-pad"><?php echo $res_indicator['indicator_title']?></td>				
					</tr>
				<?php }}}?>
				</tbody>
			</table>
		</div>
	</section>

	<!-- <script type="text/javascript" src="js/jquery-ui.min.js"></script> -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/uswds.min.js"></script>
</body>
</html>



