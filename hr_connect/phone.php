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
$url = HOST_URL."api/get_all_hr_roles.php";  
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
        $USAID_email = $mysqli->real_escape_string(trim($_REQUEST['USAID_email']));
        $USAID_cell_phone_number = $mysqli->real_escape_string(trim($_REQUEST['USAID_cell_phone_number']));
        $USAID_desk_phone_number = $mysqli->real_escape_string(trim($_REQUEST['USAID_desk_phone_number']));
        if($USAID_email=='')
        {
            $error_t5 = "Please input USAID email";
        }
        else
        {
            if($employee_id!='')
            {
                $update_employee = "update usaid_employee set USAID_email='".$USAID_email."', USAID_cell_phone_number='".$USAID_cell_phone_number."', USAID_desk_phone_number='".$USAID_desk_phone_number."' where employee_id = '".$employee_id."'";
                $result_employee = $mysqli->query($update_employee);
                header("location:phone.php");
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
					<li><a href="personal_data.php">Personal Data</a></li>
                    <li><a href="emp_detail.php">Emp Detail</a></li>
                    <li><a href="address.php">Address</a></li>
                    <li><a href="work_location.php">Work Location</a></li>
                    <li class="active"><a href="phone.php">Phone/Email</a></li>
                    <li><a href="emergency_contact.php">Emergency Contacts</a></li>
                    <li><a href="javascript:void(0);">Job-Related Skills</a></li>
			    </ul>
			    <div class="main-data">
			    	<div class="personal-data">
			    		<h1>MY INFORMATION - PHONE/E-MAIL</h1>
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
                            <div class="col-md-8">
                            <?php
                                if(isset($error_t5) and $error_t5!='')
                                {
                                    echo '<div style="text-align:center;color:red;">'.$error_t5.'</div>';
                                }
                            ?>
                                <form method="post" action="">
                                    <input type="hidden" name="employee_id" value="<?php echo $employee_id;?>">
                                    <input type="hidden" name="tab_type" value="5">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><span class="title"><b>Employee USAID E-mail: </b></span></td>
                                                <td> 
                                                    <span class="text_ph"><?php echo  $employee_arr['data']['USAID_email'];?></span>
                                                    <span class="input_ph">
                                                        <div class="col-md-10">
                                                            <input type="text" name="USAID_email" class="form-control" placeholder="" value="<?php echo  $employee_arr['data']['USAID_email'];?>">
                                                        </div>
                                                    </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="title"><b>Employee USAID Cell Phone Number:</b></span></td>
                                                <td>
                                                     <span class="text_ph"><?php echo  $employee_arr['data']['USAID_cell_phone_number'];?></span>
                                                        <span class="input_ph">
                                                        <div class="col-md-10">
                                                            <input type="text" name="USAID_cell_phone_number" class="form-control" placeholder="" value="<?php echo  $employee_arr['data']['USAID_cell_phone_number'];?>">
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Employee USAID Desk Phone Number:</b></td>
                                                <td>
                                                    <span class="text_ph"><?php echo  $employee_arr['data']['USAID_desk_phone_number'];?></span>
                                                    <span class="input_ph">
                                                        <div class="col-md-10">
                                                            <input type="text" name="USAID_desk_phone_number" class="form-control" placeholder="" value="<?php echo  $employee_arr['data']['USAID_desk_phone_number'];?>">
                                                        </div>
                                                    </span>
                                                </td>                               
                                            </tr>
                                        </tbody>
                                    </table>
                                        <!-- button to show form -->
                                    <a href="index.php" style="margin-right:15px;"> Back to list </a>
                                    <button type="button" class="btn btn-primary edit_ph" >Edit</button>           
                                                <!-- buttons to submit / cancel form -->
                                    <span>     
                                        <button type="button" class="btn btn-default cancel_ph" >Cancel</button>&nbsp;      
                                        <input type="submit" class="btn btn-primary update_ph" name="update_emp" value="Save"> 
                                    </span> 
                                </form>
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
	<script type="text/javascript">
		$(function(){
			$('.input_ph,.cancel_ph,.update_ph').css('display','none');
			$('.edit_ph').click(function(){
				$('.input_ph,.cancel_ph,.update_ph').css('display','inline');	
				$('.text_ph').css('display','none');
				$(this).css('display','none');
			});
			$('.cancel_ph').click(function(){
				$('.input_ph,.cancel_ph,.update_ph').css('display','none');	
				$('.text_ph').css('display','inline');
				$('.edit_ph').css('display','inline');
			});
		});
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>