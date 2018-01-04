<?php
include('config/config.inc.php');

##function for get date format to insert into db y-m-d ========
function dateFormat($date)
{
	$date_formated = '';
   	if($date!='')
   	{
     	$date_formated = date('Y-m-d',strtotime($date));
   	}
   	return $date_formated; 
}

if(isset($_REQUEST['employee_id']))
{
	$employee_id = $_REQUEST['employee_id'];
	$_SESSION['employee_id'] = $employee_id;
}
else
{
	$_REQUEST['employee_id'] = $_SESSION['employee_id'];
	$employee_id = $_SESSION['employee_id'];
}

if($employee_id!='')
{
	$url = HOST_URL."api/get_hr_employee.php?employee_id=".$employee_id."";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
  	$employee_arr = json_decode($output,true); 
}

## get all hr usaid  role==============
$url =HOST_URL."api/get_all_hr_roles.php";  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);                               
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
$role_arr = json_decode($output,true);

## get all hr usaid  role==============
$url = HOST_URL."api/get_employee_hire_type.php";  
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);                               
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
$employee_type_arr = json_decode($output,true); 

## code for update employee details============
if(isset($_REQUEST['update_emp']))
    {
        $employee_id =  trim($_REQUEST['employee_id']);
        $first_name = $mysqli->real_escape_string(trim($_REQUEST['first_name']));
        $second_name = $mysqli->real_escape_string(trim($_REQUEST['second_name']));
        $last_name = $mysqli->real_escape_string(trim($_REQUEST['last_name']));
        $gender =  trim($_REQUEST['gender']);
        $date_of_birth = dateFormat($_REQUEST['date_of_birth']);
        $old_picture = trim($_REQUEST['old_picture']);
        $file_name = $_FILES['picture']['name'];
        $file_size =$_FILES['picture']['size'];
        $file_tmp =$_FILES['picture']['tmp_name'];
        $file_type=$_FILES['picture']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['picture']['name'])));
        $expensions= array("jpeg","jpg","png");
        $uploaddir = 'employee_picture/'; 

        if(in_array($file_ext,$expensions)=== false and $file_name!='')
        {
             $error_t1 = "Extension not allowed, please choose a JPEG or PNG file.";
        } 
        elseif($first_name=='')
        {
            $error_t1 = "Please input first name";
        }
        elseif($gender=='')
        {
            $error_t1 = "Please select gender";
        }
        elseif($date_of_birth=='')
        {
            $error_t1 = "Please input or select data of birth";
        }
        else
        {
            if($employee_id!='')
            {
                $picture_val = '';
                $file_name = str_replace(' ', '_', $file_name);
                if($file_name!='')
                {
                    $file_name = time().'_'.$file_name;
                    $full_path = $uploaddir.$file_name;
                    if(move_uploaded_file($file_tmp,$full_path))
                    {
                        unlink($old_picture);
                    }
                    $picture_val = ", picture='".$full_path."'";
                }
                $update_employee = "update usaid_employee set first_name='".$first_name."', second_name='".$second_name."', last_name='".$last_name."', gender='".$gender."', date_of_birth='".$date_of_birth."' ".$picture_val." where employee_id = '".$employee_id."'";
                
                $result_employee = $mysqli->query($update_employee);
                 
                header("location:personal_data.php");
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
					<li class="active"><a href="personal_data.php">Personal Data</a></li>
                    <li><a href="emp_detail.php">Emp Detail</a></li>
                    <li><a href="address.php">Address</a></li>
                    <li><a href="work_location.php">Work Location</a></li>
                    <li><a href="phone.php">Phone/Email</a></li>
                    <li><a href="emergency_contact.php">Emergency Contacts</a></li>
                    <li><a href="javascript:void(0);">Job-Related Skills</a></li>
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
                        <!--Info Data Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
									 <tbody>
                                        <tr>
                                            <td><b>Name:</b></td>
                                            <td><span id="name">
                                            <?php 
                                            $comma = '';
                                            if($employee_arr['data']['last_name']!='' and $employee_arr['data']['first_name']!='')
                                            {
                                                $comma = ', ';
                                            }
                                            echo $employee_arr['data']['last_name'].$comma.$employee_arr['data']['first_name']; 
                                             ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Emplid:</b></td>
                                            <td><?php echo $employee_arr['data']['employee_id']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Position Title:</b></td>
                                           	<td><?php echo $employee_arr['data']['USAID_position_title']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr class="main-one">
                        <!-- Edit Data Section -->
                        <div class="row">
                            <div class="col-md-12">
                            	<?php
                            		if(isset($error_t1) and $error_t1!='')
                            		{
                            			echo '<div style="text-align:center;color:red;">'.$error_t1.'</div>';
                            		}
                            	?>
                                <div>
                                    <form method="post" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="employee_id" value="<?php echo $employee_id;?>">
                                    <input type="hidden" name="tab_type" value="1">
                                        <table class="table">
                                            <tbody>
                                                 <tr>
                                                    <td><b>Picture:</b></td>
                                                    <td><span class="text_form"><img class="img-responsive" width="100" src="<?php echo $employee_arr['data']['picture'];?>" ></span>
                                                    <span class="input_text">
                                                        <div class="col-md-10">
                                                        	<input type="hidden" name="old_picture" value="<?php echo $employee_arr['data']['picture'];?>">
                                                            <input type="file" name="picture"><img class="img-responsive" width="40" src="<?php echo $employee_arr['data']['picture'];?>" >
                                                        </div>
                                                    </span>
                                                    </td>
                                                </tr>  
                                                <tr>
                                                    <td><span class="title"><b>Name: </b></span></td>
                                                    <td><span class="text_form" ><?php echo $employee_arr['data']['last_name'].$comma.$employee_arr['data']['first_name'];?></span>
                                                        <span class="input_text">
                                                            <div class="col-xs-3">
                                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo $employee_arr['data']['first_name'];?>" title="First Name">     
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <input type="text" name="second_name" class="form-control" placeholder="Second Name" value="<?php echo $employee_arr['data']['second_name'];?>" title="Second Name"> 
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo $employee_arr['data']['last_name'];?>" title="Last Name">     
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="title"><b>Gender:</b></span></td>
                                                    <td><span class="text_form"><?php echo $employee_arr['data']['gender'];?></span>
                                                    <span class="input_text">
                                                        <div class="col-md-10">
                                                            <select class="form-control" name="gender">
                                                                <option value="">Select Gender</option>
                                                                <option value="Male" <?php if($employee_arr['data']['gender']=='Male')echo 'selected="selected"'; ?>>Male</option>
                                                                <option value="Female" <?php if($employee_arr['data']['gender']=='Female')echo 'selected="selected"'; ?>>Female</option>
                                                                <option value="Other" <?php if($employee_arr['data']['gender']=='Other')echo 'selected="selected"'; ?>>Other</option>
                                                            </select>
                                                        </div>
                                                    </span>
                                                    </td>
                                                </tr> 
                                                <tr>
                                                    <td><b>Date of Birth:</b></td>
                                                    <td><span class="text_form"><?php echo  $employee_arr['data']['date_of_birth'];?></span>
                                                    <span class="input_text">
                                                        <div class="col-md-10">
                                                            <input type="text" name="date_of_birth" class="form-control datepicker" data-date-format="mm/dd/yyyy" placeholder="DOB" value="<?php echo $employee_arr['data']['date_of_birth'];?>">
                                                        </div>
                                                    </span>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        <!-- button to show form -->
                                        <a href="index.php" style="margin-right:15px;"> Back to list </a>
                                        <button type="button" class="btn btn-primary edit_btn" >Edit</button>          
                                            <!-- buttons to submit / cancel form -->
                                        <span>     
                                            <button type="button" class="btn btn-default cancel_btn" >Cancel</button>&nbsp;     
                                            <input type="submit" class="btn btn-primary update_btn" name="update_emp" value="Save"> 
                                        </span> 
                                    </form> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
	                        <div class="col-md-12 ques">
	                            <i class="fa fa-question fa-3x" aria-hidden="true"></i>
	                            <h5>Have a question? send an <a href="#"> e-mail </a></h5>
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
		$(function(){
			$('.input_text,.cancel_btn,.update_btn').css('display','none');
			$('.edit_btn').click(function(){
				$('.input_text,.cancel_btn,.update_btn').css('display','inline');	
				$('.text_form').css('display','none');
				$(this).css('display','none');
			});
			$('.cancel_btn').click(function(){
				$('.input_text,.cancel_btn,.update_btn').css('display','none');	
				$('.text_form').css('display','inline');
				$('.edit_btn').css('display','inline');
			});
		});
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>