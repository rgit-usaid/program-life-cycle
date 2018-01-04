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
    $employee_id =  trim($_REQUEST['employee_id']);
        $hire_type = trim($_REQUEST['radio_hire']);
        if($hire_type=='Non-Direct Hire')
        { 
            $type_non_direct_hire = $mysqli->real_escape_string(trim($_REQUEST['type_non_direct_hire']));
            $type_direct_hire = ''; 
        }
        else
        {
            $type_non_direct_hire = '';
            $type_direct_hire = $mysqli->real_escape_string(trim($_REQUEST['type_direct_hire']));   
        }

        $USAID_role_id =  trim($_REQUEST['USAID_role_id']);

        if($_REQUEST['service_type']=="Foreign"){
            $foreign_service_employee_grade = $mysqli->real_escape_string(trim($_REQUEST['foreign_service_employee_grade']));
            $foreign_service_employee_step = $mysqli->real_escape_string(trim($_REQUEST['foreign_service_employee_step']));
            $general_service_employee_grade = "";
            $general_service_employee_step = "";
        }
        else if($_REQUEST['service_type']=="General"){
            $foreign_service_employee_grade = "";
            $foreign_service_employee_step = "";
            $general_service_employee_grade = $mysqli->real_escape_string(trim($_REQUEST['general_service_employee_grade']));
            $general_service_employee_step = $mysqli->real_escape_string(trim($_REQUEST['general_service_employee_step'])); 
        }
        $USAID_position_title = $mysqli->real_escape_string(trim($_REQUEST['USAID_position_title']));
        $qualified_COR_AOR = $mysqli->real_escape_string(trim($_REQUEST['qualified_COR_AOR']));
        $COR_AOR_certification_expiration_date = dateFormat(trim($_REQUEST['COR_AOR_certification_expiration_date']));
        $qualified_project_manager = $mysqli->real_escape_string(trim($_REQUEST['qualified_project_manager']));
        $project_manager_certification_expiration_date = dateFormat(trim($_REQUEST['project_manager_certification_expiration_date']));
        $USAID_supervisor_employee_id = $mysqli->real_escape_string(trim($_REQUEST['USAID_supervisor_employee_id']));
        if($type_direct_hire=='' and $type_non_direct_hire=='')
        {
            $error_t2 = "Please input atleast one employee type hire";
        }
        elseif($employee_id==$USAID_supervisor_employee_id)
        {
            $error_t2 = "USAID Supervisor Employee ID Number should not be same as self id";
        }
        else
        {
            if($employee_id!='')
            {
                $update_employee = "update usaid_employee set type_direct_hire='".$type_direct_hire."', type_non_direct_hire='".$type_non_direct_hire."', USAID_role_id='".$USAID_role_id."', foreign_service_employee_grade='".$foreign_service_employee_grade."', foreign_service_employee_step='".$foreign_service_employee_step."' , general_service_employee_grade='".$general_service_employee_grade."', general_service_employee_step='".$general_service_employee_step."', USAID_position_title='".$USAID_position_title."', qualified_COR_AOR='".$qualified_COR_AOR."', COR_AOR_certification_expiration_date='".$COR_AOR_certification_expiration_date."', qualified_project_manager='".$qualified_project_manager."', project_manager_certification_expiration_date='".$project_manager_certification_expiration_date."', USAID_supervisor_employee_id='".$USAID_supervisor_employee_id."' where employee_id = '".$employee_id."'";
                $result_employee = $mysqli->query($update_employee);
                header("location:emp_detail.php");
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
  	<link href="css/jquery.dataTables.min.css" type="text/css" rel="stylesheet"> 
  	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
    <link href="css/datepicker.css" type="text/css" rel="stylesheet">  
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
                    <li class="active"><a href="emp_detail.php">Emp Detail</a></li>
                    <li><a href="address.php">Address</a></li>
                    <li><a href="work_location.php">Work Location</a></li>
                    <li><a href="phone.php">Phone/Email</a></li>
                    <li><a href="emergency_contact.php">Emergency Contacts</a></li>
                    <li><a href="javascript:void(0);">Job-Related Skills</a></li>
			    </ul>
			    <div class="main-data">
			    	<div class="personal-data">
			    		<h1>MY INFORMATION - EMPLOYEE DETAIL</h1>
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
                                    if(isset($error_t2) and $error_t2!='')
                                    {
                                        echo '<div style="text-align:center;color:red;">'.$error_t2.'</div>';
                                    }
                                ?>
                                <form method="post" action="">
                                    <input type="hidden" name="employee_id" class="employee_id" value="<?php echo $employee_id;?>">
                                    <input type="hidden" name="tab_type" value="2">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><span class="title"><b>Employment Type Hire: </b></span></td>
                                                <td>
                                                    <span class="text_emp"><?php if($employee_arr['data']['type_non_direct_hire']!='')echo 'Non-Direct Hire <br>'.$employee_arr['data']['type_non_direct_hire'];
                                                            if($employee_arr['data']['type_direct_hire']!='')echo 'Direct Hire <br>'.$employee_arr['data']['USAID_role'];?> 
                                                    </span>
                                                    <span class="input_emp">
                                                        <div class="col-md-10">
                                                            
                                                        <input type="radio" name="radio_hire" value="Direct Hire" class="hire_type" <?php if($employee_arr['data']['type_direct_hire']!='')echo 'checked="checked"'; ?>><span class="hire-text">Direct Hire </span>
                                                        <input type="radio" name="radio_hire" value="Non-Direct Hire" class="hire_type" <?php if($employee_arr['data']['type_non_direct_hire']!='')echo 'checked="checked"'; ?>><span class="hire-text"> Non-Direct Hire</span>

                                                        <span class="text_emp"><?php  $employee_arr['data']['type_direct_hire'];?></span>
                                                        <span class="text_emp"><?php   $employee_arr['data']['type_non_direct_hire'];?></span>

                                                        <span class="hire"> <br>
                                                            <select name="type_direct_hire" class="form-control">
                                                                <option value=''>--Select--</option>
                                                                    <?php
                                                                    foreach ($employee_type_arr['data']['direct'] as $key_emp_type => $value_emp_type) {
                                                                    ?>
                                                                        <option value="<?php echo $value_emp_type; ?>" <?php if($value_emp_type==$employee_arr['data']['type_direct_hire'])echo 'selected="selected"'; ?>><?php echo $value_emp_type; ?></option>
                                                                    <?php   
                                                                    } 
                                                                    ?>
                                                                </select>
                                                            </span>
                                                            <span class="nonhire"> <br>
                                                                <select name="type_non_direct_hire" class="form-control">
                                                                    <option value=''>--Select--</option>
                                                                    <?php
                                                                    foreach ($employee_type_arr['data']['non_direct'] as $key_emp_type => $value_emp_type) {
                                                                    ?>
                                                                        <option value="<?php echo $value_emp_type; ?>" <?php if($value_emp_type==$employee_arr['data']['type_non_direct_hire'])echo 'selected="selected"'; ?>><?php echo $value_emp_type; ?></option>
                                                                    <?php   
                                                                    }
                                                                     
                                                                    ?>
                                                                </select> 
                                                            </span>
                                                            </div>
                                                        </span> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="title"><b>Employee Role within USAID:</b></span></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo $employee_arr['data']['USAID_role']; ?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <select name="USAID_role_id" class="form-control">
                                                                    <option>Select</option>
                                                                    <?php
                                                                    for($count_role=0; $count_role<count($role_arr['data']); $count_role++)
                                                                    { ?>
                                                                        <option value="<?php echo $role_arr['data'][$count_role]['role_id'];?>" <?php if($role_arr['data'][$count_role]['role_id']==$employee_arr['data']['USAID_role_id'])echo 'selected="selected"'; ?>><?php echo $role_arr['data'][$count_role]['role'];?></option>
                                                                    <?php   
                                                                    }
                                                                    ?> 
                                                                </select>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Service Type:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php
                                                        if($employee_arr['data']['foreign_service_employee_grade']!='' and $employee_arr['data']['foreign_service_employee_step']!='')
                                                        {
                                                            echo '<span title="Foreign service employee grade">Foreign service emp grade: '.$employee_arr['data']['foreign_service_employee_grade'].'</span><br><span title="Foreign service employee step">Foreign service emp step: '.$employee_arr['data']['foreign_service_employee_step'].'</span>';
                                                        }
                                                        if($employee_arr['data']['general_service_employee_grade']!='' and $employee_arr['data']['general_service_employee_step']!='')
                                                        {
                                                            echo '<span title="General service employee grade">Gen service emp grade: '.$employee_arr['data']['general_service_employee_grade'].'</span><br><span title="General service employee step">Gen service emp step: '.$employee_arr['data']['general_service_employee_step'].'</span>';
                                                        }
                                                        ?> 
                                                        </span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <span><input type="radio" name="service_type" class="service_type" value="Foreign" <?php if($employee_arr['data']['foreign_service_employee_grade']!='' and $employee_arr['data']['foreign_service_employee_step']!='')echo 'checked="checked"';  ?>> Foreign
                                                                      <input type="radio" name="service_type" class="service_type" value="General" <?php if($employee_arr['data']['general_service_employee_grade']!='' and $employee_arr['data']['general_service_employee_step']!='')echo 'checked="checked"'; ?>> General
                                                                </span>
                                                                <span class="foreign_service"> <br>
                                                                    <input type="text" name="foreign_service_employee_grade" class="form-control" placeholder="Foreign service employee grade" value="<?php echo  $employee_arr['data']['foreign_service_employee_grade'];?>" placeholder="Foreign service employee grade" title="Foreign service employee grade">
                                                                    <br>
                                                                     <input type="text" style="margin-top:5px" name="foreign_service_employee_step" class="form-control" placeholder="Foreign service employee step" value="<?php echo  $employee_arr['data']['foreign_service_employee_step'];?>" placeholder="Foreign service employee step" title="Foreign service employee step">
                                                                </span>
                                                                <br>
                                                                <span class="general_service">
                                                                     <input type="text" name="general_service_employee_grade" class="form-control" placeholder="General service employee grade" value="<?php echo  $employee_arr['data']['general_service_employee_grade'];?>" placeholder="General service employee grade" title="General service employee grade">
                                                                     <br>
                                                                      <input type="text" style="margin-top:5px" name="general_service_employee_step" class="form-control" placeholder="General service employee step" value="<?php echo  $employee_arr['data']['general_service_employee_step'];?>" placeholder="General service employee step" title="General service employee step">
                                                                </span> 
                                                            </div>
                                                        </span>
                                                    </td>                               
                                                </tr> 
                                                <tr>
                                                    <td><b>USAID Position Title:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo  $employee_arr['data']['USAID_position_title'];?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <input type="text" name="USAID_position_title" class="form-control" placeholder="" value="<?php echo  $employee_arr['data']['USAID_position_title'];?>">
                                                            </div>
                                                        </span>
                                                    </td>                                   
                                                </tr>
                                                <tr>
                                                    <td><b>Qualified as COR/AOR:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo  $employee_arr['data']['qualified_COR_AOR'];?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10" >
                                                               <input type="radio" name="qualified_COR_AOR" placeholder="" value="No" <?php if($employee_arr['data']['qualified_COR_AOR']=='No')echo 'checked="checked"'; ?>> No &nbsp; 
                                                                <input type="radio" name="qualified_COR_AOR" placeholder="" value="Yes" <?php if($employee_arr['data']['qualified_COR_AOR']=='Yes')echo 'checked="checked"'; ?>> Yes
                                                            </div>
                                                        </span>
                                                    </td>                                   
                                                </tr>
                                                <tr>
                                                    <td><b>COR/AOR Certification Expiration Date:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo  $employee_arr['data']['COR_AOR_certification_expiration_date'];?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <input type="text" name="COR_AOR_certification_expiration_date" class="form-control datepicker" data-date-format="mm/dd/yyyy" placeholder="" value="<?php echo  $employee_arr['data']['COR_AOR_certification_expiration_date'];?>">
                                                            </div>
                                                        </span>
                                                    </td>                                   
                                                </tr>
                                                <tr>
                                                    <td><b>Qualified as Project Manager:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo  $employee_arr['data']['qualified_project_manager'];?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <input type="radio" name="qualified_project_manager" placeholder="" value="Yes" <?php if($employee_arr['data']['qualified_project_manager']=='No')echo 'checked="checked"'; ?>> No &nbsp;
                                                                <input type="radio" name="qualified_project_manager" placeholder="" value="Yes" <?php if($employee_arr['data']['qualified_project_manager']=='Yes')echo 'checked="checked"'; ?>> Yes
                                                            </div>
                                                        </span>
                                                    </td>                                   
                                                </tr>
                                                <tr>
                                                    <td><b>Project Management Certification Expiration Date:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo  $employee_arr['data']['project_manager_certification_expiration_date'];?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <input type="text" name="project_manager_certification_expiration_date" class="form-control datepicker" data-date-format="mm/dd/yyyy" placeholder="" value="<?php echo  $employee_arr['data']['project_manager_certification_expiration_date'];?>">
                                                            </div>
                                                        </span>
                                                    </td>                                   
                                                </tr>
                                                <tr>
                                                    <td><b>USAID Supervisor Employee ID Number:</b></td>
                                                    <td>
                                                        <span class="text_emp"><?php echo  $employee_arr['data']['USAID_supervisor_employee_id'];?></span>
                                                        <span class="input_emp">
                                                            <div class="col-md-10">
                                                                <input type="text" name="USAID_supervisor_employee_id" autocomplete="off" class="form-control emp_supervisor" placeholder="" value="<?php echo  $employee_arr['data']['USAID_supervisor_employee_id'];?>" onkeyup="checkExistSupervisor();">
                                                            </div>
                                                             <br>
                                                            <div class="emp_id_err" style="color:red;clear:both"></div>
                                                        </span>
                                                    </td>                                   
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- button to show form -->
                                            <a href="index.php" style="margin-right:15px;"> Back to list </a>
                                            <button type="button" class="btn btn-primary edit_emp" >Edit</button>          
                                                <!-- buttons to submit / cancel form -->
                                            <span>     
                                                <button type="button" class="btn btn-default cancel_emp" >Cancel</button>&nbsp;     
                                                <input type="submit" class="btn btn-primary update_emp" name="update_emp" value="Save"> 
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
    <script src="js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="js/emp.js"></script>
	<script type="text/javascript">
		$(function(){
			$('.input_emp,.cancel_emp,.update_emp').css('display','none');
			$('.edit_emp').click(function(){
				$('.input_emp,.cancel_emp,.update_emp').css('display','inline');	
				$('.text_emp').css('display','none');
				$(this).css('display','none');
			});
			$('.cancel_emp').click(function(){
				$('.input_emp,.cancel_emp,.update_emp').css('display','none');	
				$('.text_emp').css('display','inline');
				$('.edit_emp').css('display','inline');
			});
		});

        function checkExistSupervisor()
        {
            var employee_id = $('.employee_id').val();
            var supervisor_id = $('.emp_supervisor').val();
            $.ajax({
              type: "POST",
              url: "ajax_files/check_exist.php",
              data: {employee_id:employee_id, supervisor_id:supervisor_id},
              success: function(data){
                 if(data!='')$('.emp_id_err').html(data);
                 else $('.emp_id_err').html('');
              }
            });
        }

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