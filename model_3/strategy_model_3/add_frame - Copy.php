<?php
include('config/config.inc.php');
include('include/function.inc.php'); 

//## get All program element ===========
$url = AMP_API_HOST_URL."get_all_program_element.php";
$all_program_element_arr = requestByCURL($url); 

	//## get Detail operating unit ===========
	$operating_unit_id = $_SESSION['operating_unit_id'];
	if($_SESSION['operating_unit_id']!='')
	{
		$operating_unit_id = $_SESSION['operating_unit_id'];
		$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
		$operating_unit_arr = requestByCURL($url); 
	}
	
$url = API_HOST_URL_PROJECT."get_all_project.php";
$all_project = requestByCURL($url);

/*
echo $url = API_HOST_URL_PROJECT."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_id;
$all_project = requestByCURL($url);
print_r($all_project);  exit; */
	
	if(isset($_REQUEST['active_frame_id']))
	{	
		$active_frame_id = trim($_REQUEST['active_frame_id']);
		
			$select_frame_data = "select * from usaid_frame where id='".$active_frame_id."'";
			$result_data = $mysqli->query($select_frame_data);
			$fetch_res_frame = $result_data->fetch_array();
			if($fetch_res_frame >0)
			{
			
				$_SESSION['frame_id']= $fetch_res_frame['id'];
				$_SESSION['frame_name']= $fetch_res_frame['frame_name']; 
			}
	}
	
	$frame_id=$_SESSION['frame_id'];
	$frame_name=$_SESSION['frame_name'];
	
	## Add Project =========================
	if(isset($_REQUEST['project']))
	{	
		$frame_id = trim($_REQUEST['frame_id']); 
		$project_id = trim($_REQUEST['project_id']);
		
		//print_r($_REQUEST); exit;
		$type='Project';
				
		if($project_id=='')
		{
			$error = 'Please select project';
		}
		else
		{	
			 $location=(rand(10,500));
		
			if($frame_id!='')
			{
				 $insert_project_data = "insert into usaid_frame_project_activity set
				frame_id = '".$frame_id."',
				project_activity_id = '".$project_id."',
				operating_unit_id = '".$operating_unit_id."',
				type = '".$type."',
				location = '".$location."'"; 
				$result_data = $mysqli->query($insert_project_data);
			
			}
		}
		
	}
	
	
## Add Development Goal =========================
	if(isset($_REQUEST['development_goal']))
	{	
		$frame_id = trim($_REQUEST['frame_id']); 
		$goal_description = trim($_REQUEST['goal_description']);
		$month = trim($_REQUEST['month']);
		$day = trim($_REQUEST['day']);
		$year = trim($_REQUEST['year']);
		
		$program_element_id = $_REQUEST['program_element_id']; // array program element id
		$type='Goal';
				
		if($goal_description=='')
		{
			$error = 'Description should not be blank';
		}
		elseif($month=='')
		{
			$error = 'Month should not be blank';
		}
		elseif($day=='')
		{
			$error = 'Day should not be blank';
		}
		elseif($year=='')
		{
			$error = 'Year should not be blank';
		}
		else
		{	
			 $date=$month.'/'.$day.'/'.$year;
			 $date_formate=date('Y-m-d',strtotime($date));
			 $location=(rand(10,500));
		
			if($frame_id!='')
			{
				$insert_development_goal_data = "insert into usaid_development_goal set
				frame_id = '".$frame_id."',
				goal_description = '".$goal_description."',
				operating_unit_id = '".$operating_unit_id."',
				location = '".$location."',
				goal_approval_date = '".$date_formate."'"; 
				$result_data = $mysqli->query($insert_development_goal_data);
				$goal_id = $mysqli->insert_id;
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_relation = "insert into usaid_do_ir_program_element set 
							relation_id='".$goal_id."',
							program_element_id='".$program_element_id[$i]."',
							type='".$type."'"; 
							$result_insert_relation = $mysqli->query($insert_relation); 	
						}
					}
					
				}
			}
		}
		
	}
	
		## Add Development Objective =========================
	if(isset($_REQUEST['development_objective']))
	{	
		$frame_id = trim($_REQUEST['frame_id']); 
		$objective_description = trim($_REQUEST['objective_description']);
		$month = trim($_REQUEST['month']);
		$day = trim($_REQUEST['day']);
		$year = trim($_REQUEST['year']);
		
		$program_element_id = $_REQUEST['program_element_id']; // array program element id
		$standard_indicator_id = $_REQUEST['standard_indicator_id'];//array
		$custom_indicator_id = $_REQUEST['custom_indicator_id'];//array

		$type='Objective';
				
		if($objective_description=='')
		{
			$error = 'Description should not be blank';
		}
		elseif($month=='')
		{
			$error = 'Month should not be blank';
		}
		elseif($day=='')
		{
			$error = 'Day should not be blank';
		}
		elseif($year=='')
		{
			$error = 'Year should not be blank';
		}
		else
		{	
			 $date=$month.'/'.$day.'/'.$year;
			 $date_formate=date('Y-m-d',strtotime($date));
			 $location=(rand(10,555));
		
			if($frame_id!='')
			{
				$insert_development_objective = "insert into usaid_development_objective set
				frame_id = '".$frame_id."',
				objective_description = '".$objective_description."',
				operating_unit_id = '".$operating_unit_id."',
				location = '".$location."',
				objective_approval_date = '".$date_formate."'"; 
				$result_data = $mysqli->query($insert_development_objective);
				$objective_id = $mysqli->insert_id;
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_relation = "insert into usaid_do_ir_program_element set 
							relation_id='".$objective_id."',
							program_element_id='".$program_element_id[$i]."',
							type='".$type."'"; 
							$result_insert_relation = $mysqli->query($insert_relation); 	
						}
					}
					
					for($i=0; $i<count($standard_indicator_id); $i++)
					{	
						$indicator_type='Standard';
						if(!empty($standard_indicator_id[$i]))
						{
							$insert_standard_indicator = "insert into usaid_do_ir_indicator set 
							relation_id='".$objective_id."',
							indicator_id='".$standard_indicator_id[$i]."',
							indicator_type='".$indicator_type."',
							type='".$type."'"; 
							$result_insert_standard_indicator = $mysqli->query($insert_standard_indicator); 	
						}
					}
					
					for($i=0; $i<count($custom_indicator_id); $i++)
					{	
						$indicator_type='Custom';
						if(!empty($custom_indicator_id[$i]))
						{
							$insert_custom_indicator = "insert into usaid_do_ir_indicator set 
							relation_id='".$objective_id."',
							indicator_id='".$custom_indicator_id[$i]."',
							indicator_type='".$indicator_type."',
							type='".$type."'"; 
							$result_insert_custom_indicator = $mysqli->query($insert_custom_indicator); 	
						}
					}
					
				}
			}
		}
		
	}
	
	
	## Add Intermediate Result =========================
	if(isset($_REQUEST['intermediate_result']))
	{	
		$frame_id = trim($_REQUEST['frame_id']); 
		$ir_description = trim($_REQUEST['ir_description']);
		$month = trim($_REQUEST['month']);
		$day = trim($_REQUEST['day']);
		$year = trim($_REQUEST['year']);
		
		$program_element_id = $_REQUEST['program_element_id']; // array program element id
		$standard_indicator_id = $_REQUEST['standard_indicator_id'];//array
		$custom_indicator_id = $_REQUEST['custom_indicator_id'];//array

		$type='IR';
				
		if($ir_description=='')
		{
			$error = 'Description should not be blank';
		}
		elseif($month=='')
		{
			$error = 'Month should not be blank';
		}
		elseif($day=='')
		{
			$error = 'Day should not be blank';
		}
		elseif($year=='')
		{
			$error = 'Year should not be blank';
		}
		else
		{	
			 $date=$month.'/'.$day.'/'.$year;
			 $date_formate=date('Y-m-d',strtotime($date));
			  $location=(rand(10,444));
		
			if($frame_id!='')
			{
				$insert_intermediate_result = "insert into usaid_intermediate_result set
				frame_id = '".$frame_id."',
				ir_description = '".$ir_description."',
				operating_unit_id = '".$operating_unit_id."',
				location = '".$location."',
				ir_approval_date = '".$date_formate."'"; 
				$result_data = $mysqli->query($insert_intermediate_result);
				$intermediate_result_id = $mysqli->insert_id;
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_relation = "insert into usaid_do_ir_program_element set 
							relation_id='".$intermediate_result_id."',
							program_element_id='".$program_element_id[$i]."',
							type='".$type."'"; 
							$result_insert_relation = $mysqli->query($insert_relation); 	
						}
					}
					
					for($i=0; $i<count($standard_indicator_id); $i++)
					{	
						$indicator_type='Standard';
						if(!empty($standard_indicator_id[$i]))



						{
							$insert_standard_indicator = "insert into usaid_do_ir_indicator set 
							relation_id='".$intermediate_result_id."',
							indicator_id='".$standard_indicator_id[$i]."',
							indicator_type='".$indicator_type."',
							type='".$type."'"; 
							$result_insert_standard_indicator = $mysqli->query($insert_standard_indicator); 	
						}
					}
					
					for($i=0; $i<count($custom_indicator_id); $i++)
					{	
						$indicator_type='Custom';
						if(!empty($custom_indicator_id[$i]))
						{
							$insert_custom_indicator = "insert into usaid_do_ir_indicator set 
							relation_id='".$intermediate_result_id."',
							indicator_id='".$custom_indicator_id[$i]."',
							indicator_type='".$indicator_type."',
							type='".$type."'"; 
							$result_insert_custom_indicator = $mysqli->query($insert_custom_indicator); 	
						}
					}
					
				}
			}
		}
		
	}		
	
	
		## Add Sub Intermediate Result =========================
	if(isset($_REQUEST['sub_intermediate_result']))
	{	
		$frame_id = trim($_REQUEST['frame_id']); 
		$sub_ir_description = trim($_REQUEST['sub_ir_description']);
		$month = trim($_REQUEST['month']);
		$day = trim($_REQUEST['day']);
		$year = trim($_REQUEST['year']);
		
		$program_element_id = $_REQUEST['program_element_id']; // array program element id
		$standard_indicator_id = $_REQUEST['standard_indicator_id'];//array
		$custom_indicator_id = $_REQUEST['custom_indicator_id'];//array

		$type='Sub_IR';
				
		if($sub_ir_description=='')
		{
			$error = 'Description should not be blank';
		}
		elseif($month=='')
		{
			$error = 'Month should not be blank';
		}
		elseif($day=='')
		{
			$error = 'Day should not be blank';
		}
		elseif($year=='')
		{
			$error = 'Year should not be blank';
		}
		else
		{	
			 $date=$month.'/'.$day.'/'.$year;
			 $date_formate=date('Y-m-d',strtotime($date));
			  $location=(rand(10,555));
		
			if($frame_id!='')
			{
				$insert_sub_intermediate_result = "insert into usaid_sub_intermediate_result set
				frame_id = '".$frame_id."',
				sub_ir_description = '".$sub_ir_description."',
				operating_unit_id = '".$operating_unit_id."',
				location = '".$location."',
				sub_ir_approval_date = '".$date_formate."'"; 
				$result_data = $mysqli->query($insert_sub_intermediate_result);
				$sub_intermediate_result_id = $mysqli->insert_id;
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_relation = "insert into usaid_do_ir_program_element set 
							relation_id='".$sub_intermediate_result_id."',
							program_element_id='".$program_element_id[$i]."',
							type='".$type."'"; 
							$result_insert_relation = $mysqli->query($insert_relation); 	
						}
					}
					
					for($i=0; $i<count($standard_indicator_id); $i++)
					{	
						$indicator_type='Standard';
						if(!empty($standard_indicator_id[$i]))
						{
							$insert_standard_sub_indicator = "insert into usaid_do_ir_indicator set 
							relation_id='".$sub_intermediate_result_id."',
							indicator_id='".$standard_indicator_id[$i]."',
							indicator_type='".$indicator_type."',
							type='".$type."'"; 
							$result_insert_standard_sub_indicator = $mysqli->query($insert_standard_sub_indicator); 	
						}
					}
					
					for($i=0; $i<count($custom_indicator_id); $i++)
					{	
						$indicator_type='Custom';
						if(!empty($custom_indicator_id[$i]))
						{
							$insert_custom_sub_indicator = "insert into usaid_do_ir_indicator set 
							relation_id='".$sub_intermediate_result_id."',
							indicator_id='".$custom_indicator_id[$i]."',
							indicator_type='".$indicator_type."',
							type='".$type."'"; 
							$result_insert_custom_sub_indicator = $mysqli->query($insert_custom_sub_indicator); 	
						}
					}
					
				}
			}
		}
		
	}	
	
	
	
	
## for create node link ===============================
if($_POST['node_link'])
{
	$link_data= stripslashes($_REQUEST['link_data']);
	
	$arr_node_data=explode('"nodeDataArray": [',$link_data);
	$arr_node_two=$arr_node_data[1]; 
	
	$arr_type_id=explode('{"key":"-',$arr_node_two);
	$arr_node_loc=explode('"loc":"',$arr_node_two);
	
	
	for($i=1; $i<=count($arr_node_loc); $i++)
		{
			$filter_loc=explode('",',$arr_node_loc[$i]);
			$loc = $filter_loc[0]; 
			
			$filter_id=explode('",',$arr_type_id[$i]);
			$id_type = $filter_id[0];
			$id_type_merg=explode('-',$id_type);
			$type=$id_type_merg[0];
			$id=$id_type_merg[1];
			if($type='GOAL')
			{
				$update_goal_loc = "update usaid_development_goal set 
					location='".$loc."' where id='".$id."'";
				$result_update_goal_loc = $mysqli->query($update_goal_loc); 
			}
			if($type='DO')
			{
				$update_do_loc = "update usaid_development_objective set 
					location='".$loc."' where id='".$id."'";
				$result_update_do_loc = $mysqli->query($update_do_loc); 
			}
			if($type='IR')
			{
				$update_ir_loc = "update usaid_intermediate_result set 
					location='".$loc."' where id='".$id."'";
				$result_update_ir_loc = $mysqli->query($update_ir_loc); 
			}
			if($type='SUBIR')
			{
				$update_subir_loc = "update usaid_sub_intermediate_result set 
					location='".$loc."' where id='".$id."'";
				$result_update_subir_loc = $mysqli->query($update_subir_loc); 
			}
			if($type='Project')
			{
				$update_project_loc = "update usaid_frame_project_activity set 
					location='".$loc."' where id='".$id."'";
				$result_update_project_loc = $mysqli->query($update_project_loc); 
			}
						
		}
	
	
	## for link data array=========	
	$arr_all_data=explode('"linkDataArray": [',$link_data);
	$arr_one=$arr_all_data[0];
	$arr_two=$arr_all_data[1];
	$from_arr=explode('"from":"-',$arr_two); 
	$to_arr=explode('"to":"-',$arr_two);
	$from_port_arr=explode('"fromPort":"',$arr_two);
	$to_port_arr=explode('"toPort":"',$arr_two);
	if($from_arr!='')
	{
		$delete_data = "delete from usaid_data_relation where frame_id='".$frame_id."'";
		$result_delete_data = $mysqli->query($delete_data);
	for($i=1; $i<=count($from_arr); $i++)
		{
			$filter_from=explode('",',$from_arr[$i]);
			$from_merg = $filter_from[0];
			$from_both=explode('-',$from_merg);
			$from_type=$from_both[0];
			$from_id=$from_both[1];
			$filter_to=explode('",',$to_arr[$i]);
			$to_merg=$filter_to[0];
			$to_both=explode('-',$to_merg);
			$to_type=$to_both[0];
			$to_id=$to_both[1];
			$filter_from_port=explode('",',$from_port_arr[$i]);
			$from_port=$filter_from_port[0];
			$filter_to_port=explode('",',$to_port_arr[$i]);
			$to_port=$filter_to_port[0];
			$all_loc=$filter_to_port[1];
			$location=explode('"points":[',$all_loc);
			$loc=$location[1];
			$filter_loc=explode(']',$loc);
			$loc=$filter_loc[0];
			
				if($frame_id && $from_id && $from_type && $to_id && $to_type && $from_port && $to_port!='')
					{
						 $insert_data_relation = "insert into usaid_data_relation set 
							frame_id='".$frame_id."',
							from_id='".$from_id."',
							from_type='".$from_type."',
							to_id='".$to_id."',
							to_type='".$to_type."',
							from_port='".$from_port."',
							to_port='".$to_port."',
							location='".$loc."'";
						 $result_insert_data_relation = $mysqli->query($insert_data_relation); 
					}					
		
			}
	}

}

			
## fetch development_goal==============
	$arr=array();
	$i=1;
	$select_goal="select * from usaid_development_goal where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_goal);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="GOAL-".$fetch_data['id'];
		$arr[$i]['name']=$fetch_data['goal_description'];
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['goal_approval_date'];
		$arr[$i]['color']="#f2dcdb";
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select program_element_id from usaid_do_ir_program_element where relation_id='".$id."' and type='Goal'";
		$program_element_res = $mysqli->query($select_program_element);
		$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['program_element_id']; 
			$url = AMP_API_HOST_URL."get_program_element.php?program_element_id=".$program_element_id;
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
		$arr[$i]['name']=$fetch_data['objective_description'];
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['objective_approval_date'];
		$arr[$i]['color']="#b8dfec";
		
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select program_element_id from usaid_do_ir_program_element where relation_id='".$id."' and type='Objective'";
		$program_element_res = $mysqli->query($select_program_element);
		$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['program_element_id']; 
			$url = AMP_API_HOST_URL."get_program_element.php?program_element_id=".$program_element_id;
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
	
	
	
	## fetch Intermediate Result==============
	$select_IR="select * from usaid_intermediate_result where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_IR);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="IR-".$fetch_data['id'];
		$arr[$i]['name']=$fetch_data['ir_description'];
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['ir_approval_date'];
		$arr[$i]['color']="#c3d69b";
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select program_element_id from usaid_do_ir_program_element where relation_id='".$id."' and type='IR'";
		$program_element_res = $mysqli->query($select_program_element);
		$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['program_element_id']; 
			$url = AMP_API_HOST_URL."get_program_element.php?program_element_id=".$program_element_id;
			$get_program_element = requestByCURL($url); 
			$arr[$i]['program_element'][] = $get_program_element['data']['program_element_name'];
		}
		
		$type='IR';
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
	
	## fetch Sub Intermediate Result==============
	$select_Sub_IR="select * from usaid_sub_intermediate_result where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_Sub_IR);
	while($fetch_data = $total_res->fetch_array())
	{
	$arr[$i]['key']="SUBIR-".$fetch_data['id'];
	$arr[$i]['name']=$fetch_data['sub_ir_description'];
	$arr[$i]['location']=$fetch_data['location'];
	$arr[$i]['approval_date']=$fetch_data['sub_ir_approval_date'];
	$arr[$i]['color']="#b3a2c7";
	
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select program_element_id from usaid_do_ir_program_element where relation_id='".$id."' and type='Sub_IR'";
		$program_element_res = $mysqli->query($select_program_element);
		$arr[$i]['program_element_id']=array();
		//echo count($fetch_program_element);exit;
		//for($k=0; $k<count($fetch_program_element); $k++)
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['program_element_id']; 
			$url = AMP_API_HOST_URL."get_program_element.php?program_element_id=".$program_element_id;
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
	
				
## fetch object==============
	$select_goal="select * from usaid_frame_project_activity where frame_id='$frame_id'";
	$total_res = $mysqli->query($select_goal);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="Project-".$fetch_data['id'];
		$project_id=$fetch_data['project_activity_id'];
		$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id;
		$get_progect = requestByCURL($url);
		$arr[$i]['name'] = $get_progect['data']['title'];
		
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=" ";
		$arr[$i]['color']="#f44242";
		$id=$fetch_data['id'];
		
		## show program element on moush hover==============
		$select_program_element="select program_element_id from usaid_do_ir_program_element where relation_id='".$id."' and type='Project'";
		$program_element_res = $mysqli->query($select_program_element);
		$arr[$i]['program_element_id']=array();
		while($fetch_program_element = $program_element_res->fetch_array())
		{	
			$program_element_id=$fetch_program_element['program_element_id']; 
			$url = AMP_API_HOST_URL."get_program_element.php?program_element_id=".$program_element_id;
			$get_program_element = requestByCURL($url); 
			$arr[$i]['program_element'][] = $get_program_element['data']['program_element_name'];
		}
		
		
		$type='Project';
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>USAID</title>
	<!-- Bootstrap -->
	<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
	<link href="css/sumoselect.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">	
	<script type="text/javascript" src="js/go.js"></script>
	<script type="text/javascript" src="js/frame.js"></script>
	<style>
		nav > ul{
			list-style: none;
		}
		nav > ul >li{
			float: left;
			margin-top: 2px;
		}
		nav > ul > li > div{
			width: 20px;
			height: 20px;
			margin-right: 14px;
			margin-top: 2px;
			border: 1px solid #000;
		}
	</style>

<script>
function showProjectActivity(project_id)
{
	alert(val);
	$.ajax({
		type: "POST",
		url: "get_activity.php",
		data: {project_id:project_id},
		context:elem,
		success: function(data){
			$(elem).closest('.req_link_to').find('.show_activity').html(data);
		}
	}); 
	
}
</script>
</head>
<body onLoad="init()">
	<!-- Header Include Here -->
	<?php include 'include/header.html'; ?>
	<!-- Menu -->
	<div class="menu">
		<div class="container-fluid">
			<div class="col-md-4 text-center">
				<a class="usa-button usa-button-outline-inverse active-class" href="framework_management.php">Framework Management</a>
			</div>
			<div class="col-md-4 text-center">
				<a class="usa-button usa-button-outline-inverse" href="indicator_management.php">Indicator Management</a>
			</div>
			<div class="col-md-4 text-center">
				<a class="usa-button usa-button-outline-inverse" href="#">Development Objective Agreement Objective</a>
			</div>
		</div>
	</div>
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12 info">Operating Unit: <span class="disc"><?php echo $operating_unit_arr['data']['operating_unit_description']; ?></span></div>
			</div>
		</div>
	</div>
	<!-- Adding Adding Frame -->
	<div class="add-frame">
		<div class="container-fluid">
			<div class="col-md-9" style="text-align: center;">
				<h2 class="text-center" style="margin-top: 30px;"><?php echo ucwords($frame_name); ?></h2>
				<ul class="list-inline">
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#developmentGoal"><i class="fa fa-plus" aria-hidden="true"></i> Add Development Goal</button></li>
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#developmentObjective"><i class="fa fa-plus" aria-hidden="true"></i> Add Development Objective</button></li>
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#intermediate"><i class="fa fa-plus" aria-hidden="true"></i> Add Intermediate Result</button></li>
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#sub-intermediate"><i class="fa fa-plus" aria-hidden="true"></i> Add Sub-Intermediate Result</button></li>
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#project"><i class="fa fa-plus" aria-hidden="true"></i> Add Project</button></li>
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#activity"><i class="fa fa-plus" aria-hidden="true"></i> Add Project Activity</button></li>
				</ul>
			</div>
			<div class="col-md-3" style="margin:13px 0">
				<nav id="keys">
					<ul>
						<li><div style="background:#f2dcdb;"></div>Development Goal</li>
						<li><div style="background:#b8dfec;"></div>Development Objective</li>
						<li><div style="background:#c3d69b;"></div>Intermediate Result</li>
						<li><div style="background:#b3a2c7;"></div>Sub-Intermediate Result</li>
						<li><div style="background:#f44242;"></div>Project</li>
						<li><div style="background:#0801bf;"></div>Project Activity</li>
					</ul>
				</nav>
			</div>
			<div class="col-md-12">
			<form method="post" action="">

				<div id="myDiagramDiv" style="width:100%; height:500px; border:1px solid black; border-radius: 10px;"></div>
				<div class="text-center">
					
				</div>
				
				<textarea id="mySavedModel" name="link_data"  style="width:100%;height:500px; display: none;">
					
				{ 
				"class": "go.GraphLinksModel",
				"copiesArrays": true,
				"copiesArrayObjects": true,
				"linkFromPortIdProperty": "fromPort",
				"linkToPortIdProperty": "toPort",
				"nodeDataArray": [
					<?php for($i=1; $i<=count($arr); $i++){
						$html = '{"key":"-'.$arr[$i]['key'].'", "createDate":"'.$arr[$i]['approval_date'].'",';
						
						/*if program element exists*/
						if($arr[$i]['program_element']!='') {
						$html.='"programElements":"';
						for($k=0; $k<count($arr[$i]['program_element']); $k++) 
						{
							$html.=$arr[$i]['program_element'][$k].','; 
						}
						
						$html = substr_replace($html,"",-1);
						$html.='",';
						}
						
					$html.='"name":"'.$arr[$i]['name'].'", "loc":"'.$arr[$i]['location'].'", "color":"'.$arr[$i]['color'].'",';
					
						if(count($arr[$i]['left'])>0)
						{
							$html.= '"leftArray":[ ';
							for($j=0;$j<count($arr[$i]['left']);$j++)
							{
								$html.='{"portId":"'.$arr[$i]['left'][$j].'", "portColor":"#cc585c"} ';
								
								if($j<count($arr[$i]['left'])-1)
									{
										$html.=',';
									}	
							}
							$html.= ' ], ';
						}
						else
						{
							$html.= ' "leftArray":[], ';
						}
						
						if(count($arr[$i]['right'])>0)
						{
							$html.= '"rightArray":[ ';
							for($j=0;$j<count($arr[$i]['right']);$j++)
							{
								$html.='{"portId":"'.$arr[$i]['right'][$j].'", "portColor":"#cc585c"} ';
								if($j<count($arr[$i]['right'])-1)
									{
										$html.=',';
									}	
							}
							$html.= ' ], ';
						}
						else 
						{
							$html.= ' "rightArray":[], ';
						}
						
						if(count($arr[$i]['top'])>0)
						{
							$html.= '"topArray":[ ';
							for($j=0;$j<count($arr[$i]['top']);$j++)
							{
								$html.='{"portId":"'.$arr[$i]['top'][$j].'", "portColor":"#cc585c"} ';
								if($j<count($arr[$i]['top'])-1)
									{
										$html.=',';
									}	
							}
							$html.= ' ], ';
						}
						else
						{
							$html.= ' "topArray":[], ';
						}
						
						if(count($arr[$i]['bottom'])>0)
						{
							$html.= '"bottomArray":[ ';
							for($j=0;$j<count($arr[$i]['bottom']);$j++)
							{
								$html.='{"portId":"'.$arr[$i]['bottom'][$j].'", "portColor":"#cc585c"} ';
								if($j<count($arr[$i]['bottom'])-1)
									{
										$html.=',';
									}	
							}
							$html.= ' ] ';
						}
						else 
						{
							$html.= ' "bottomArray":[] ';
						}
						$html.='}';
						
						if($i<count($arr))
						{
							$html.=',';
						}
						echo $html;
					}?>],
				"linkDataArray": [
					<?php
					 for($k=1; $k<=count($data_link); $k++)
					 {					
						$link='{"from":"-'.$data_link[$k]['from'].'", "to":"-'.$data_link[$k]['to'].'", "fromPort":"'.$data_link[$k]['from_port'].'", "toPort":"'.$data_link[$k]['to_port'].'" , "points":['.$data_link[$k]['location'].']}';
						if($k<count($data_link))
							{
								$link.=',';
							}	
							echo $link;	
					  }		
						?>
								]
					
				}
			</textarea>
			<div class="text-center">
				<button class="usa-button-outline" onClick="load()">Cancel</button>
				<input type="submit" name="node_link" value="save" onClick="save()" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;">
			</div>
			</form>

		</div>
	</div>
</div>
<!-- Add project to IR Sub IR -->
<div id="project" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
				<legend class="usa-drop_text">Add Project</legend>
			</div>
			<div class="modal-body">
				<form class="usa-form" method="post" action="">
				<input type="hidden" name="frame_id" value="<?php echo $frame_id; ?>">
					<fieldset>
						<div class="form-group">
							<label for="input-type-textarea">Select Project</label>
							 <select name="project_id" id="options">
							 
								<option value="">Select</option>
								<?php
								for($j=0; $j<count($all_project['data']); $j++){ ?>	
									<option value="<?php echo $all_project['data'][$j]['project_id']?>" ><?php echo $all_project['data'][$j]['title'];?></option>
								<?php } ?>
							</select>
						</div>
						<br>
						<input type="submit" name="project" value="Save" style="margin-top:10px; margin-bottom: 0;" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Add project activity to IR Sub IR -->
<div id="activity" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
				<legend class="usa-drop_text">Add Project Activity</legend>
			</div>
			<div class="modal-body">
				<form class="usa-form" method="post" action="">
					<fieldset>
						<div class="form-group">
							<label for="input-type-textarea">Select Project Activity</label>
							<select name="options" id="options" onChange="showProjectActivity(this.value);">
								 <option value="">Select</option>
								<?php
								for($j=0; $j<count($all_project['data']); $j++){ ?>	
									<option value="<?php echo $all_project['data'][$j]['project_id']?>" ><?php echo $all_project['data'][$j]['title'];?></option>
								<?php } ?>
							</select>
						</div>

						<br>
						<input type="submit" name="" value="Save" style="margin-top:10px; margin-bottom: 0;" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Development Goals Form -->

<div id="developmentGoal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
				<legend class="usa-drop_text">Add Development Goal</legend>
			</div>
			<div class="modal-body">
				<form class="usa-form" method="post" action="">
				<input type="hidden" name="frame_id" value="<?php echo $frame_id; ?>">
					<fieldset>
						<div class="form-group">
							<label for="input-type-textarea">Associated Program Elements</label>
							<select multiple="multiple" name="program_element_id[]" class="SlectBox" required>							
								<?php
								for($j=0; $j<count($all_program_element_arr['data']); $j++)
								{
									?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						<?php
								}
								?>
								
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Development Goal Description</label>		
							<textarea id="input-type-textarea" name="goal_description"></textarea>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Development Goal Create Date</label>
							<div class="usa-date-of-birth">
								<div class="usa-form-group usa-form-group-month">
									<label for="date_of_birth_1">Month</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
								</div>
							</div>
						</div>
						<br><br>
						<input type="submit" name="development_goal" value="Save" style="margin-top:40px; margin-bottom: 0;" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Development Objective Form -->

<div id="developmentObjective" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
				<legend class="usa-drop_text">Add Development Objective</legend>
			</div>
			<div class="modal-body">
				<form class="usa-form" method="post" action="">
				<input type="hidden" name="frame_id" value="<?php echo $frame_id; ?>">
					<fieldset>
						<div class="form-group">
							<label for="input-type-textarea">Associated Program Elements</label>
							<select multiple="multiple" name="program_element_id[]" class="SlectBox" required>
								<?php
								for($j=0; $j<count($all_program_element_arr['data']); $j++)
								{
									?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						<?php
								}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="input-type-textarea">Associated Standard Indicators</label>
							<select multiple="multiple" name="standard_indicator_id[]" class="stin">
								<option value="1">Percentage of reduced post harvest losses (1.1)</option>
								<option value="2">Percentage of reduced seed storage losses (1.2)</option>
								<option value="3">Increased of HDDS (1.3)</option>
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Associated Custom Indicators</label>
							<select multiple="multiple" name="custom_indicator_id" class="cuin">
								<option value="1">Percentage of reduced post harvest losses (1.1)</option>
								<option value="2">Percentage of reduced seed storage losses (1.2)</option>
								<option value="3">Increased of HDDS (1.3)</option>
								<option value="4">No. of farmers with introduced storage system (1.4)</option>
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Development Objective Description</label>						
							<textarea id="input-type-textarea" name="objective_description"></textarea>

						</div>

						<div class="form-group">
							<label for="input-type-textarea">Development Objective Create Date</label>
							<div class="usa-date-of-birth">
								<div class="usa-form-group usa-form-group-month">
									<label for="date_of_birth_1">Month</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="month" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="day" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="year" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
								</div>
							</div>
						</div>
						<br><br>
						<input type="submit" value="Save" name="development_objective" style="margin-top:40px; margin-bottom: 0;" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Development Intermediate Result -->
<div id="intermediate" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
				<legend class="usa-drop_text">Add Intermediate Result</legend>
			</div>
			<div class="modal-body">
				<form class="usa-form" method="post" action="">
				<input type="hidden" name="frame_id" value="<?php echo $frame_id; ?>">
					<fieldset>
						<div class="form-group">
							<label for="input-type-textarea">Associated Program Elements</label>
							<select multiple="multiple" name="program_element_id[]" class="SlectBox" required>
								<?php
								for($j=0; $j<count($all_program_element_arr['data']); $j++)
								{
									?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						<?php
								}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="input-type-textarea">Associated Standard Indicators</label>
							<select multiple="multiple" name="standard_indicator_id[]" class="stin">
								<option value="1">Percentage of reduced post harvest losses (1.1)</option>
								<option value="2">Percentage of reduced seed storage losses (1.2)</option>
								<option value="3">Increased of HDDS (1.3)</option>
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Associated Custom Indicators</label>
							<select multiple="multiple" name="custom_indicator_id" class="cuin">
								<option value="1">Percentage of reduced post harvest losses (1.1)</option>
								<option value="2">Percentage of reduced seed storage losses (1.2)</option>
								<option value="3">Increased of HDDS (1.3)</option>
								<option value="4">No. of farmers with introduced storage system (1.4)</option>
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Intermediate Result Description</label>						
							<textarea id="input-type-textarea" name="ir_description"></textarea>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Intermediate Result Create Date</label>
							<div class="usa-date-of-birth">
								<div class="usa-form-group usa-form-group-month">
									<label for="date_of_birth_1">Month</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
								</div>
							</div>
						</div>
						<br><br>
						<input type="submit" name="intermediate_result" value="Save" style="margin-top:40px; margin-bottom: 0;" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Development Sub-Intermediate Result -->
<div id="sub-intermediate" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="cls btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
				<legend class="usa-drop_text">Add Sub-Intermediate Result</legend>
			</div>
			<div class="modal-body">
				<form class="usa-form" method="post" action="">
				<input type="hidden" name="frame_id" value="<?php echo $frame_id; ?>">
					<fieldset>
						<div class="form-group">
							<label for="input-type-textarea">Associated Program Elements</label>
							<select multiple="multiple" name="program_element_id[]" class="SlectBox" required>
								<?php
								for($j=0; $j<count($all_program_element_arr['data']); $j++)
								{
									?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						<?php
								}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="input-type-textarea">Associated Standard Indicators</label>
							<select multiple="multiple" name="standard_indicator_id[]" class="stin">
								<option value="1">Percentage of reduced post harvest losses (1.1)</option>
								<option value="2">Percentage of reduced seed storage losses (1.2)</option>
								<option value="3">Increased of HDDS (1.3)</option>
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Associated Custom Indicators</label>
							
							<select multiple="multiple" name="custom_indicator_id" class="cuin">
								<option value="1">Percentage of reduced post harvest losses (1.1)</option>
								<option value="2">Percentage of reduced seed storage losses (1.2)</option>
								<option value="3">Increased of HDDS (1.3)</option>
								<option value="4">No. of farmers with introduced storage system (1.4)</option>
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Sub-Intermediate Result Description</label>						
							<textarea id="input-type-textarea" name="sub_ir_description"></textarea>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Sub-Intermediate Result Create Date</label>
							<div class="usa-date-of-birth">
								<div class="usa-form-group usa-form-group-month">
									<label for="date_of_birth_1">Month</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
								</div>
							</div>
						</div>
						<br><br>
						<input type="submit" name="sub_intermediate_result" value="Save" style="margin-top:40px; margin-bottom: 0;" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Include all compiled plugins (below), or include individual files as needed -->

<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script src="js/uswds.min.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script src="js/jquery.sumoselect.min.js"></script>
<script>
	$(document).ready(function () {
		$('.SlectBox').SumoSelect({
			placeholder: 'Select Program Element',
			okCancelInMulti: false,
			search : true,
			csvDispCount: 1
		});

		$('.stin').SumoSelect({
			placeholder: 'Select Standard Indicator	',
			okCancelInMulti: false,
			search : true,
			csvDispCount: 1
		});

		$('.cuin').SumoSelect({
			placeholder: 'Select Custom Indicator	',
			okCancelInMulti: false,
			search : true,
			csvDispCount: 1
		});
	});
</script>

</body>
</html>