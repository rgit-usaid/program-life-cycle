<?php
include('config/config.inc.php');
include('include/function.inc.php'); 

//## get Detail operating unit ===========
$operating_unit_id = $_SESSION['operating_unit_id'];
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id'];
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
//## get All program element ===========
$url = AMP_API_HOST_URL."get_all_program_element.php";
$all_program_element_arr = requestByCURL($url); 

//## get All standard indicator
$url = API_HOST_URL."get_all_standard_indicator.php";
$st_indicator_arr = requestByCURL($url); 

//## get All custom indicator
$url = API_HOST_URL."get_all_custom_indicator_by_ou_id.php?ou_id=".$operating_unit_arr['data']['operating_unit_id'];
$cs_indicator_arr = requestByCURL($url);

$url = API_HOST_URL_PROJECT."get_all_project.php";
$all_project = requestByCURL($url);

## Move for archived frame=======================
if(isset($_REQUEST['move_archive']))
	{	
		$archive_frame_id = trim($_REQUEST['archive_frame_id']); 
		if($archive_frame_id!='')
		{
			$updata_frame = "update usaid_frame set
			status = 'Archive', modified_on = NOW() where id='".$archive_frame_id."'";
			$result_data = $mysqli->query($updata_frame);
			if($result_data)
			{
				header("location:archived_list.php");
			}
			
		}
	}	
	
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
			 $location=(rand(10,300));
		
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
			 $location=(rand(150,500));
		
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
				
				$gohashid = "DG-".$goal_id;
				$upd =  "UPDATE usaid_development_goal set gohashid='".$gohashid."' WHERE id='".$goal_id."'";
				$result_data = $mysqli->query($upd);

				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='DG-".$goal_id."',
							association_type='Program Element',
							association_id='".$program_element_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	
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
			 $location=(rand(350,666));
		
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
				
				$gohashid = "DO-".$objective_id;
				$upd =  "UPDATE usaid_development_objective set gohashid='".$gohashid."' WHERE id='".$objective_id."'";
				$result_data = $mysqli->query($upd);
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Program Element',
							association_id='".$program_element_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 		
						}
					}
					
					for($i=0; $i<count($standard_indicator_id); $i++)
					{	
						$indicator_type='Standard';
						if(!empty($standard_indicator_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Standard Indicator',
							association_id='".$standard_indicator_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	
						}
					}
										
					for($i=0; $i<count($custom_indicator_id); $i++)
					{	
						$indicator_type='Custom';
						if(!empty($custom_indicator_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Custom Indicator',
							association_id='".$custom_indicator_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	 	
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
			  $location=(rand(399,599));
		
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
				$gohashid="IR-".$intermediate_result_id;
				
				$upd =  "UPDATE usaid_intermediate_result set gohashid='IR-".$intermediate_result_id."' WHERE id='".$intermediate_result_id."'";
				$result_data = $mysqli->query($upd);
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Program Element',
							association_id='".$program_element_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	
						}
					}
					
					for($i=0; $i<count($standard_indicator_id); $i++)
					{	
						$indicator_type='Standard';
						if(!empty($standard_indicator_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Standard Indicator',
							association_id='".$standard_indicator_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	
						}
					}
					
					for($i=0; $i<count($custom_indicator_id); $i++)
					{	
						$indicator_type='Custom';
						if(!empty($custom_indicator_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Custom Indicator',
							association_id='".$custom_indicator_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc);  	
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
			  $location=(rand(450,700));
		
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
				$gohashid="SR-".$sub_intermediate_result_id;
				
				$upd =  "UPDATE usaid_sub_intermediate_result set gohashid='SR-".$sub_intermediate_result_id."' WHERE id='".$sub_intermediate_result_id."'";
				$result_data = $mysqli->query($upd);
				
				if($result_data)
				{
					for($i=0; $i<count($program_element_id); $i++)
					{
						if(!empty($program_element_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Program Element',
							association_id='".$program_element_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc);	
						}
					}
					
					for($i=0; $i<count($standard_indicator_id); $i++)
					{	
						$indicator_type='Standard';
						if(!empty($standard_indicator_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Standard Indicator',
							association_id='".$standard_indicator_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	
						}
					}
					
					for($i=0; $i<count($custom_indicator_id); $i++)
					{	
						$indicator_type='Custom';
						if(!empty($custom_indicator_id[$i]))
						{
							$insert_assoc = "insert into usaid_association set 
							gohashid='".$gohashid."',
							association_type='Custom Indicator',
							association_id='".$custom_indicator_id[$i]."'"; 
							$result_insert_assoc = $mysqli->query($insert_assoc); 	
						}
					}
					
				}
			}
		}	
	}	
	
## for create node link ===============================
if($_POST['node_link'])
{
	if($frame_id!='')
		{
			$updata_frame = "update usaid_frame set
			modified_on = NOW() 
			where id='".$frame_id."'"; 
			$result_data = $mysqli->query($updata_frame);
		}

	$link_data= stripslashes($_REQUEST['link_data']);
	

	$arr_node_data=explode('"nodeDataArray": [',$link_data);
	$arr_node_two=$arr_node_data[1]; 
	
	$arr_type_id=explode('{"key":"-',$arr_node_two);
	$arr_node_loc=explode('"loc":"',$arr_node_two);
	$all_link_arr=array();
	
	$update_type_id=array();
	
	for($i=1; $i<=count($arr_node_loc); $i++)
		{
			$filter_loc=explode('",',$arr_node_loc[$i]);
			$loc = $filter_loc[0]; 
			$filter_id=explode('",',$arr_type_id[$i]);
			$id_type = $filter_id[0];
			$id_type_merg=explode('-',$id_type);
			$type=$id_type_merg[0];
			$id=$id_type_merg[1];
		
			$update_type_id[$i] = $id_type_merg[0].'-'.$id_type_merg[1];
							
			if($type=='GOAL')
			{
						
				$update_goal_loc = "update usaid_development_goal set 
					location='".$loc."' where id='".$id."'";
				$result_update_goal_loc = $mysqli->query($update_goal_loc);
				$all_link_arr['GOAL'][] = $id;
			}
			if($type=='DO')
			{
				$update_do_loc = "update usaid_development_objective set 
					location='".$loc."' where id='".$id."'";
				$result_update_do_loc = $mysqli->query($update_do_loc); 
				$all_link_arr['DO'][] = $id;
			}
			if($type=='IR')
			{
				$update_ir_loc = "update usaid_intermediate_result set 
					location='".$loc."' where id='".$id."'";
				$result_update_ir_loc = $mysqli->query($update_ir_loc); 
				$all_link_arr['IR'][] = $id;
			}
			if($type=='SUBIR')
			{
				$update_subir_loc = "update usaid_sub_intermediate_result set 
					location='".$loc."' where id='".$id."'";
				$result_update_subir_loc = $mysqli->query($update_subir_loc); 
				$all_link_arr['SUBIR'][] = $id;
			}						
		}
		
	## fetch all current frame node for delete after form submit ==========		
			$select_goal="select id from usaid_development_goal where frame_id='$frame_id'";
			$total_res = $mysqli->query($select_goal);
			while($fetch_data = $total_res->fetch_array())
			{
				$del_arr['delete_key']="GOAL-".$fetch_data['id'];
				
				if(!in_array($del_arr['delete_key'], $update_type_id))
				{
					$key_both=explode('-',$del_arr['delete_key']);
					$key_type = $key_both[0];
					$key_id = $key_both[1];
						$delete_goal_data = "delete from usaid_development_goal where id='".$key_id."'";
						$result_goal = $mysqli->query($delete_goal_data);
						if($result_goal)
						{
							$gohash_id="DG-".$key_id;
							$delete_association_data = "delete from usaid_association where gohashid='".$gohash_id."'";
							$result_association = $mysqli->query($delete_association_data);
						}
						
				}
			}
			
			$select_DO="select id from usaid_development_objective where frame_id='$frame_id'";
			$total_res_DO = $mysqli->query($select_DO);
			while($fetch_DO_data = $total_res_DO->fetch_array())
			{
				$del_arr['delete_key']="DO-".$fetch_DO_data['id'];
				
				if(!in_array($del_arr['delete_key'], $update_type_id))
				{
					$key_both=explode('-',$del_arr['delete_key']);
					$key_type = $key_both[0];
					$key_id = $key_both[1];
						$delete_DO_data = "delete from usaid_development_objective where id='".$key_id."'";
						$result_DO = $mysqli->query($delete_DO_data);
						if($result_DO)
						{
							$gohash_id="DO-".$key_id;
							$delete_association_data = "delete from usaid_association where gohashid='".$gohash_id."'";
							$result_association = $mysqli->query($delete_association_data);
						}
				}
			}
			
			$select_IR="select id from usaid_intermediate_result where frame_id='$frame_id'";
			$total_res_IR = $mysqli->query($select_IR);
			while($fetch_IR_data = $total_res_IR->fetch_array())
			{
				$del_arr['delete_key']="IR-".$fetch_IR_data['id'];
				
				if(!in_array($del_arr['delete_key'], $update_type_id))
				{
					$key_both=explode('-',$del_arr['delete_key']);
					$key_type = $key_both[0];
					$key_id = $key_both[1];
						$delete_IR_data = "delete from usaid_intermediate_result where id='".$key_id."'";
						$result_IR = $mysqli->query($delete_IR_data);
						if($result_IR)
						{
							$gohash_id="IR-".$key_id;
							$delete_association_data = "delete from usaid_association where gohashid='".$gohash_id."'";
							$result_association = $mysqli->query($delete_association_data);
						}
				}
			}
			
			$select_SUBIR="select id from usaid_sub_intermediate_result where frame_id='$frame_id'";
			$total_res_SUBIR = $mysqli->query($select_SUBIR);
			while($fetch_SUBIR_data = $total_res_SUBIR->fetch_array())
			{
				$del_arr['delete_key']="SUBIR-".$fetch_SUBIR_data['id'];
				
				if(!in_array($del_arr['delete_key'], $update_type_id))
				{
					$key_both=explode('-',$del_arr['delete_key']);
					$key_type = $key_both[0];
					$key_id = $key_both[1];
						$delete_SUBIR_data = "delete from usaid_sub_intermediate_result where id='".$key_id."'";
						$result_SUBIR = $mysqli->query($delete_SUBIR_data);
						if($result_SUBIR)
						{
							$gohash_id="SR-".$key_id;
							$delete_association_data = "delete from usaid_association where gohashid='".$gohash_id."'";
							$result_association = $mysqli->query($delete_association_data);
						}
						
				}
			}

	$arr=array();
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
	$i=1;
	$select_goal="select * from usaid_development_goal where frame_id='$frame_id'";
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
			$url = AMP_API_HOST_URL."get_program_element.php?program_element_id=".$program_element_id;
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

$page_name="framework_management";


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
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/go.js"></script>
	
	<link rel='stylesheet' href='http://drewryrcd.com/jquery/advance-multi-select/multiple-select.css' />

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
		function init() {
    	if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    	var $ = go.GraphObject.make;  //for conciseness in defining node templates
    	myDiagram =
      	$(go.Diagram, "myDiagramDiv",  //Diagram refers to its DIV HTML element by id
      		{"toolManager.hoverDelay": 300, initialContentAlignment: go.Spot.Center, "undoManager.isEnabled": false});
    	// when the document is modified, add a "*" to the title and enable the "Save" button
    	myDiagram.addDiagramListener("Modified", function(e) {
    		var button = document.getElementById("SaveButton");
    		if (button) button.disabled = !myDiagram.isModified;
    		var idx = document.title.indexOf("*");
    		if (myDiagram.isModified) {
    			if (idx < 0) document.title += "*";
    		} else {
    			if (idx >= 0) document.title = document.title.substr(0, idx);
    		}


    	});
    	
    	// To simplify this code we define a function for creating a context menu button:
    	function makeButton(text, action, visiblePredicate) {
    		return $("ContextMenuButton",
    			$(go.TextBlock, text),
    			{ click: action },
               // don't bother with binding GraphObject.visible if there's no predicate
               visiblePredicate ? new go.Binding("visible", "", visiblePredicate).ofObject() : {});
    	}
    	var nodeMenu =  // context menu for each Node
    	$(go.Adornment, "Vertical",
    		makeButton("Delete",
    			function(e, obj) { 
				e.diagram.commandHandler.deleteSelection();
				console.log(obj);
			}),
    		$(go.Shape, "LineH", { strokeWidth: 2, height: 1, stretch: go.GraphObject.Horizontal }),
    		makeButton("Add Top Port",
    			function (e, obj) { addPort("top"); }),
    		makeButton("Add Left Port",
    			function (e, obj) { addPort("left"); }),
    		makeButton("Add Right Port",
    			function (e, obj) { addPort("right"); }),
    		makeButton("Add Bottom Port",
    			function (e, obj) { addPort("bottom"); })
    		);
	    var portSize = new go.Size(8, 8); // PORT SIZE ABHILASH
	    var portMenu =  // context menu for each port
	    $(go.Adornment, "Vertical",
	    	makeButton("Remove port",
	                   // in the click event handler, the obj.part is the Adornment;
	                   // its adornedObject is the port
	                   function (e, obj) { removePort(obj.part.adornedObject); })
	    	
	    	);

	    // includes a panel on each side with an itemArray of panels containing ports
	     // get tooltip text from the object's data enter
	    // get tooltip text from the object's data enter
	    function tooltipTextConverter(info) {
	    	var str = "";
	    	str += "Create Date: " + info.createDate;
	    	str += "\n \n Program Elements: " + info.programElements;
	    	return str;
	    }
    // define tooltips for nodes
    var tooltiptemplate =
    $(go.Adornment, "Auto",
    	$(go.Shape, "Rectangle",
    		{ fill: "whitesmoke", stroke: "#CCCCCC" }),
    	$(go.TextBlock,
    		{ font: "bold 10pt Helvetica, bold Arial, sans-serif",
    		wrap: go.TextBlock.WrapFit,
    		margin: 10, stroke: "#4e5560" },
    		new go.Binding("text", "", tooltipTextConverter))
    	);
	    // Node Links Styling 
	    myDiagram.nodeTemplate =
	    $(go.Node, "Table",
	    { 
    	toolTip: tooltiptemplate, //enter
    	locationObjectName: "BODY",
    	locationSpot: go.Spot.Center,
    	selectionObjectName: "BODY",
    	contextMenu: nodeMenu
    },
    new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
	        // the body
	        $(go.Panel, "Auto",
	        	{ row: 1, column: 1, name: "BODY",
	        	stretch: go.GraphObject.Fill },
	        	$(go.Shape, "Rectangle",
	        		{ strokeWidth: 0, stroke: null, minSize: new go.Size(170, 80) },
	        		new go.Binding("fill", "color")),
	        	$(go.TextBlock,
	        		{ margin: 10, wrap: go.TextBlock.WrapFit, textAlign: "center", font: "14px  Merriweather", stroke: "#000000", editable: false, isMultiline: true },
	        		new go.Binding("text", "name").makeTwoWay())
	        ),   // end Auto Panel body
	        // the Panel holding the left port elements, which are themselves Panels,
	        // created for each item in the itemArray, bound to data.leftArray
	        $(go.Panel, "Vertical",
	        	new go.Binding("itemArray", "leftArray"),
	        	{ row: 1, column: 0,
	        		itemTemplate:
	        		$(go.Panel,
	                { _side: "left",  // internal property to make it easier to tell which side it's on
	                fromSpot: go.Spot.Left, toSpot: go.Spot.Left,
	                fromLinkable: true, toLinkable: true, cursor: "pointer",
	                contextMenu: portMenu },
	                new go.Binding("portId", "portId"),
	                $(go.Shape, "Rectangle",
	                	{ stroke: null, strokeWidth: 0,
	                		desiredSize: portSize,
	                		margin: new go.Margin(1,0) },
	                		new go.Binding("fill", "#000000"))
	              )  // end itemTemplate
	        	}
	        ),  // end Vertical Panel
	        // the Panel holding the top port elements, which are themselves Panels,
	        // created for each item in the itemArray, bound to data.topArray
	        $(go.Panel, "Horizontal",
	        	new go.Binding("itemArray", "topArray"),
	        	{ row: 0, column: 1,
	        		itemTemplate:
	        		$(go.Panel,
	        			{ _side: "top",
	        			fromSpot: go.Spot.Top, toSpot: go.Spot.Top,
	        			fromLinkable: true, toLinkable: true, cursor: "pointer",
	        			contextMenu: portMenu },
	        			new go.Binding("portId", "portId"),
	        			$(go.Shape, "Rectangle",
	        				{ stroke: null, strokeWidth: 0,
	        					desiredSize: portSize,
	        					margin: new go.Margin(0, 1) },
	        					new go.Binding("fill", "#000000"))
	              )  // end itemTemplate
	        	}
	        ),  // end Horizontal Panel
	        // the Panel holding the right port elements, which are themselves Panels,
	        // created for each item in the itemArray, bound to data.rightArray
	        $(go.Panel, "Vertical",
	        	new go.Binding("itemArray", "rightArray"),
	        	{ row: 1, column: 2,
	        		itemTemplate:
	        		$(go.Panel,
	        			{ _side: "right",
	        			fromSpot: go.Spot.Right, toSpot: go.Spot.Right,
	        			fromLinkable: true, toLinkable: true, cursor: "pointer",
	        			contextMenu: portMenu },
	        			new go.Binding("portId", "portId"),
	        			$(go.Shape, "Rectangle",
	        				{ stroke: null, strokeWidth: 0,
	        					desiredSize: portSize,
	        					margin: new go.Margin(1, 0) },
	        					new go.Binding("fill", "#000000"))
	              )  // end itemTemplate
	        	}
	        ),  // end Vertical Panel
	        // the Panel holding the bottom port elements, which are themselves Panels,
	        // created for each item in the itemArray, bound to data.bottomArray
	        $(go.Panel, "Horizontal",
	        	new go.Binding("itemArray", "bottomArray"),
	        	{ row: 2, column: 1,
	        		itemTemplate:
	        		$(go.Panel,
	        			{ _side: "bottom",
	        			fromSpot: go.Spot.Bottom, toSpot: go.Spot.Bottom,
	        			fromLinkable: true, toLinkable: true, cursor: "pointer",
	        			contextMenu: portMenu },
	        			new go.Binding("portId", "portId"),
	        			$(go.Shape, "Rectangle",
	        				{ stroke: null, strokeWidth: 0,
	        					desiredSize: portSize,
	        					margin: new go.Margin(0, 1) },
	        					new go.Binding("fill", "#000000"))
	              )  // end itemTemplate
	        	}
	        )  // end Horizontal Panel
	      );  // end Node
		    // an orthogonal link template, reshapable and relinkable
		    myDiagram.linkTemplate =
		      $(CustomLink,  // defined below
		      {
		      	routing: go.Link.AvoidsNodes,
		      	corner: 4,
		      	curve: go.Link.JumpGap,
		      	reshapable: true,
		      	resegmentable: true,
		      	relinkableFrom: true,
		      	relinkableTo: true
		      },
		      new go.Binding("points").makeTwoWay(),
		      $(go.Shape, { stroke: "#2F4F4F", strokeWidth: 3 })
		      );
		    // support double-clicking in the background to add a copy of this data as a node
		    // myDiagram.toolManager.clickCreatingTool.archetypeNodeData = {
		    // 	name: "",
		    // 	leftArray: [],
		    // 	rightArray: [],
		    // 	topArray: [],
		    // 	bottomArray: []
		    // };
		    myDiagram.contextMenu =
		    $(go.Adornment, "Vertical",
		    	makeButton("Paste",
		    		function(e, obj) { e.diagram.commandHandler.pasteSelection(e.diagram.lastInput.documentPoint); },
		    		function(o) { return o.diagram.commandHandler.canPasteSelection(); }),
		    	makeButton("Undo",
		    		function(e, obj) { e.diagram.commandHandler.undo(); },
		    		function(o) { return o.diagram.commandHandler.canUndo(); }),
		    	makeButton("Redo",
		    		function(e, obj) { e.diagram.commandHandler.redo(); },
		    		function(o) { return o.diagram.commandHandler.canRedo(); })
		    	);
		    // load the diagram from JSON data
		    load();
			showpopup(myDiagram);

		}
			// This custom-routing Link class tries to separate parallel links from each other.
	  		// This assumes that ports are lined up in a row/column on a side of the node.
	  		function CustomLink() {
	  			go.Link.call(this);
	  		};
	  		go.Diagram.inherit(CustomLink, go.Link);
	  		CustomLink.prototype.findSidePortIndexAndCount = function(node, port) {
	  			var nodedata = node.data;
	  			if (nodedata !== null) {
	  				var portdata = port.data;
	  				var side = port._side;
	  				var arr = nodedata[side + "Array"];
	  				var len = arr.length;
	  				for (var i = 0; i < len; i++) {
	  					if (arr[i] === portdata) return [i, len];
	  				}
	  			}
	  			return [-1, len];
	  		};
	  		/** @override */
	  		CustomLink.prototype.computeEndSegmentLength = function(node, port, spot, from) {
	  			var esl = go.Link.prototype.computeEndSegmentLength.call(this, node, port, spot, from);
	  			var other = this.getOtherPort(port);
	  			if (port !== null && other !== null) {
	  				var thispt = port.getDocumentPoint(this.computeSpot(from));
	  				var otherpt = other.getDocumentPoint(this.computeSpot(!from));
	  				if (Math.abs(thispt.x - otherpt.x) > 20 || Math.abs(thispt.y - otherpt.y) > 20) {
	  					var info = this.findSidePortIndexAndCount(node, port);
	  					var idx = info[0];
	  					var count = info[1];
	  					if (port._side == "top" || port._side == "bottom") {
	  						if (otherpt.x < thispt.x) {
	  							return esl + 4 + idx * 8;
	  						} else {
	  							return esl + (count - idx - 1) * 8;
	  						}
	     	   } 	else {  // left or right
	     	   	if (otherpt.y < thispt.y) {
	     	   		return esl + 4 + idx * 8;
	     	   	} else {
	     	   		return esl + (count - idx - 1) * 8;
	     	   	}
	     	   }
	     	}
	     }
	     return esl;
	 };
	 /** @override */
	 CustomLink.prototype.hasCurviness = function() {
	 	if (isNaN(this.curviness)) return true;
	 	return go.Link.prototype.hasCurviness.call(this);
	 };
	 /** @override */
	 CustomLink.prototype.computeCurviness = function() {
	 	if (isNaN(this.curviness)) {
	 		var fromnode = this.fromNode;
	 		var fromport = this.fromPort;
	 		var fromspot = this.computeSpot(true);
	 		var frompt = fromport.getDocumentPoint(fromspot);
	 		var tonode = this.toNode;
	 		var toport = this.toPort;
	 		var tospot = this.computeSpot(false);
	 		var topt = toport.getDocumentPoint(tospot);
	 		if (Math.abs(frompt.x - topt.x) > 20 || Math.abs(frompt.y - topt.y) > 20) {
	 			if ((fromspot.equals(go.Spot.Left) || fromspot.equals(go.Spot.Right)) &&
	 				(tospot.equals(go.Spot.Left) || tospot.equals(go.Spot.Right))) {
	 				var fromseglen = this.computeEndSegmentLength(fromnode, fromport, fromspot, true);
	 			var toseglen = this.computeEndSegmentLength(tonode, toport, tospot, false);
	 			var c = (fromseglen - toseglen) / 2;
	 			if (frompt.x + fromseglen >= topt.x - toseglen) {
	 				if (frompt.y < topt.y) return c;
	 				if (frompt.y > topt.y) return -c;
	 			}
	 		} else if ((fromspot.equals(go.Spot.Top) || fromspot.equals(go.Spot.Bottom)) &&
	 			(tospot.equals(go.Spot.Top) || tospot.equals(go.Spot.Bottom))) {
	 			var fromseglen = this.computeEndSegmentLength(fromnode, fromport, fromspot, true);
	 			var toseglen = this.computeEndSegmentLength(tonode, toport, tospot, false);
	 			var c = (fromseglen - toseglen) / 2;
	 			if (frompt.x + fromseglen >= topt.x - toseglen) {
	 				if (frompt.y < topt.y) return c;
	 				if (frompt.y > topt.y) return -c;
	 			}
	 		}
	 	}
	 }
	 return go.Link.prototype.computeCurviness.call(this);
	};
	  	// end CustomLink class
	  	// Add a port to the specified side of the selected nodes.
	  	function addPort(side) {
	  		myDiagram.startTransaction("addPort");
	  		myDiagram.selection.each(function(node) {
	      	// skip any selected Links
	      	if (!(node instanceof go.Node)) return;
	      	// compute the next available index number for the side
	      	var i = 0;
	      	while (node.findPort(side + i.toString()) !== node) i++;
	      	// now this new port name is unique within the whole Node because of the side prefix
	      	var name = side + i.toString();
	      	// get the Array of port data to be modified
	      	var arr = node.data[side + "Array"];
	      	if (arr) {
	        // create a new port data object
	        var newportdata = {
	        	portId: name,
	        	portColor: go.Brush.randomColor()
	          	// if you add port data properties here, you should copy them in copyPortData above
	          };
	        // and add it to the Array of port data
	        myDiagram.model.insertArrayItem(arr, -1, newportdata);
	    }
	});
	  		myDiagram.commitTransaction("addPort");
	  	}
	  	// Remove the clicked port from the node.
	  	// Links to the port will be redrawn to the node's shape.
	  	function removePort(port) {
	  		myDiagram.startTransaction("removePort");
	  		var pid = port.portId;
	  		var arr = port.panel.itemArray;
	  		for (var i = 0; i < arr.length; i++) {
	  			if (arr[i].portId === pid) {
	  				myDiagram.model.removeArrayItem(arr, i);
	  				break;
	  			}
	  		}
	  		myDiagram.commitTransaction("removePort");
	  	}
	  	// Remove all ports from the same side of the node as the clicked port.
	  	function removeAll(port) {
	  		myDiagram.startTransaction("removePorts");
	  		var nodedata = port.part.data;
		    var side = port._side;  // there are four property names, all ending in "Array"
		    myDiagram.model.setDataProperty(nodedata, side + "Array", []);  // an empty Array
		    myDiagram.commitTransaction("removePorts");
		}


	  	// Save the model to / load it from JSON text shown on the page itself, not in a database.
	  	function save() {
	  		document.getElementById("mySavedModel").value = myDiagram.model.toJson();
	  		myDiagram.isModified = false;

	  	}
	  	function load() {
	  		myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
		    // When copying a node, we need to copy the data that the node is bound to.
		    // This JavaScript object includes properties for the node as a whole, and
		    // four properties that are Arrays holding data for each port.
		    // Those arrays and port data objects need to be copied too.
		    // Thus Model.copiesArrays and Model.copiesArrayObjects both need to be true.
		    // Link data includes the names of the to- and from- ports;
		    // so the GraphLinksModel needs to set these property names:
		    // linkFromPortIdProperty and linkToPortIdProperty.
		}
		
	
	 function showpopup(myDiagram){
		myDiagram.addDiagramListener("ObjectDoubleClicked", function(e) { 
			/*check whether obejct is block or a line*/
			var obj = myDiagram.selection.first();
			if(obj.constructor.name=="S"){ /*show popup*/
				document.getElementById('gohashid_text').innerHTML = obj.data.name;
				document.getElementById('gohashid').value = obj.data.__gohashid;
				document.getElementById("popup").style.display="block";
				var gohashid = obj.data.__gohashid; 
				get_association(gohashid);
			}
		});
	 }
	 
	 function get_association(gohashid){
		reset_popup();
		$('#gohashid').val(gohashid); 
		jQuery.ajax({
			type:'POST',
			url:'ajaxfiles/get_association.php',
			data:{gohashid:gohashid},
			success:function(data){
				var data = JSON.parse(data);
				if(data.length>0){ /*if association exists than fill the data else make popup blank*/
					$.each(data,function(index, assoc_obj){
						if(assoc_obj.association_type=="Budget"){
							$('#budget').val(assoc_obj.association_value);
						}
						
						if(assoc_obj.association_type=="Program Element"){
							$('#prgm_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+assoc_obj.association_id+'"]').trigger('click');
						}
						
						if(assoc_obj.association_type=="Standard Indicator"){
							$('#st_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+assoc_obj.association_id+'"]').trigger('click');
						}
						
						if(assoc_obj.association_type=="Custom Indicator"){
							$('#cs_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+assoc_obj.association_id+'"]').trigger('click');
						}
						
						if(assoc_obj.association_type=="Project"){
							$('#project_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+assoc_obj.association_id+'"]').trigger('click');
						}
						
						if(assoc_obj.association_type=="Activity"){
							$('#project_act_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+assoc_obj.association_id+'"]').trigger('click');
						}
					});
				}
				else{
					reset_popup();
				}
			}
		});	 
	 }
	</script>  
<!--
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
 -->
<script type="text/javascript">
function showProjectActivity(project_id)
{
	$.ajax({
		type: 'post',
		url: 'project_activity.php',
		data: {
			project_id:project_id
		},
		success: function (data) {
			$('#show_activity').html(data);
			//document.getElementById("new_select").innerHTML=response; 
		}
	});	
}

</script>
</head>
<body onLoad="init()">
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href="framework_management.php">Back</a>)</span></div>
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
				<!--	<li><button class="usa-button-outline" data-toggle="modal" data-target="#project"><i class="fa fa-plus" aria-hidden="true"></i> Add Project</button></li>
					<li><button class="usa-button-outline" data-toggle="modal" data-target="#activity"><i class="fa fa-plus" aria-hidden="true"></i> Add Project Activity</button></li> -->
				</ul>
			</div>
			<div class="col-md-3" style="margin:13px 0">
				<nav id="keys">
					<ul style="font-size:16px">
						<li><div style="background:#f2dcdb;"></div>Development Goal</li>
						<li><div style="background:#b8dfec;"></div>Development Objective</li>
						<li><div style="background:#c3d69b;"></div>Intermediate Result</li>
						<li><div style="background:#b3a2c7;"></div>Sub-Intermediate Result</li>
					<!--	<li><div style="background:#f44242;"></div>Project </li>
						<li><div style="background:#0801bf;"></div>Project Activity</li>  -->
					</ul>
				</nav>
			</div>
			<div class="col-md-12">
			<?php 
			if($fetch_res_frame['status']=='Active') { ?>
			<div class="text-right">
				<form method="post">
					<input type="hidden" name="archive_frame_id" value="<?php  echo $frame_id; ?>">
						<button name="move_archive" onClick="if(confirm('Are You Sure, You Want To Archive This Frame ?')){ return true;} else { return false; }" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;">Move to Archive</button>
				</form>
			</div>
			<?php } ?>
			<form id="form_assoc" method="post" action="" style="position:relative">
				<div id="myDiagramDiv" style="width:100%;height:500px;border:1px solid black;border-radius:10px;"></div>
				<!--popup-->
				<div id="popup">
					<!--popup close btn-->
					<div id ="popup_cover">
						<div>
							<span class="pull-right close_btn" style="cursor:pointer"><i class="fa fa-times text-danger"></i></span>
							<div class="clearfix"></div>
						</div>
						<div id="build_relation">
							<div id="poup_msg" style="padding:10px; font-size:16px;" class="disp-none bold"></div>
							<input type="hidden" value="" name="" />
							<table>
							<tr>
								<td class="lbl_td">Component</td>
								<td class="chk_td" colspan="3">
									<span id="gohashid_text"></span>
									<input type="hidden" name="gohashid" value="" id="gohashid"/>
								</td>
							</tr>
							<tr id="prgm_blk">
								<td class="lbl_td">Program Element</td>
								<td class="chk_td" colspan="3">
								<?php if(count($all_program_element_arr['data'])>0){?>
								<select multiple="multiple" name="program_element[]" id="prgm_dpw">
									<?php for($j=0; $j<count($all_program_element_arr['data']); $j++){?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						            <?php }?>
								</select>
								<?php }?>
								<div class="disp_text"></div>	
								</td>
							</tr>
							<tr id="st_indicator_blk">
								<td>Standard Indicator</td>
								<td colspan="3" class="chk_td">
								<?php if(count($st_indicator_arr['data'])>0){?>
								<select multiple="multiple" name="standar_indicator[]" id="st_dpw">
									<?php for($i=0;$i<count($st_indicator_arr['data']);$i++){?>
									<option value="<?php echo $st_indicator_arr['data'][$i]['id'];?>"><?php echo $st_indicator_arr['data'][$i]['indicator_title'];?> (<?php echo $st_indicator_arr['data'][$i]['indicator_id'];?>)</option>
									<?php }?>
								</select>
								<?php }?>
								<div class="disp_text"></div>	
								</td>
							</tr>
							<tr id="cs_indicator_blk">
								<td>Custom Indicator</td>
								<td colspan="3" class="chk_td">
								<?php if(count($cs_indicator_arr['data'])>0){?>
								<select multiple="multiple" name="custom_indicator[]" id="cs_dpw" >
									<?php for($i=0;$i<count($cs_indicator_arr['data']);$i++){?>
									<option value="<?php echo $cs_indicator_arr['data'][$i]['id'];?>"><?php echo $cs_indicator_arr['data'][$i]['name_indicator'];?></option>
									<?php }?>
								</select>
								<?php }?>
								<div class="disp_text"></div>	
								</td>
							</tr>
							<tr id="project_label_blk"> 
								<td>Project</td>
								<td colspan="3" class="chk_td">
								<?php $url = AMP_API_HOST_URL."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_arr['data']['operating_unit_id'];
								$project_arr = requestByCURL($url); 
								if(count($project_arr['data'])>0){
								?>
								<select multiple="multiple" name="project[]" id="project_dpw">
									<?php for($i=0;$i<count($project_arr['data']);$i++){?>
									<option value="<?php echo $project_arr['data'][$i]['project_id'];?>"><?php echo $project_arr['data'][$i]['title'];?></option>
									<?php }?>
								</select>
								<?php }?>
								<div class="disp_text"></div>	
								</td>
							</tr>
							<tr id="project_act_label_blk">
								<td>Project Activity</td>
								<td colspan="3" class="chk_td">
								<?php $url = AMP_API_HOST_URL."get_all_project_by_ou_id.php?operating_unit_id=".$operating_unit_arr['data']['operating_unit_id'];
								$project_arr = requestByCURL($url); 
								if(count($project_arr['data'])>0){
								?>
								<select multiple="multiple" name="project_activity[]" id="project_act_dpw">
								</select>
								<?php }?>
								<div class="disp_text"></div>		
								</td>
							</tr>
							<tr id="assoc_budget_blk">
								<td>Budget</td>
								<td colspan="3" class="chk_td">
								<input type="text" value="" placeholder="Budget" id="budget" name="budget"/>
								</td>
							</tr>
							<tr>
								<td colspan="4" class="text-center"><button type="button" class="usa-button-outline" id="cancel_assoc">Cancel</button> <button type="button" id="save_assoc">Save</button></td>
							</tr>
						</table>
						  </div>
					</div>
				</div>
				<div  style="clear:both" class="text-center"></div>
				<textarea id="mySavedModel" name="link_data"  style="width:100%;height:500px; display: none1;">					
				{ 
				"class": "go.GraphLinksModel",
				"copiesArrays": true,
				"copiesArrayObjects": true,
				"linkFromPortIdProperty": "fromPort",
				"linkToPortIdProperty": "toPort",
				"nodeDataArray": [
					<?php for($i=1; $i<=count($arr); $i++){ 
						$html = '{"key":"-'.$arr[$i]['key'].'", "__gohashid":"'.$arr[$i]['gohashid'].'", "createDate":"'.$arr[$i]['approval_date'].'", ';
						
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
				<input type="submit" name="node_link" value="Save" onClick="save()" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;">
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
							<select name="project_id" id="options" onChange="showProjectActivity(this.value);">
								 <option value="">Select</option>
								<?php
								for($j=0; $j<count($all_project['data']); $j++){ ?>	
									<option value="<?php echo $all_project['data'][$j]['project_id']?>" ><?php echo $all_project['data'][$j]['title'];?></option>
								<?php } ?>
							</select>
						</div>
						<div id="show_activity">
						</div>

						<br>
						<input type="submit" name="Activity" value="Save" style="margin-top:10px; margin-bottom: 0;" />
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
							<select multiple="multiple" name="program_element_id[]" class="SlectBox">							
								<?php for($j=0; $j<count($all_program_element_arr['data']); $j++){?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						        <?php }?>
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
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
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
							<select multiple="multiple" name="program_element_id[]" class="SlectBox" >
								<?php for($j=0; $j<count($all_program_element_arr['data']); $j++){?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						        <?php }?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="input-type-textarea">Associated Standard Indicators</label>
							<select multiple="multiple" name="standard_indicator_id[]" class="stin">
								<?php for($i=0;$i<count($st_indicator_arr['data']);$i++){?>
									<option value="<?php echo $st_indicator_arr['data'][$i]['id'];?>"><?php echo $st_indicator_arr['data'][$i]['indicator_title'];?> (<?php echo $st_indicator_arr['data'][$i]['indicator_id'];?>)</option>
								<?php }?>		
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Associated Custom Indicators</label>
							<select multiple="multiple" name="custom_indicator_id" class="cuin">
								<?php for($i=0;$i<count($cs_indicator_arr['data']);$i++){?>
									<option value="<?php echo $cs_indicator_arr['data'][$i]['id'];?>"><?php echo $cs_indicator_arr['data'][$i]['name_indicator'];?></option>
								<?php }?>
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
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="month" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="day" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="year" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
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
							<select multiple="multiple" name="program_element_id[]" class="SlectBox" >
								<?php for($j=0; $j<count($all_program_element_arr['data']); $j++){?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						        <?php }?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="input-type-textarea">Associated Standard Indicators</label>
							<select multiple="multiple" name="standard_indicator_id[]" class="stin">
								<?php for($i=0;$i<count($st_indicator_arr['data']);$i++){?>
									<option value="<?php echo $st_indicator_arr['data'][$i]['id'];?>"><?php echo $st_indicator_arr['data'][$i]['indicator_title'];?> (<?php echo $st_indicator_arr['data'][$i]['indicator_id'];?>)</option>
								<?php }?>	
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Associated Custom Indicators</label>
							<select multiple="multiple" name="custom_indicator_id" class="cuin">
								<?php for($i=0;$i<count($cs_indicator_arr['data']);$i++){?>
									<option value="<?php echo $cs_indicator_arr['data'][$i]['id'];?>"><?php echo $cs_indicator_arr['data'][$i]['name_indicator'];?></option>
								<?php }?>
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
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint"  id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
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
							<select multiple="multiple" name="program_element_id[]" class="SlectBox">
								<?php for($j=0; $j<count($all_program_element_arr['data']); $j++){?>	
									<option value="<?php echo $all_program_element_arr['data'][$j]['id']?>" ><?php echo $all_program_element_arr['data'][$j]['program_element_code'].' ('.$all_program_element_arr['data'][$j]['program_element_name'].')';?></option>
						        <?php }?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="input-type-textarea">Associated Standard Indicators</label>
							<select multiple="multiple" name="standard_indicator_id[]" class="stin">
								<?php for($i=0;$i<count($st_indicator_arr['data']);$i++){?>
									<option value="<?php echo $st_indicator_arr['data'][$i]['id'];?>"><?php echo $st_indicator_arr['data'][$i]['indicator_title'];?> (<?php echo $st_indicator_arr['data'][$i]['indicator_id'];?>)</option>
								<?php }?>	
							</select>
						</div>

						<div class="form-group">
							<label for="input-type-textarea">Associated Custom Indicators</label>
							<select multiple="multiple" name="custom_indicator_id" class="cuin">
								<?php for($i=0;$i<count($cs_indicator_arr['data']);$i++){?>
									<option value="<?php echo $cs_indicator_arr['data'][$i]['id'];?>"><?php echo $cs_indicator_arr['data'][$i]['name_indicator'];?></option>
								<?php }?>
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
									<input class="usa-input-inline usa-form-control"  aria-describedby="dobHint" id="date_of_birth_1" name="month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								</div>
								<div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline usa-form-control"  aria-describedby="dobHint"  id="date_of_birth_2" name="day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								</div>
								<div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline usa-form-control" aria-describedby="dobHint" id="date_of_birth_3" name="year" pattern="[0-9]{4}" type="number" min="1900" max="2050" value="">
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
</br>
<!-- Include all compiled plugins (below), or include individual files as needed -->

<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script src="js/uswds.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script src="js/jquery.sumoselect.min.js"></script>
<script type="text/javascript" src="http://drewryrcd.com/jquery/advance-multi-select/jquery.multiple.select.js"></script>
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
		
		$('#project_dpw').multipleSelect({
			columns: 1,
			placeholder: 'Select Project',
			search: false
		});
		
		$('#project_act_dpw').multipleSelect({
			columns: 1,
			placeholder: 'Select Project Activity',
			search: false
		});
		
		$('#prgm_dpw').multipleSelect({
			columns: 1,
			placeholder: 'Select Program Indicator',
			search: false
		});
		
		$('#st_dpw').multipleSelect({
			columns: 1,
			placeholder: 'Select Standard Indicator',
			search: false
		});
		
		$('#cs_dpw').multipleSelect({
			columns: 1,
			placeholder: 'Select Custom Indicator',
			search: false
		});
	});
	
	/*function to reset popup*/
	function reset_popup(){
		$('.ms-parent input[type="checkbox"]').prop("checked",false);
		$('#budget,#gohashid').val("");
		$('#prgm_dpw').next('.ms-parent').find("button").html("<span class='placeholder'>Select Program Element</span><div></div>");
		$('#st_dpw').next('.ms-parent').find("button").html("<span class='placeholder'>Select Standard Indicator</span><div></div>");
		$('#cs_dpw').next('.ms-parent').find("button").html("<span class='placeholder'>Select Custom Indicator</span><div></div>");
		$('#project_dpw').next('.ms-parent').find("button").html("<span class='placeholder'>Select Project</span><div></div>");
		$('.disp_text,#project_act_dpw,#poup_msg').html("");
		$('#project_act_dpw').multipleSelect();
	}
	/*close popup*/
	$('#popup .close_btn').click(function(){	
		reset_popup();
		$('#popup').css('display','none');	
	});
	
	/*fill project activites*/
	$(document).ready(function () {
		/*program element click*/
		$(document).on('click','#prgm_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#prgm_blk').find('.disp_text').html("");
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				$(elem).closest('#prgm_blk').find('.disp_text').append('<span>'+val+'</span>');
			});
		});
		
		/*standard indicator click*/
		$(document).on('click','#st_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#st_indicator_blk').find('.disp_text').html("");
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				$(elem).closest('#st_indicator_blk').find('.disp_text').append('<span>'+val+'</span>');
			});
		});
		
		/*custom indicator click*/
		$(document).on('click','#cs_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#cs_indicator_blk').find('.disp_text').html("");
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				$(elem).closest('#cs_indicator_blk').find('.disp_text').append('<span>'+val+'</span>');
			});	
		});
		
		/*project click*/
		
		$(document).on('click','#project_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#project_label_blk').find('.disp_text').html("");
			$('#project_act_dpw').html("");
			$('#project_act_dpw').multipleSelect();
			var sel_html= "";
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				$(elem).closest('#project_label_blk').find('.disp_text').append('<span>'+val+'</span>');
				var val = $(elem).val();
				var url ='<?php echo AMP_API_HOST_URL.'get_all_project_activity.php?project_id=';?>'+val;
				if(val!=""){
					$.ajax({
						url:url,
						dataType:'json',
						async: false,
						success:function(data){
							//$('#project_act_dpw').html("");
							var act_arr = data.data;
							$.each(act_arr, function(index, obj){
								sel_html=sel_html+"<option value='"+obj.activity_id+"'>"+obj.title+"</option>";
							});
							$('#project_act_dpw').html(sel_html);
							$('#project_act_dpw').multipleSelect();
						}
					});
				}
			});
		});
		
		/*project activity click*/
		$(document).on('click','#project_act_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#project_act_label_blk').find('.disp_text').html("");
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				$(elem).closest('#project_act_label_blk').find('.disp_text').append('<span>'+val+'</span>');
			});	
		});
		
		$('#cancel_assoc').click(function(){
			reset_popup();
		});
   });
   
   /*save association*/
   $('#save_assoc').click(function(){
   		 var gohashid = $('#gohashid').val();
		 var prgm_elem = $('#prgm_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var st_elem = $('#st_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var cs_elem = $('#cs_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var prj_elem = $('#project_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var prj_act_elem = $('#project_act_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var budget = $('#budget').val();
		 /*atleast one element should be selected*/
		 if(prgm_elem.length>0 || st_elem.length>0 || cs_elem.length>0 || prj_elem.length>0 || prj_act_elem.length>0 || budget!=""){
		 	
			var prgm_elem_arr = [];
			var st_elem_arr = [];
			var cs_elem_arr = [];
			var prj_elem_arr = [];
			var prj_act_elem_arr = [];
			
			/*loop in checked checkbox in program element*/
			$(prgm_elem).each(function(index, elem){
				prgm_elem_arr.push($(elem).val());
			});
				
			/*loop in checked checkbox in standard indicator*/
			$(st_elem).each(function(index, elem){
				st_elem_arr.push($(elem).val());
			});
				
			/*loop in checked checkbox in custom indicator*/
			$(cs_elem).each(function(index, elem){
				cs_elem_arr.push($(elem).val());
			});
			
			/*loop in checked checkbox in custom indicator*/
			$(prj_elem).each(function(index, elem){
				prj_elem_arr.push($(elem).val());
			});
			
			/*loop in checked checkbox in custom indicator*/
			$(prj_act_elem).each(function(index, elem){
				prj_act_elem_arr.push($(elem).val());
			});
		
			$.ajax({
				type:'POST',
				url:'ajaxfiles/manage_association.php',
				data:{gohashid:gohashid,prgm_elem:prgm_elem_arr,st_indicator:st_elem_arr,cs_indicator:cs_elem_arr,projects:prj_elem_arr,activities:prj_act_elem_arr,budget:$('#budget').val()},
				success:function(data){
					$('#poup_msg').removeClass('text-danger').removeClass('text-success');
					var data = JSON.parse(data);
					console.log(data);
					if(data['msg_type']=="error"){
						$('#poup_msg').addClass('text-danger');
					}
					else{
						$('#poup_msg').addClass('text-success');
						
					}
					$('#poup_msg').text(data['msg']);
					$('#poup_msg').removeClass("disp-none");
					setTimeout(function(){
						$('#poup_msg').removeClass('text-danger').removeClass('text-success');
						$('#poup_msg').text("");
						$('#poup_msg').addClass("disp-none");
						$('#popup').css('display','none');	
					},10000);
							
				}
			});
		 }
   });
  
</script>
</body>
</html>
