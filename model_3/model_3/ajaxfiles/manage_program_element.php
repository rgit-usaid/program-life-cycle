<?php
include("../config/functions.inc.php");
/*add program element to activity*/
if(isset($_REQUEST['add_program_element']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	$data_msg = array();
	$project_id = trim($_REQUEST['project_id']);
	$program_element_id_arr = array();
	$program_element_id_arr = $_REQUEST['program_element_id'];
	$program_element_perc_arr = array();
	$program_element_perc_arr = $_REQUEST['program_element_percentage'];	
	
	/*clear all the previous activity data*/
	$del = "DELETE FROM usaid_project_program_element WHERE project_id='".$project_id."'";
	$exe = $mysqli->query($del);
	
	if(!$exe){
		$data_msg["msg_type"]="error";
		$data_msg["msg"]="Some Error Occurred in program element deletion";
		echo json_encode($data_msg);
		exit;
	}
	
	
	$ins = "INSERT INTO usaid_project_program_element (project_id, program_element_id, percentage) VALUES ";
	for($i=0;$i<count($program_element_id_arr);$i++){
		$ins.="('".$project_id."','".$program_element_id_arr[$i]."',".$program_element_perc_arr[$i]."),";
	}
	

	$ins = substr($ins, 0, -1);
	$exe = $mysqli->query($ins);
	
	if($ins){
		$data_msg["msg_type"]="success";
		$data_msg["msg"]="Program Element Save Successfully";	
	}
	else{
		$data_msg["msg_type"]="error";
		$data_msg["msg"]="Some Error Occurred";	
	}
	
	echo json_encode($data_msg);
	exit;
}

/*list activity program element*/
if(isset($_REQUEST['list_data']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	$html='';
	$project_id = trim($_REQUEST['project_id']);
	
	$url = API_HOST_URL_PROJECT."get_project_program_element.php?project_id=".$project_id;  
    $program_elem_arr = requestByCURL($url);
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
if(isset($_REQUEST['graph_data']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	$data_msg = array();
	$data_value=  array();
	$data_label=  array();
	$project_id = trim($_REQUEST['project_id']);
	
	$url = API_HOST_URL_PROJECT."get_project_program_element.php?project_id=".$project_id;  
    $program_elem_arr = requestByCURL($url);
	$actv_prgm_elem = $program_elem_arr['data'];
	
	
	if(count($actv_prgm_elem)>0){
		for($i=0; $i<count($actv_prgm_elem); $i++){
			$data_value[$i]= $actv_prgm_elem[$i]['percentage'];
			$data_label[$i]= "(".$actv_prgm_elem[$i]['program_element_code'].') '.$actv_prgm_elem[$i]['program_element_name']; 
		}
		
		$data_msg["msg_type"]="success";
		$data_msg["msg"]="Data Found";
		$data_msg["prgm_elem"]=$data_value;	
		$data_msg["prgm_elem_label"]=$data_label;		
	}
	else{
		$data_msg["msg_type"]="error";
		$data_msg["msg"]="No Data Found";	
	}
	
	echo json_encode($data_msg);
	exit;
}
?>