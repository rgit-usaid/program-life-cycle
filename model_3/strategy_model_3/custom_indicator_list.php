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

if(isset($_REQUEST['delete']))
	{	
		$custom_indicator_delete_id = trim($_REQUEST['custom_indicator_delete_id']); 
		if($custom_indicator_delete_id!='')
		{
			$delete_custom_indicator = "update usaid_custom_indicator set 
			delete_status='Yes' 
			where id='".$custom_indicator_delete_id."'";
			$result_data = $mysqli->query($delete_custom_indicator);
			if($result_data)
			{
			header('location:custom_indicator_list.php');
			}
		}
	}	
		$select_data="select * from usaid_custom_indicator where operating_unit_id='".$operating_unit_id."' and delete_status='No'"; 
		$result_custom_indicator = $mysqli->query($select_data);
		$total_custom_indicator=$result_custom_indicator->num_rows; 

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
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="indicator_management.php" class="remove-line"> Indicator Management</a> >> Custom Indicator</div>
			</div>
		</div>
		<nav id="nav">
			<ul class="list-inline">
				<li><a class="usa-button usa-button-outline active-class-white disable active" data-target="standard_indicator_list" href="standard_indicator_list.php">Standard Indicators</a></li>
				<li><a class="usa-button usa-button-outline-inverse active-class-white" data-target="custom_indicator_list" href="custom_indicator_list.php">Custom Indicators</a></li>
			</ul>
		</nav>
	</div>

	<section id="main-content">
		<div class="container-fluid" id="standard_indicator">
			<div class="col-md-12 text-center"><h2 style="margin-top: 0; margin-left: 19rem;">CUSTOM INDICATOR<span class="pull-right"><a style="color: #0071bc;-webkit-box-shadow: inset 0 0 0 2px #0071bc;box-shadow: inset 0 0 0 2px #0071bc; padding: 0.7rem 1.2rem" class="usa-button usa-button-outline-inverse" href="custom_indicator.php?indicator=new">Create New Indicator</a></span></h2></div>
			<?php if($total_custom_indicator>0) { ?>
			<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th class="text-center">Date Created</th>
						
						<th class="text-center">Indicator Name</th>
						
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php  while($fetch_all_custom_indicator= $result_custom_indicator->fetch_array()) {?>
					<tr>
						<td><?php echo  $fetch_all_custom_indicator['added_on']; ?></td>
					
						<td><?php echo  $fetch_all_custom_indicator['name_indicator']; ?></td>
						
						<td class="text-center">
							<a style="color: #fff" class="btn btn-warning" href="custom_indicator.php?custom_indicator_edit_id=<?php echo $fetch_all_custom_indicator['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
							<form method="post" action="custom_indicator_list.php" style="display:inline">
								<input type="hidden" name="custom_indicator_delete_id" value="<?php  echo $fetch_all_custom_indicator['id']; ?>">
						<button name="delete" style="color: #fff; margin-top:0px" class="btn btn-danger" onClick="if(confirm('Are you sure, you want to delete this indicator?')){ return true;} else { return false; }"><i class="fa fa-trash" aria-hidden="true"></i></button>
						</form>
						</td>							
					</tr>
				<?php } ?>
					
				</tbody>
			</table>
			<?php } ?>
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



