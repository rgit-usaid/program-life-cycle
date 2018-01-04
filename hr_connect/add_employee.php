<?php
include('config/config.inc.php');

##function for get date format to insert into db y-m-d ========
function dateFormat($date)
{
	$date_formated = '';
   	if($date!='')
   	{
     	$date_formated = date('y-m-d',strtotime($date));
   	}
   	return $date_formated; 
}
##function for generate employee id ==================
function updateEmployeeId($id)
{
	global $mysqli;
	$num_str = sprintf("%06d", $id);
	$update_val = "update usaid_employee set employee_id = '".$num_str."' where id = '".$id."'";
	$result_update = $mysqli->query($update_val);
	if($result_update)return $num_str; else return false;
}
## add employee==========================
if(isset($_REQUEST['add_employee']))
{
	$first_name = $mysqli->real_escape_string(trim($_REQUEST['first_name'])); 
	$second_name = $mysqli->real_escape_string(trim($_REQUEST['second_name']));
	$last_name = $mysqli->real_escape_string(trim($_REQUEST['last_name']));
	$gender =  trim($_REQUEST['gender']);
	$date_of_birth = dateFormat(trim($_REQUEST['date_of_birth']));
	
	$file_name = $_FILES['picture']['name'];
    $file_size =$_FILES['picture']['size'];
   	$file_tmp =$_FILES['picture']['tmp_name'];
    $file_type=$_FILES['picture']['type'];
   	$file_ext=strtolower(end(explode('.',$_FILES['picture']['name'])));
   	$expensions= array("jpeg","jpg","png");
	
	if($first_name=='')
	{
		$error = "Please input first name";
	}
	elseif($gender=='')
	{
		$error = "Please select gender";
	}
	elseif($date_of_birth=='')
	{
		$error = "Please input or select data of birth";
	}
	elseif($file_name=='')
	{
		$error = "Please choose picture";
	}
	elseif(in_array($file_ext,$expensions)=== false)
	{
         $error = "extension not allowed, please choose a JPEG or PNG file.";
    }  
	else
	{
		$full_path = '';
		if($file_name!='')
		{
			$full_path = 'employee_picture/';
			$file_name = time().'_'.$file_name;
			$full_path = $full_path.$file_name;
			move_uploaded_file($file_tmp,$full_path);
		}
		
		$insert_employee = "insert into usaid_employee set first_name='".$first_name."', second_name='".$second_name."', last_name='".$last_name."', gender='".$gender."', date_of_birth='".$date_of_birth."',picture='".$full_path."'"; 
		$result_employee = $mysqli->query($insert_employee);
		if($result_employee)
		{
			$id = $mysqli->insert_id;
			$employee_id = updateEmployeeId($id);
			?>
			<form method="post" name="add_form" action="personal_data.php"> 
				<input type="hidden" name="employee_id" value="<?php echo $employee_id;?>">
			</form>
			<script>
				document.add_form.submit();
			</script>
			<?php
		} 
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
  	<link href="css/font-awesome.min.css" type="text/css" rel="stylesheet">
	<link href="css/style.css" type="text/css" rel="stylesheet"> 
	<link href="css/datepicker.css" type="text/css" rel="stylesheet">
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
				<ul class="nav nav-pills">
					<li class="active"><a href="add_employee.php">Personal Data</a></li>
                    <li class="disable"><a href="javascript:void(0);">Emp Detail</a></li>
                    <li class="disable"><a href="javascript:void(0);">Address</a></li>
                    <li class="disable"><a href="javascript:void(0);">Work Location</a></li>
                    <li class="disable"><a href="javascript:void(0);">Phone/Email</a></li>
                    <li class="disable"><a href="javascript:void(0);">Emergency Contacts</a></li>
                    <li class="disable"><a href="javascript:void(0);">Job-Related Skills</a></li>
			    </ul>
			    <div class="main-data">
			    	<div class="personal-data">
						<h1>MY INFORMATION - PERSONAL</h1>
						<div class="info">							
							<div class="key">
								<img src="images/key.png" width="75" height="75" alt="Key">
							</div>
							<div class="edit">
								<p>Click <b>SAVE</b> to Add Information.</p><br>
								<p>
										HR Connects no longer display Race and National Origin (RNO) code, since it has been replaced by a new Race and Ethnicity (R&amp;E) code. Employees are strongly urged to use the "My Information" link and update the new R&amp;E information, so the accurate data is reported to the Equal Employment Opportunity Commission. Submission of this new R&amp;E information is voluntary
								</p>
							</div>						
						</div>
						<hr class="main-one">
							<!-- Add Data Section -->
						 <?php
                        if(isset($error) and $error!='')
                        {
                            echo '<div style="text-align:center;color:red;">'.$error.'</div>';
                        }
                        ?>
						<form data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
		 					<table class="table">
								<tbody>
									<tr>
										<td><span class="title"><b>Name: </b></span></td>
										<td>
											<div class="col-xs-3">
												<input type="text" name="first_name" class="form-control" placeholder="First Name">	 
											</div>
											<div class="col-xs-3">
												<input type="text" name="second_name" class="form-control" placeholder="Second Name">	 
											</div>
											<div class="col-xs-3">
												<input type="text" name="last_name" class="form-control" placeholder="Last Name">	 
											</div>
										</td>
									</tr>
										 
									<tr>
										<td><span class="title"><b>Gender:</b></span></td>
										<td>
											<div class="col-xs-4">
												<select class="form-control" name="gender">
													<option value="">Select Gender</option>
													<option value="Male">Male</option>
													<option value="Female">Female</option>
													<option value="Other">Other</option>
												</select>
											</div>
										</td>
									</tr> 
									<tr>
										<td><b>Date of Birth:</b></td>
										<td>
											<div class="col-xs-4">
												<input type="text" name="date_of_birth" class="form-control datepicker" data-date-format="mm/dd/yyyy" placeholder="DOB">
											</div>
										</td>								
									</tr>
									<tr>
										<td><b>Picture:</b></td>
										<td>
											<div class="col-xs-4">
												<input type="file" name="picture" class="form-control">
											</div>
										</td>								
									</tr>  
								</tbody>
							</table>
							<a href="index.php" style="margin-left:15px"> Back to List</a>	     
							<button type="submit" class="btn btn-primary" name="add_employee" >Save</button> 
						</form>
						<div class="row">
							<div class="col-md-12 ques">
								<i class="fa fa-question fa-3x" aria-hidden="true"></i>
								<h5>Have a question? send an <a href="javascript:void(0);"> e-mail </a></h5>
							</div>
						</div>
						<div class="row">
							<div class="foot-menu">
									
							</div>
						</div>
					</div>
			    </div>
			</div>
		</div>
	</div>
	
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/bootstrap-datepicker.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {  
            $('.datepicker').datepicker({
                startDate: '-3d'
            });
        });
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
