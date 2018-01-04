<?php 
include('config/config.inc.php');
##get all project details from api==========
function _isCurl()
{
    return function_exists('curl_version');
}

###if curl is enable then get all projects==================    
if (_iscurl())
{
    //curl is enabled
    $url = HOST_URL."api/get_all_hr_employee.php";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
  	$employee_arr = json_decode($output,true);          
}
else{
     
}

###request for remove project================
if(isset($_REQUEST['remove']))
{
	$employee_id = trim($_REQUEST['employee_id']);	
	$url = HOST_URL."api/remove_employee.php?employee_id=".$employee_id."";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output_remove = curl_exec($ch);
    curl_close($ch);
    $remove = json_decode($output_remove,true);
  	if($remove['status']==200)
  	{  
  		header("location:./");	
  	} 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>USAID - HR Connect</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" />	
	
    <!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS
  ================================================== -->
 	<!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" type="text/css" rel="stylesheet">
  	<link href="css/font-awesome.min.css" type="text/css" rel="stylesheet">
  	<link href="css/jquery.dataTables.min.css" type="text/css" rel="stylesheet"> 
  	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">  
	 
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.html'; ?>

	<!-- HR Connect Page Content Start Here ==================================================-->
	<div class="container-fluid">
		<div class="row page-content">
			<!-- HR Connect Page Sidebar Menu Here ==================================================-->
			<div class="col-md-3 sidebar-menu">
				<?php include 'include/left_menu.html'; ?>
			</div>
			<div class="col-md-9 table-container">	
				<div class="row">	
					<a href="add_employee.php" style="margin:-15px 11px 8px 0;" class="btn btn-primary pull-right">Add New Employee</a>
				</div>
				<div class="row">
					<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center">Emp Id</th>
								<th class="text-center">Emp Name</th>
								<th class="text-center">Gender</th>
								<th class="text-center">Email</th>
								<th class="text-center">Cell Number</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								for($i=0; $i<count($employee_arr['data']); $i++){					 
							?>
							<tr>
								<td><?php echo $employee_arr['data'][$i]['employee_id'];?></td>
								<td><?php echo $employee_arr['data'][$i]['first_name'].' '.$employee_arr['data'][$i]['second_name'].' '.$employee_arr['data'][$i]['last_name'];?></td>
								<td><?php echo $employee_arr['data'][$i]['gender'];?></td>
								<td><?php echo $employee_arr['data'][$i]['USAID_email'];?></td>
								<td><?php echo $employee_arr['data'][$i]['USAID_cell_phone_number'];?></td>							
								<td class="text-center"> 
					 				<form method="post" action="personal_data.php" style="display:inline;font-size:14px">
					 				<input type="hidden" name="employee_id" style="display:inline" value="<?php echo $employee_arr['data'][$i]['employee_id']; ?>">
					 				<input type="submit" name="details" value="Details" style="color:#00a6d2; border:none; background:none;font-weight:normal;padding:0px;margin:0px;display:inline" class="project_btn"> 
					 				</form>
					 			|
									 <form method="post" style="display:inline;font-size:14px">
									 	<input type="hidden" name="employee_id" style="display:inline" value="<?php echo $employee_arr['data'][$i]['employee_id']; ?>" >
									 	<input type="submit" name="remove" value="Remove" class="project_btn" style="color:#00a6d2; border:none; background:none;font-weight:normal;padding:0px;margin:0px;display:inline;" onClick="return window.confirm('Are you sure you want to remove this employee');">							
									 </form> 
								</td>							
							</tr>
							<?php  }?>
						</tbody>
					</table>
				</div>
			</div>	
		</div>
	</div>
	
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>
	<script type="text/javascript">	
		$(document).ready(function() {
    		$('#manage-table').DataTable({"lengthMenu": [ 25, 50, 75, 100 ]});
		});	
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php
if(isset($_SESSION['employee_id']))unset($_SESSION['employee_id']);
?>