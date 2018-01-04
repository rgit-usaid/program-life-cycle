<?php
include('config/config.inc.php');
include('include/function.inc.php');
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
        $USAID_operating_unit_id_assi = $mysqli->real_escape_string(trim($_REQUEST['USAID_operating_unit_id_assi']));
        $date_assi_operating_unit =  dateFormat(trim($_REQUEST['date_assi_operating_unit']));
        $assi_work_location_office_name = $mysqli->real_escape_string(trim($_REQUEST['assi_work_location_office_name']));
        $assi_work_location_postal_location_code = $mysqli->real_escape_string(trim($_REQUEST['assi_work_location_postal_location_code']));
        $assi_work_location_street_address = $mysqli->real_escape_string(trim($_REQUEST['assi_work_location_street_address']));
        $assi_work_location_city = $mysqli->real_escape_string(trim($_REQUEST['assi_work_location_city']));
        $assi_work_location_country = $mysqli->real_escape_string(trim($_REQUEST['assi_work_location_country']));
        if($USAID_operating_unit_id_assi=='')
        {
            $error_t4 = "Please input USAID Operating Unit Assigned";
        }
        else{
            if($employee_id!='')
            {
                $update_employee = "update usaid_employee set 
                    USAID_operating_unit_id_assi='".$USAID_operating_unit_id_assi."',
                    date_assi_operating_unit='".$date_assi_operating_unit."',                   
                    assi_work_location_office_name='".$assi_work_location_office_name."',   
                    assi_work_location_postal_location_code='".$assi_work_location_postal_location_code."',
                    assi_work_location_street_address='".$assi_work_location_street_address."',
                    assi_work_location_city='".$assi_work_location_city."',
                    assi_work_location_country='".$assi_work_location_country."' where employee_id = '".$employee_id."'";
                    $result_employee = $mysqli->query($update_employee);
                    header("location:work_location.php");
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
<style>
 
.frmSearch {}
#ou-list{float:left;list-style:none;margin:0;padding:0;  height:250px; overflow-y:auto;position:absolute;}
#ou-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
#ou-list li:hover{background:#F0F0F0;}
#search-box{padding: 10px;border: #F0F0F0 1px solid;}
</style>
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
                    <li class="active"><a href="work_location.php">Work Location</a></li>
                    <li><a href="phone.php">Phone/Email</a></li>
                    <li><a href="emergency_contact.php">Emergency Contacts</a></li>
                    <li><a href="javascript:void(0);">Job-Related Skills</a></li>
			    </ul>
			    <div class="main-data">
			    	<div class="personal-data">
			    		<h1>MY INFORMATION - WORK LOCATION</h1>
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
                                if(isset($error_t4) and $error_t4!='')
                                {
                                    echo '<div style="text-align:center;color:red;">'.$error_t4.'</div>';
                                }
                            ?>
                                <form method="post" action="">
                                    <input type="hidden" name="employee_id" value="<?php echo $employee_id;?>">
                                    <input type="hidden" name="tab_type" value="4">
                                    <table class="table">
                                        <tbody> 
                                            <tr>
                                                <td><b>USAID Operating Unit Assigned:</b></td>
                                                <td>
                                                <?php
                                                $operating_unit = '';
                                                if(isset($employee_arr['data']))
                                                {
                                                    $url = API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$employee_arr['data']['USAID_operating_unit_id_assi'];
                                                    $operating_unit_arr = requestByCURL($url);
                                                    $operating_unit = $operating_unit_arr['data']['operating_unit_abbreviation'];
                                                } 
                                                ?>  
                                                    <span class="text_work"><?php echo $operating_unit;?></span>
                                                    <span class="input_work">
                                                    <div class="frmSearch">
                                                        <input type="text" id="search-box" placeholder="" class="form-control" autocomplete="off" value="<?php echo $operating_unit;?>" />
                                                        <div id="suggesstion-box"></div>
                                                    </div>
                                                    <input type="hidden" name="USAID_operating_unit_id_assi" class="form-control USAID_operating_unit_id_assi" value="<?php echo $employee_arr['data']['USAID_operating_unit_id_assi'];?>"></span>
                                                </td>
                                            </tr>
                                              <tr>
                                                <td><b>Date Assigned To Operating Unit:</b></td>
                                                <td>
                                                    <span class="text_work"><?php echo  $employee_arr['data']['date_assi_operating_unit'];?></span>
                                                    <span class="input_work"> 
                                                    <input type="text" name="date_assi_operating_unit" class="form-control datepicker" value="<?php echo  $employee_arr['data']['date_assi_operating_unit'];?>"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Assigned Work Location Office Name:</b></td>
                                                <td>
                                                    <span class="text_work"><?php echo  $employee_arr['data']['assi_work_location_office_name'];?></span>
                                                    <span class="input_work">
                                                    <input type="text" name="assi_work_location_office_name" class="form-control" value="<?php echo  $employee_arr['data']['assi_work_location_office_name'];?>"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Assigned Work Location Postal on Location Code:</b></td>
                                                <td>
                                                    <span class="text_work"><?php echo  $employee_arr['data']['assi_work_location_postal_location_code'];?></span>
                                                    <span class="input_work">
                                                    <input type="text" name="assi_work_location_postal_location_code" class="form-control" value="<?php echo  $employee_arr['data']['assi_work_location_postal_location_code'];?>"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Assigned Work Location Street Address:</b></td>
                                                    <td>
                                                    <span class="text_work"><?php echo  $employee_arr['data']['assi_work_location_street_address'];?></span>
                                                    <span class="input_work">
                                                    <input type="text" name="assi_work_location_street_address" class="form-control" value="<?php echo  $employee_arr['data']['assi_work_location_street_address'];?>"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Assigned Work Location City:</b></td>
                                                <td>
                                                    <span class="text_work"><?php echo  $employee_arr['data']['assi_work_location_city'];?></span>
                                                    <span class="input_work">
                                                    <input type="text" name="assi_work_location_city" class="form-control" value="<?php echo  $employee_arr['data']['assi_work_location_city'];?>"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Assigned Work Location Country:</b></td>
                                                <td>
                                                    <span class="text_work"><?php echo  $employee_arr['data']['assi_work_location_country'];?></span>
                                                    <span class="input_work">
                                                    <input type="text" name="assi_work_location_country" class="form-control" value="<?php echo  $employee_arr['data']['assi_work_location_country'];?>"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                        <!-- button to show form -->
                                        <a href="index.php" style="margin-right:15px;"> Back to list </a>
                                        <button type="button" class="btn btn-primary edit_work" >Edit</button>          
                                                <!-- buttons to submit / cancel form -->
                                        <span>     
                                            <button type="button" class="btn btn-default cancel_work" >Cancel</button>&nbsp;     
                                            <input type="submit" class="btn btn-primary update_work" name="update_emp" value="Save"> 
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
    <script>
        $(document).ready(function(){
           $("#search-box").keyup(function(){
                var keyword = $(this).val();
                 $.ajax({
                  type: "POST",
                  url: "ajax_files/operating_unit_drop.php",
                  data: {keyword:keyword},
                   beforeSend: function(){
                    $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                 },
                  success: function(data){
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background","#FFF");
                  }
                }); 
           
            });
        });
        function selectOU(val,val_id) {
        $("#search-box").val(val);
        $(".USAID_operating_unit_id_assi").val(val_id);
        $("#suggesstion-box").hide();
        }
    </script>
    <script type="text/javascript" language="javascript" class="init">
		$(function(){
			$('.input_work,.cancel_work,.update_work').css('display','none');
			$('.edit_work').click(function(){
				$('.input_work,.cancel_work,.update_work').css('display','inline');	
				$('.text_work').css('display','none');
				$(this).css('display','none');
			});
			$('.cancel_work').click(function(){
				$('.input_work,.cancel_work,.update_work').css('display','none');	
				$('.text_work').css('display','inline');
				$('.edit_work').css('display','inline');
			});
		});
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