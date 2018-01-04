<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============

if(isset($_REQUEST['ou_id']) && isset($_REQUEST['ou_id']))
{
    $data = $arr = array();
	$select_frame_data = "select * from usaid_frame where operating_unit_id='".$_REQUEST['ou_id']."' AND status = 'Active'";
	$result_data = $mysqli->query($select_frame_data);
	$fetch_res_frame = $result_data->fetch_array();
	
	$frame_id= $fetch_res_frame['id'];
    $select_goal="select * from usaid_development_goal where frame_id='$frame_id'";
	$i=1;
	$total_res = $mysqli->query($select_goal);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="GOAL-".$fetch_data['id'];
		$arr[$i]['gohashid']=$fetch_data['gohashid'];
		$arr[$i]['name']=$fetch_data['goal_description'];
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['goal_approval_date'];
		$arr[$i]['color']="#f2dcdb";
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select * from usaid_association where gohashid='".$arr[$i]['gohashid']."' and association_type='Program Element'";
		$program_element_res = $mysqli->query($select_program_element);
		//$arr[$i]['program_element_id']=array();
		
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['association_id']; 
			$url = API_HOST_URL_PROJECT."get_program_element.php?program_element_id=".$program_element_id;
			$get_program_element = requestByCURL($url); 
			$arr[$i]['program_element'][] = $get_program_element['data']['program_element_name'];
		}
		
		
		$type='GOAL';
		$select_dat_link="select from_port from usaid_data_relation where frame_id='$frame_id' AND ( `from_id` = '".$id."' AND `from_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		$arr[$i]['right']=array();
		$arr[$i]['left']=array();
		$arr[$i]['top']=array();
		$arr[$i]['bottom']=array();
		
		
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check fo right port==
				$port_dir = "";
				if(strpos($fetch_res['from_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check fo left port==
				if(strpos($fetch_res['from_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check fo top port==
				if(strpos($fetch_res['from_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check fo bottom port==
				if(strpos($fetch_res['from_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['from_port']; 
			}
		}
		
		$select_dat_link="select to_port from usaid_data_relation where frame_id='$frame_id' AND ( `to_id` = '".$id."' AND `to_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check fo right port==
				$port_dir = "";
				if(strpos($fetch_res['to_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check fo left port==
				if(strpos($fetch_res['to_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check fo top port==
				if(strpos($fetch_res['to_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check fo bottom port==
				if(strpos($fetch_res['to_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['to_port']; 
			}
		}
		$i++;
	}
	
	## fetch development_objective==============
	$select_objective="select * from usaid_development_objective where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_objective);
	
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="DO-".$fetch_data['id'];
		$arr[$i]['gohashid']=$fetch_data['gohashid'];
		$arr[$i]['name']=$fetch_data['objective_description'];
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['objective_approval_date'];
		$arr[$i]['color']="#b8dfec";
		
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select * from usaid_association where gohashid='".$arr[$i]['gohashid']."' and association_type='Program Element'";
		$program_element_res = $mysqli->query($select_program_element);
		//$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['association_id']; 
			$url = API_HOST_URL_PROJECT."get_program_element.php?program_element_id=".$program_element_id;
			$get_program_element = requestByCURL($url); 
			$arr[$i]['program_element'][] = $get_program_element['data']['program_element_name'];
		}
		
		
		$type='DO';
		$select_dat_link="select from_port from usaid_data_relation where frame_id='$frame_id' AND ( `from_id` = '".$id."' AND `from_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		$arr[$i]['right']=array();
		$arr[$i]['left']=array();
		$arr[$i]['top']=array();
		$arr[$i]['bottom']=array();
		 
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check for right port==
				$port_dir = "";
				if(strpos($fetch_res['from_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check for left port==
				if(strpos($fetch_res['from_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check for top port==
				if(strpos($fetch_res['from_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check for bottom port==
				if(strpos($fetch_res['from_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['from_port']; 
			}
		}
		
		$select_dat_link="select to_port from usaid_data_relation where frame_id='$frame_id' AND (`to_id` = '".$id."' AND `to_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check for right port==
				
				$port_dir = "";
				if(strpos($fetch_res['to_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check for left port==
				if(strpos($fetch_res['to_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check for top port==
				if(strpos($fetch_res['to_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check for bottom port==
				if(strpos($fetch_res['to_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['to_port']; 
			}
		}
		$i++;
	}
	
	## fetch Intermediate Result==============
	$select_IR="select * from usaid_intermediate_result where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_IR);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="IR-".$fetch_data['id'];
		$arr[$i]['gohashid']=$fetch_data['gohashid'];
		$arr[$i]['name']=$fetch_data['ir_description'];
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['ir_approval_date'];
		$arr[$i]['color']="#c3d69b";
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select * from usaid_association where gohashid='".$arr[$i]['gohashid']."' and association_type='Program Element'";
		$program_element_res = $mysqli->query($select_program_element);
		//$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['association_id']; 
			$url = API_HOST_URL_PROJECT."get_program_element.php?program_element_id=".$program_element_id;
			$get_program_element = requestByCURL($url); 
			$arr[$i]['program_element'][] = $get_program_element['data']['program_element_name'];
		}

		
		$type='IR';
		$select_dat_link="select from_port from usaid_data_relation where frame_id='$frame_id' AND (`from_id` = '".$id."' AND `from_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		$arr[$i]['right']=array();
		$arr[$i]['left']=array();
		$arr[$i]['top']=array();
		$arr[$i]['bottom']=array();
		 
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check for right port==
				$port_dir = "";
				if(strpos($fetch_res['from_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check for left port==
				if(strpos($fetch_res['from_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check for top port==
				if(strpos($fetch_res['from_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check for bottom port==
				if(strpos($fetch_res['from_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['from_port']; 
			}
		}
		
		$select_dat_link="select to_port from usaid_data_relation where frame_id='$frame_id' AND (  `to_id` = '".$id."' AND `to_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check for right port==
				
				$port_dir = "";
				if(strpos($fetch_res['to_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check for left port==
				if(strpos($fetch_res['to_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check for top port==
				if(strpos($fetch_res['to_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check for bottom port==
				if(strpos($fetch_res['to_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['to_port']; 
			}
		}
		
		$i++;
	}
	
	## fetch Sub Intermediate Result==============
	$select_Sub_IR="select * from usaid_sub_intermediate_result where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_Sub_IR);
	while($fetch_data = $total_res->fetch_array())
	{
	$arr[$i]['key']="SUBIR-".$fetch_data['id'];
	$arr[$i]['gohashid']=$fetch_data['gohashid'];
	$arr[$i]['name']=$fetch_data['sub_ir_description'];
	$arr[$i]['location']=$fetch_data['location'];
	$arr[$i]['approval_date']=$fetch_data['sub_ir_approval_date'];
	$arr[$i]['color']="#b3a2c7";
	
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select * from usaid_association where gohashid='".$arr[$i]['gohashid']."' and association_type='Program Element'";
		$program_element_res = $mysqli->query($select_program_element);
		//$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['association_id']; 
			$url = API_HOST_URL_PROJECT."get_program_element.php?program_element_id=".$program_element_id;
			$get_program_element = requestByCURL($url); 
			$arr[$i]['program_element'][] = $get_program_element['data']['program_element_name'];
			
		}
		
		$type='SUBIR';
		$select_dat_link="select from_port from usaid_data_relation where frame_id='$frame_id' AND ( `from_id` = '".$id."' AND `from_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		$arr[$i]['right']=array();
		$arr[$i]['left']=array();
		$arr[$i]['top']=array();
		$arr[$i]['bottom']=array();
		 
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check for right port==
				$port_dir = "";
				if(strpos($fetch_res['from_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check for left port==
				if(strpos($fetch_res['from_port'], 'left') !==false)
				{
					$port_dir = "left";
				} 
				
				##==check for top port==
				if(strpos($fetch_res['from_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check for bottom port==
				if(strpos($fetch_res['from_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['from_port']; 
			}
		}
		
		$select_dat_link="select to_port from usaid_data_relation where frame_id='$frame_id' AND (  `to_id` = '".$id."' AND `to_type` = '".$type."')";
		$total_link_res = $mysqli->query($select_dat_link);
		
		
		if($total_link_res->num_rows>0){
			while($fetch_res = $total_link_res->fetch_array()){
				##==check for right port==
				
				$port_dir = "";
				if(strpos($fetch_res['to_port'], 'right') !==false)
				{
					$port_dir = "right";
				} 
				##==check for left port==
				if(strpos($fetch_res['to_port'], 'left') !==false)
				{ 
					$port_dir = "left";
				} 
				
				##==check for top port==
				if(strpos($fetch_res['to_port'], 'top') !==false)
				{
					$port_dir = "top";
				} 
				
				##==check for bottom port==
				if(strpos($fetch_res['to_port'], 'bottom') !==false)
				{
					$port_dir = "bottom";
				} 
				
				$arr[$i][$port_dir][] =  $fetch_res['to_port']; 
			}
		}
		$i++;
	}
	
	
	## Get all data link result==============
	$data_link=array();
	$k=1;
	$select_dat_link="select * from usaid_data_relation where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_dat_link);
	while($fetch_data = $total_res->fetch_array())
	{
		$data_link[$k]['from']=$fetch_data['from_type'].'-'.$fetch_data['from_id'];
		$data_link[$k]['to']=$fetch_data['to_type'].'-'.$fetch_data['to_id'];
		$data_link[$k]['from_port']=$fetch_data['from_port'];
		$data_link[$k]['to_port']=$fetch_data['to_port'];
		$data_link[$k]['location']=$fetch_data['location'];
		$k++;
	}	
	
	
	if(count($arr)>0)
    {
        $data['blocks'] = $arr;
		$data['links'] = $data_link;   
        deliverResponse(200,'Record Found',$data); 
    }
    else{
        deliverResponse(200,'No Record Found',NULL);
    }
}
else{
     deliverResponse(200,'Invalid Request',NULL);
}    
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>