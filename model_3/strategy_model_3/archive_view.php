<?php
include('config/config.inc.php');
include('include/function.inc.php'); 

## function for DOAG box description replace double quote  ============
function removeDoubleQuote($description)
{
	global $mysqli;
	$description = $mysqli->real_escape_string(str_replace('"', '', $description)); 
	return $description;
}

//## get Detail operating unit ===========
$operating_unit_id = $_SESSION['operating_unit_id'];
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id'];
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
else
{
	header('location:index.php');
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

## fetch frame name and from usaid_archive_frame & usaid_frame table=============	
	if(isset($_REQUEST['archive_frame_id']))
	{	
		 $archive_frame_id = trim($_REQUEST['archive_frame_id']); 
		
		if($archive_frame_id!='')
		{		
				$select_archive_frame_data = "select af.*, f.frame_name from usaid_archive_frame as af
					left join usaid_frame as f ON f.id=af.frame_id
					where af.id='".$archive_frame_id."'";
				$result_archive_data = $mysqli->query($select_archive_frame_data);
				$fetch_res_archive_frame = $result_archive_data->fetch_array();
				
				$_SESSION['frame_name']=$fetch_res_archive_frame['frame_name'];
				$_SESSION['archive_frame_id']= $fetch_res_archive_frame['id'];
		}
	}
		$archive_frame_id=$_SESSION['archive_frame_id'];
		$frame_name = $_SESSION['frame_name'];
		
## fetch development_goal==============
	$i=1;
	$select_goal="select * from usaid_archive_development_goal where archive_id='$archive_frame_id'";
	$total_res = $mysqli->query($select_goal);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="GOAL-".$fetch_data['development_goal_id'];
		$arr[$i]['gohashid']=$fetch_data['gohashid'];
		$arr[$i]['name']=preg_replace('/\\\\+/','',stripslashes($fetch_data['goal_description']));
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['goal_approval_date'];
		$arr[$i]['color']="#f2dcdb";
		$id=$fetch_data['development_goal_id'];
		
		## show program element on moush hover==============
		$select_program_element="select * from usaid_archive_association where gohashid='".$arr[$i]['gohashid']."' and archive_id='".$archive_frame_id."' and association_type='Program Element'";
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
		$select_dat_link="select from_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND ( `from_id` = '".$id."' AND `from_type` = '".$type."')";
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
		
		$select_dat_link="select to_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND ( `to_id` = '".$id."' AND `to_type` = '".$type."')";
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
	$select_objective="select * from usaid_archive_development_objective where archive_id='$archive_frame_id'";
	$total_res = $mysqli->query($select_objective);
	
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="DO-".$fetch_data['development_objective_id'];
		$arr[$i]['gohashid']=$fetch_data['gohashid'];
		$arr[$i]['name']=preg_replace('/\\\\+/','',stripslashes($fetch_data['objective_description']));
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['objective_approval_date'];
		$arr[$i]['color']="#b8dfec";
		
		$id=$fetch_data['development_objective_id'];
		
		## show program element on moush hover==============
		$select_program_element="select * from usaid_archive_association where gohashid='".$arr[$i]['gohashid']."' and archive_id='".$archive_frame_id."' and association_type='Program Element'";
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
		$select_dat_link="select from_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND ( `from_id` = '".$id."' AND `from_type` = '".$type."')";
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
		
		$select_dat_link="select to_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND (`to_id` = '".$id."' AND `to_type` = '".$type."')";
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
	$select_IR="select * from usaid_archive_intermediate_result where archive_id='$archive_frame_id'";
	$total_res = $mysqli->query($select_IR);
	while($fetch_data = $total_res->fetch_array())
	{
		$arr[$i]['key']="IR-".$fetch_data['intermediate_result_id'];
		$arr[$i]['gohashid']=$fetch_data['gohashid'];
		$arr[$i]['name']=preg_replace('/\\\\+/','',stripslashes($fetch_data['ir_description']));
		$arr[$i]['location']=$fetch_data['location'];
		$arr[$i]['approval_date']=$fetch_data['ir_approval_date'];
		$arr[$i]['color']="#c3d69b";
		$id=$fetch_data['intermediate_result_id'];
		## show program element on moush hover==============
		$select_program_element="select * from usaid_archive_association where gohashid='".$arr[$i]['gohashid']."' and archive_id='".$archive_frame_id."' and association_type='Program Element'";
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
		$select_dat_link="select from_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND (`from_id` = '".$id."' AND `from_type` = '".$type."')";
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
		
		$select_dat_link="select to_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND (  `to_id` = '".$id."' AND `to_type` = '".$type."')";
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
	$select_Sub_IR="select * from usaid_archive_sub_intermediate_result where archive_id='$archive_frame_id'";
	$total_res = $mysqli->query($select_Sub_IR);
	while($fetch_data = $total_res->fetch_array())
	{
	$arr[$i]['key']="SUBIR-".$fetch_data['sub_intermediate_result_id'];
	$arr[$i]['gohashid']=$fetch_data['gohashid'];
	$arr[$i]['name']=preg_replace('/\\\\+/','',stripslashes($fetch_data['sub_ir_description']));
	$arr[$i]['location']=$fetch_data['location'];
	$arr[$i]['approval_date']=$fetch_data['sub_ir_approval_date'];
	$arr[$i]['color']="#b3a2c7";
		$id=$fetch_data['sub_intermediate_result_id'];
		## show program element on moush hover==============
		$select_program_element="select * from usaid_archive_association where gohashid='".$arr[$i]['gohashid']."' and archive_id='".$archive_frame_id."' and association_type='Program Element'";
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
		$select_dat_link="select from_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND ( `from_id` = '".$id."' AND `from_type` = '".$type."')";
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
		
		$select_dat_link="select to_port from usaid_archive_data_relation where archive_id='$archive_frame_id' AND (  `to_id` = '".$id."' AND `to_type` = '".$type."')";
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
	$select_dat_link="select * from usaid_archive_data_relation where archive_id='$archive_frame_id'";
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
		.disp-none{display:none;}
	</style>
	
	<script>
		function init() {
    	if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    	var $ = go.GraphObject.make;  //for conciseness in defining node templates
    	myDiagram =
      	$(go.Diagram, "myDiagramDiv",  //Diagram refers to its DIV HTML element by id
      		{"toolManager.hoverDelay": 300, initialContentAlignment: go.Spot.Center, "undoManager.isEnabled": false, allowCopy : false});
    	
    	
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
//    		makeButton("Delete",
//    			function(e, obj) { 
//				e.diagram.commandHandler.deleteSelection();
//				console.log(obj);
//			}),
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
    		wrap: go.TextBlock.WrapDesiredSize,
    		margin: 10, stroke: "#4e5560", 
			maxSize: new go.Size(450, 300)
			},
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
	        		{ strokeWidth: 0, stroke: null /*, minSize: new go.Size(170, 80)*/,minSize: new go.Size(200, 100), maxSize: new go.Size(200, 400)},
	        		new go.Binding("fill", "color")),
	        	$(go.TextBlock,
	        		{ margin: 10, wrap: go.TextBlock.WrapDesiredSize, textAlign: "center", width: 130, font: "14px  Merriweather", stroke: "#000000", editable: false, overflow: go.TextBlock.OverflowEllipsis},
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
	                		margin: new go.Margin(6,0) },
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
	        					margin: new go.Margin(0, 6) },
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
	        					margin: new go.Margin(6, 0) },
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
	        					margin: new go.Margin(0, 6) },
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
		      $(go.Shape, { stroke: "#2F4F4F", strokeWidth: 1 })
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
	  	function addPort(side) { /*
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
	  		myDiagram.commitTransaction("addPort");  */
	  	}
	  	// Remove the clicked port from the node.
	  	// Links to the port will be redrawn to the node's shape.
	  	function removePort(port) {  /*
	  		myDiagram.startTransaction("removePort");
	  		var pid = port.portId;
	  		var arr = port.panel.itemArray;
	  		for (var i = 0; i < arr.length; i++) {
	  			if (arr[i].portId === pid) {
	  				myDiagram.model.removeArrayItem(arr, i);
	  				break;
	  			}
	  		}
	  		myDiagram.commitTransaction("removePort");  */
	  	}
	  	// Remove all ports from the same side of the node as the clicked port.
	  	function removeAll(port) { /*
	  		myDiagram.startTransaction("removePorts");
	  		var nodedata = port.part.data;
		    var side = port._side;  // there are four property names, all ending in "Array"
		    myDiagram.model.setDataProperty(nodedata, side + "Array", []);  // an empty Array
		    myDiagram.commitTransaction("removePorts"); */
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
							$('.assoc_with_prj').trigger('click');
							$('#project_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+assoc_obj.association_id+'"]').trigger('click');
						}
						
						if(assoc_obj.association_type=="Activity"){
							$('.assoc_with_act').trigger('click');
							var assoc_temp_arr = assoc_obj.association_id.split('-');
							var project_id = assoc_temp_arr[0];
							$('#project_dpw').next('.ms-parent').find('input[type="checkbox"][value="'+project_id+'"]').trigger('click');
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
<body onLoad="init()"; oncontextmenu="return false">
	<!-- Header Include Here -->
	<?php // include 'include/header.php'; ?>
	
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			 
		</div>
	</div>
	<!-- Adding Adding Frame -->
	<div class="add-frame">
		<div class="container-fluid">
			<div class="col-md-12" style="text-align: center;">
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
							<tr>
								<td>Associated With</td>
								<td>
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<input type="radio" value="Project" class="assoc_with assoc_with_prj" name="assoc_with" checked="checked"/><label>Project</label>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<input type="radio" value="Activity" class="assoc_with assoc_with_act" name="assoc_with"/><label>Activity</label>
										</div>
									</div>
								</td>
							</tr>
							<tr id="project_label_blk"> 
								<td>Project</td>
								<td colspan="3" class="chk_td">
								<?php $url = AMP_API_HOST_URL."get_all_project_by_implementing_ou_id.php?operating_unit_id=".$operating_unit_arr['data']['operating_unit_id'];
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
							<tr id="project_act_label_blk" class="disp-none">
								<td>Project Activity</td>
								<td colspan="3" class="chk_td">
								<?php 
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
								<td colspan="4" class="text-center">
							<!--	<button type="button" class="usa-button-outline" id="cancel_assoc">Cancel</button> <button type="button" id="save_assoc">Save</button> -->
								</td>
							</tr>
						</table>
						  </div>
					</div>
				</div>
				<div  style="clear:both" class="text-center"></div>
				<textarea id="mySavedModel" name="link_data"  style="width:100%;height:500px; display: none;">					
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
				<!--<button class="usa-button-outline" onClick="load()">Cancel</button>
				<input type="submit" name="node_link" value="Save" onClick="save()" style="display: inline-block; padding-left: 1.2em; padding-right: 1.2em;height: 3.7rem;"> -->
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
		$('.assoc_with_prj').trigger('click');
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
				var id = $(elem).closest('li').find('label input[type="checkbox"]').val();
				var temp_cls = "unchk-"+id;
				$(elem).closest('#prgm_blk').find('.disp_text').append('<span class="'+temp_cls+'">'+val+' <i class="fa fa-times unchk"></i></span>');
			});
		});
		
		/*standard indicator click*/
		$(document).on('click','#st_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#st_indicator_blk').find('.disp_text').html("");
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				var id = $(elem).closest('li').find('label input[type="checkbox"]').val();
				var temp_cls = "unchk-"+id;
				$(elem).closest('#st_indicator_blk').find('.disp_text').append('<span class="'+temp_cls+'">'+val+' <i class="fa fa-times unchk"></i></span>');
			});
		});
		
		/*custom indicator click*/
		$(document).on('click','#cs_dpw + .ms-parent input[type="checkbox"]', function(){
			var chk_checked = $(this).closest('.ms-parent').find('input[type="checkbox"]:checked');
			$(this).closest('#cs_indicator_blk').find('.disp_text').html("");
			$(chk_checked).each(function(index, elem){
				var val = $(elem).closest('li').find('label').text();
				var id = $(elem).closest('li').find('label input[type="checkbox"]').val();
				var temp_cls = "unchk-"+id;
				$(elem).closest('#cs_indicator_blk').find('.disp_text').append('<span class="'+temp_cls+'">'+val+' <i class="fa fa-times unchk"></i></span>');
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
				var id = $(elem).closest('li').find('label input[type="checkbox"]').val();
				var temp_cls = "unchk-"+id;
				$(elem).closest('#project_label_blk').find('.disp_text').append('<span class="'+temp_cls+'">'+val+' <i class="fa fa-times unchk"></i></span>');
				$('#project_act_label_blk').find('.disp_text').html("");
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
				var id = $(elem).closest('li').find('label input[type="checkbox"]').val();
				var temp_cls = "unchk-"+id;
				$(elem).closest('#project_act_label_blk').find('.disp_text').append('<span class="'+temp_cls+'">'+val+' <i class="fa fa-times unchk"></i></span>');
			});	
		});
		
		$('#cancel_assoc').click(function(){
			reset_popup();
		});
   });
   
   /*uncheck checkbox*/
   $(document).on('click',".unchk" ,function(){
		var elem = $(this).closest('span');
		var id = $(this).closest('span').attr('class');
		id = id.replace("unchk-","");
		$(elem).closest('.disp_text').prev('.ms-parent').find('.ms-drop ul input[type="checkbox"]').filter(function(){
			return this.value == id;
		}).prop("checked", false);
		var par = $(this).closest('.chk_td').closest('tr').attr('id');
		if(par=="prgm_blk"){
			var sel_value = $('#prgm_dpw').multipleSelect('getSelects');
			$('#prgm_dpw').multipleSelect('setSelects', sel_value);	
		}
		
		if(par=="st_indicator_blk"){
			var sel_value = $('#st_dpw').multipleSelect('getSelects');
			$('#st_dpw').multipleSelect('setSelects', sel_value);	
		}
		
		if(par=="cs_indicator_blk"){
			var sel_value = $('#cs_dpw').multipleSelect('getSelects');
			$('#cs_dpw').multipleSelect('setSelects', sel_value);	
		}
		
		if(par=="project_label_blk"){
			var sel_value = $('#project_dpw').multipleSelect('getSelects');
			$('#project_dpw').multipleSelect('setSelects', sel_value);
			$('#project_act_dpw').multipleSelect('setSelects', []);	
			$('#project_act_label_blk').find('.disp_text').html("");	
		}
		
		if(par=="project_act_label_blk"){
			var sel_value = $('#project_act_dpw').multipleSelect('getSelects');
			$('#project_act_dpw').multipleSelect('setSelects', sel_value);	
		}
		$(elem).remove();
   });
   
   /*save association*/
   $('#save_assoc').click(function(){
   		 var gohashid = $('#gohashid').val();
		 var prgm_elem = $('#prgm_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var st_elem = $('#st_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var cs_elem = $('#cs_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var prj_elem = $('#project_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var prj_act_elem = $('#project_act_dpw').next('.ms-parent').find('input[type="checkbox"]:checked');
		 var prj_assoc = $('.assoc_with:checked').val();
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
				data:{gohashid:gohashid,prgm_elem:prgm_elem_arr,st_indicator:st_elem_arr,cs_indicator:cs_elem_arr,projects:prj_elem_arr,activities:prj_act_elem_arr,budget:$('#budget').val(),association:prj_assoc},
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
 	
	/*project assoc*/
	$('.assoc_with').click(function(){
		var assoc_with = $(this).val();
		if(assoc_with=="Project"){
			$('#project_act_label_blk').addClass('disp-none');
			$('#project_act_dpw').multipleSelect('setSelects', []);
			$('#project_act_label_blk').find('.disp_text').html("");	
		}
		else if(assoc_with=="Activity"){
			$('#project_act_label_blk').removeClass('disp-none');
		}
	}); 
</script>
</body>
</html>
