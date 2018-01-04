<?php
include("../config/functions.inc.php");

###insert team member ========
if(isset($_REQUEST['add_project_team']) && isset($_REQUEST['employee_id']))
{
	$data_msg = array(); 
	$employee_id = trim($_REQUEST['employee_id']);
	$project_id = trim($_REQUEST['project_id']);
	$project_team_role = trim($_REQUEST['project_team_role']);
	$team_member_start_date	 = trim($_REQUEST['team_member_start_date']);
	$team_member_start_date = dateFormat($team_member_start_date);
	
	$sel_team = "SELECT employee_id FROM usaid_project_team WHERE employee_id='".$employee_id."' AND project_id='".$project_id."' AND project_team_role='".$project_team_role."' AND team_member_end_date IS NULL";
	$exe_team = $mysqli->query($sel_team);
	
	if($exe_team->num_rows<1){
		
		$ins_team = "INSERT INTO usaid_project_team set employee_id='".$employee_id."',project_id='".$project_id."',project_team_role='".$project_team_role."', team_member_start_date='".$team_member_start_date."'";
		$result_team = $mysqli->query($ins_team); 
		if($result_team){
			$data_msg['msg_type'] = "Success";
			$data_msg['msg'] = "Congratulation team member has added successfully";
		}
		else{
			$data_msg['msg_type'] = "Error";
			$data_msg['msg'] = "Error";
		}
	}
	else{
		$data_msg['msg_type'] = "Error";
		$data_msg['msg'] = "Team member is already exists with the same role.";	
	} 
	
	echo json_encode($data_msg);
}

###remove team member ========
if(isset($_REQUEST['remove_team_member']) && isset($_REQUEST['project_id']) && isset($_REQUEST['emp_id'])){
	$data_msg = array(); 
	$employee_id = trim($_REQUEST['emp_id']);
	$project_id = trim($_REQUEST['project_id']);
	$project_team_role = trim($_REQUEST['emp_role']);
	
	$update_team = "UPDATE usaid_project_team set team_member_status='Remove', team_member_end_date = NOW() 
	WHERE employee_id= '".$employee_id."' AND project_id= '".$project_id."' AND project_team_role='".$project_team_role."' AND team_member_status='Active'";
	$exe_team = $mysqli->query($update_team);
	
	if($exe_team){
		$data_msg['msg_type'] = "Success";
		$data_msg['msg'] = "Team member has removed successfully";
	}
	else{
		$data_msg['msg_type'] = "Error";
		$data_msg['msg'] = "Some error occured";
	}

	echo json_encode($data_msg);
}

###get current team member ========
if(isset($_REQUEST['get_project_team']) && isset($_REQUEST['project_id'])){
	$html='';
	
	$project_id = trim($_REQUEST['project_id']);
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);
	$project_owner_id = $project_arr['data']['employee_id']; 
	
	$html.='<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="img-blk"><form class="team_member_info_form">';
	$html.='<div class="head">Project Creator</div>';
	$html.='<div class="img-src-blk">';
	$empinfo_url = API_HOST_URL_PROJECT2."get_hr_employee.php?employee_id=".$project_owner_id; 
	$empinfo_arr = requestByCURL($empinfo_url);
	
	$picture="img/user.png";
	if($empinfo_arr['data']['picture']!=""){
		$picture=PICTURE_SERVER.$empinfo_arr['data']['picture'];
		$html.='<img src="'.$picture.'" class="center-block img-responsive emp-img"/>';
	}
	else
	$html.='<img src="'.$picture.'" class="center-block img-responsive emp-img"/>';
	$html.='</div>';
	if($empinfo_arr['data']['second_name']!=""){
		$full_name=$empinfo_arr['data']['first_name'].' '.$empinfo_arr['data']['second_name'].' '.$empinfo_arr['data']['last_name'];
	}
	else{
		$full_name=$empinfo_arr['data']['first_name'].' '.$empinfo_arr['data']['last_name'];
	}
	
	$html.='<div class="user-info">'.$full_name.'<br/>'.$empinfo_arr['data']['USAID_desk_phone_number'].'</div></form></div></div>';
			
	$url = API_HOST_URL_PROJECT."get_project_current_team.php?project_id=".$project_id;  
	$project_team_arr = requestByCURL($url);
	$project_team_data = $project_team_arr["data"];
	if(count($project_team_data)>0){
		for($i=0; $i<count($project_team_data); $i++){
			$html.='<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="img-blk"><form class="team_member_info_form">';
			$html.='<div class="head">'.$project_team_data[$i]['project_team_role'].'</div>';
			$html.='<div class="img-src-blk">';
			
			
			$empinfo_url = API_HOST_URL_PROJECT2."get_hr_employee.php?employee_id=".$project_team_data[$i]['employee_id'];  
			$empinfo_arr = requestByCURL($empinfo_url);
			
			$picture="img/user.png";
			if($empinfo_arr['data']['picture']!=""){
				$picture=PICTURE_SERVER.$empinfo_arr['data']['picture'];
				$html.='<img src="'.$picture.'"  class="center-block img-responsive emp-img"/>';
			}
			else
			$html.='<img src="'.$picture.'"  class="center-block img-responsive emp-img"/>';
			$html.='</div>';
			
			
			if($empinfo_arr['data']['second_name']!=""){
				$full_name=$empinfo_arr['data']['first_name'].' '.$empinfo_arr['data']['second_name'].' '.$empinfo_arr['data']['last_name'];
			}
			else{
				$full_name=$empinfo_arr['data']['first_name'].' '.$empinfo_arr['data']['last_name'];
			}
			
			$html.='<div class="user-info">'.$full_name.'<br/>'.$empinfo_arr['data']['USAID_desk_phone_number'].'<br/>Start Date '.$project_team_data[$i]['team_member_start_date'].'</div><div><input type="hidden" value="'.$full_name.'" name="emp_name" class="emp_name"/><input type="hidden" value="'.$picture.'" name="emp_img" class="emp_img"/><input type="hidden" value="'.$project_team_data[$i]['employee_id'].'" name="emp_id" class="emp_id"/><input type="hidden" value="'.$project_team_data[$i]['project_team_role'].'" name="emp_role" class="emp_role"/><button type="button" class="usa-button-outline remove_team_member">Remove</button> <button type="button" class="usa-button-active edit_team_member">Edit</button></div></form></div></div>';	
		}
	}
	else{
		$html.='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="no_data_found_msg">No team member found for this project</div></div>';
	}

	$html.='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="gray-line"></div></div>';
	echo $html;	
}
###==update team member====
if(isset($_REQUEST['edit_project_team']) && isset($_REQUEST['emp_id'])){
	$data_msg = array(); 
	$employee_id = trim($_REQUEST['emp_id']);
	$project_id = trim($_REQUEST['project_id']);
	$project_team_role = trim($_REQUEST['project_team_role']);
	$emp_old_role = trim($_REQUEST['emp_old_role']);
	
 	$sel_team = "SELECT employee_id FROM usaid_project_team WHERE employee_id='".$employee_id."' AND project_id='".$project_id."' AND project_team_role='".$project_team_role."' AND activity_id IS NULL AND team_member_status='Active'";
	$exe_team = $mysqli->query($sel_team);
	if($exe_team->num_rows<1){
		$update_team = "UPDATE usaid_project_team set project_team_role='".$project_team_role."'
		WHERE employee_id= '".$employee_id."' AND project_id= '".$project_id."' AND project_team_role='".$emp_old_role."' AND activity_id IS NULL AND team_member_status='Active'";
		$exe_team = $mysqli->query($update_team);
		
		if($exe_team){
			$data_msg['msg_type'] = "Success";
			$data_msg['msg'] = "Team member has updated successfully";
		}
		else{
			$data_msg['msg_type'] = "Error";
			$data_msg['msg'] = "Some error occured";
		}
	}
	else{
		$data_msg['msg_type'] = "Error";
		$data_msg['msg'] = "Team member is already exists with the same role.";	
	} 
	echo json_encode($data_msg);
}
###get team history ========
if(isset($_REQUEST['get_old_project_team']) && isset($_REQUEST['project_id'])){
	$html='';
	
	$project_id = trim($_REQUEST['project_id']);
	$url = API_HOST_URL_PROJECT."get_project_history_team.php?project_id=".$project_id;  
	$project_team_arr = requestByCURL($url);
	
	$project_team_data = $project_team_arr["data"];
	if(count($project_team_data)>0){
		for($i=0; $i<count($project_team_data); $i++){
			$html.='<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="img-blk"><form class="team_member_info_form">';
			$html.='<div class="head">'.$project_team_data[$i]['project_team_role'].'</div>';
			$html.='<div class="img-src-blk">';
			
			$empinfo_url = API_HOST_URL_PROJECT2."get_hr_employee.php?employee_id=".$project_team_data[$i]['employee_id'];  
			$empinfo_arr = requestByCURL($empinfo_url);
			
			
			$picture="img/user.png";
			if($empinfo_arr['data']['picture']!=""){
				$picture=PICTURE_SERVER.$empinfo_arr['data']['picture'];
				$html.='<img src="'.$picture.'"  class="center-block img-responsive emp-img"/>';
			}
			else
			$html.='<img src="'.$picture.'" class="center-block img-responsive emp-img"/>';
			$html.='</div>';
			
			if($empinfo_arr['data']['second_name']!=""){
				$full_name=$empinfo_arr['data']['first_name'].' '.$empinfo_arr['data']['second_name'].' '.$empinfo_arr['data']['last_name'];
			}
			else{
				$full_name=$empinfo_arr['data']['first_name'].' '.$empinfo_arr['data']['last_name'];
			}
			
			$html.='<div class="user-info">'.$full_name.'<br/>'.$empinfo_arr['data']['USAID_desk_phone_number'].'<br/>Start Date '.$project_team_data[$i]['team_member_start_date'].'<br/>End Date '.$project_team_data[$i]['team_member_end_date'].'</div><div></div></form></div></div>';	
		}
	}
	else{
		$html.='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="no_data_found_msg">No team history found for this project</div></div>';
	}

	$html.='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="gray-line"></div></div>';
	echo $html;	
}

###get team history ========
if(isset($_REQUEST['change_team_marker']) && isset($_REQUEST['project_id']) && isset($_REQUEST['team_marker'])){	
	$data_msg = array(); 
	$team_marker = trim($_REQUEST['team_marker']);
	$project_id = trim($_REQUEST['project_id']);
	
	$update_team = "UPDATE usaid_project set team_marker='".$team_marker."'
	WHERE project_id= '".$project_id."'";
	$exe_team = $mysqli->query($update_team);
	
	if($exe_team){
		$data_msg['msg_type'] = "Success";
		$data_msg['msg'] = "Team member has updated successfully";
	}
	else{
		$data_msg['msg_type'] = "Error";
		$data_msg['msg'] = "Some error occured";
	}

	echo json_encode($data_msg);

}
?>