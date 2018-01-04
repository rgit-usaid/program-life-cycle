<?php
include("../config/functions.inc.php");
$employee_id = $_SESSION['user'];

## check and ensert program element in archive table  ====================
function checkProgramElementArchiveData($activity_id)
{
	global $mysqli;
	if($activity_id!='')
	{
		$url = API_HOST_URL_PROJECT."get_activity_program_element.php?activity_id=".$activity_id; 
		$program_element_arr = requestByCURL($url);
		if(count($program_element_arr['data'])>0)
		{
			$insert_archive_activity_pe = "insert into usaid_archive_activity_pe set
			 activity_id='".$activity_id."',
			 modified_by='".$_SESSION['first_last_name']."'"; 
			$result_archive_activity_pe = $mysqli->query($insert_archive_activity_pe);
			if($result_archive_activity_pe)
			{
				$archive_id = $mysqli->insert_id;
				for($i=0; $i<count($program_element_arr['data']); $i++)
				{
					$insert_archive_project_activity_pe_chield = "insert into usaid_archive_project_activity_program_element set
						 arcive_activity_pe_id='".$archive_id."',
						 activity_id='".$program_element_arr['data'][$i]['activity_id']."',
						 program_element_id='".$program_element_arr['data'][$i]['program_element_id']."',
						 percentage='".$program_element_arr['data'][$i]['percentage']."',
						 added_on='".dateFormat($program_element_arr['data'][$i]['added_on'])."'";
					$result_archive_project_activity_pe_chield = $mysqli->query($insert_archive_project_activity_pe_chield);
				}
			}  
		}
		
	}
} 

/*add program element to activity*/
if(isset($_REQUEST['add_program_element']) && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!=""){
	$data_msg = array();
	$activity_id = trim($_REQUEST['activity_id']);
	$program_element_id_arr = array();
	$program_element_id_arr = $_REQUEST['program_element_id'];
	$program_element_perc_arr = array();
	$program_element_perc_arr = $_REQUEST['program_element_percentage'];	
	
	checkProgramElementArchiveData($activity_id); // call for check and ensert program element 
	/*clear all the previous activity data*/
	$del = "DELETE FROM usaid_project_activity_element WHERE activity_id='".$activity_id."'";
	$exe = $mysqli->query($del);
	
	if(!$exe){
		$data_msg["msg"]="error";
		$data_msg["msg_type"]="Some Error Occurred in activity deletion";
		echo json_encode($data_msg);
		exit;
	}
	
	
	$ins = "INSERT INTO usaid_project_activity_element (activity_id, program_element_id, percentage) VALUES ";
	for($i=0;$i<count($program_element_id_arr);$i++){
		$ins.="('".$activity_id."','".$program_element_id_arr[$i]."',".$program_element_perc_arr[$i]."),";
	}
	

	$ins = substr($ins, 0, -1);
	$exe = $mysqli->query($ins);
	
	if($ins){
		$data_msg["msg"]="success";
		$data_msg["msg_type"]="Program Element Save Successfully";	
	}
	else{
		$data_msg["msg"]="error";
		$data_msg["msg_type"]="Some Error Occurred";	
	}
	
	echo json_encode($data_msg);
	exit;
}

/*list activity program element*/
if(isset($_REQUEST['list_data']) && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!=""){
	$html='';
	$activity_id = trim($_REQUEST['activity_id']);
	
	$url = API_HOST_URL_PROJECT."get_activity_program_element.php?activity_id=".$activity_id;  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $program_elem_arr = json_decode($output,true);
	$actv_prgm_elem = $program_elem_arr['data'];
	
	if(count($actv_prgm_elem)>0){	
		for($i=0; $i<count($actv_prgm_elem); $i++){
			$id_with_dash = str_replace(".","-",$actv_prgm_elem[$i]['program_element_id']);
			
			$html.='<tr class="prgm_elem_info saved_data">';	
			$html.='<td class="elem_label">'.$actv_prgm_elem[$i]['program_element_code'].'</td>';
			$html.='<td class="elem_label">'.$actv_prgm_elem[$i]['program_element_name'].'<input type="hidden" name="program_element_id[]" class="program_element_code '.$id_with_dash.'" value="'.$actv_prgm_elem[$i]['program_element_id'].'" title="'.$id_with_dash.'"/></td>';
			$html.='<td class="elem_ip"><span class="perc_outer disp-none"><input type="text" name="program_element_percentage[]" class="form-control perc only_num" style="display:inline-block;width:90%" value="'.$actv_prgm_elem[$i]['percentage'].'"/> %</span><span class="elem_perc_text">'.$actv_prgm_elem[$i]['percentage'].'%</span></td>';
			$html.='<td class="elem_close_img"><img src="'.HOST_URL.'img/cross.jpg" width="15" class="remove_prgm_elem disp-none" /></td>';
			$html.='</tr>';
		}
	}
	else{
		$html.='<tr class="bold no_elem_found">';	
		$html.='<td colspan="4" class="text-center">No Program Element Found.</td>';
		$html.='</tr>';	
	}
	
	echo $html;
}

/*list activity program element*/
if(isset($_REQUEST['graph_data']) && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!=""){
	$data_msg = array();
	$data_value=  array();
	$data_label=  array();
	$activity_id = trim($_REQUEST['activity_id']);
	
	$url = API_HOST_URL_PROJECT."get_activity_program_element.php?activity_id=".$activity_id;  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $program_elem_arr = json_decode($output,true);
	$actv_prgm_elem = $program_elem_arr['data'];
	
	
	if(count($actv_prgm_elem)>0){
		
		for($i=0; $i<count($actv_prgm_elem); $i++){
			$data_value[$i]= $actv_prgm_elem[$i]['percentage'];
			$data_label[$i]= "(".$actv_prgm_elem[$i]['program_element_code'].') '.$actv_prgm_elem[$i]['program_element_name']; 
		}
		
		$data_msg["msg"]="success";
		$data_msg["msg_type"]="Data Found";
		$data_msg["prgm_elem"]=$data_value;	
		$data_msg["prgm_elem_label"]=$data_label;		
	}
	else{
		$data_msg["msg"]="error";
		$data_msg["msg_type"]="No Data Found";	
	}
	
	echo json_encode($data_msg);
	exit;
}
?>