<?php
include('config/functions.inc.php');

##==validate user====
validate_user();
###request for get single project details using project id ===========
$project_id = '';
if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];
	##==get project_info
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

	##==get project_stage_info
    $url = API_HOST_URL_PROJECT."api_demo.php?stage"; 
  	$project_stage_arr = requestByCURL($url);	
}

//## get Detail operating unit ===========
$operating_unit_id = $project_arr['data']['implementing_operating_unit_id'];


//## get all project activity
$url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
$project_activity_arr = requestByCURL($url);


$data = array();
if(isset($_REQUEST['manage_strategy'])){
	
	##=manage project association
	if(isset($_REQUEST['associate_with']) && $_REQUEST['associate_with']=="Project"){
		$action = "action=Ins";
			
		$url = API_HOST_URL_STRATEGY."manage_asscociation_by_project.php?gohashid=".$_REQUEST['gohashid']."&elem_id=".$_REQUEST['project_id']."&association_type=Project&".$action; 
		$project_assoc = requestByCURL($url);	
		
		if($project_assoc['data']['msg_type']=="Error"){
			$data['msg'] = "Something went wrong";
			$data['msg_type'] = "Error";
			echo json_encode($data);
			exit;
		}
	
	}
	else {
		if(isset($_REQUEST['project_act_assoc']) &&  count($_REQUEST['project_act_assoc'])>0){
			$project_act=implode(',',$_REQUEST['project_act_assoc']);
			
			##=manage project activity association
			$url = API_HOST_URL_STRATEGY."manage_asscociation_by_project.php?gohashid=".$_REQUEST['gohashid']."&elems=".$project_act."&association_type=Activity"; 
			$project_assoc = requestByCURL($url);	
			
			if($project_assoc['data']['msg_type']=="Error"){
				$data['msg'] = "Something went wrong";
				$data['msg_type'] = "Error";
				echo json_encode($data);
				exit;
			}
		}
	}
}

//## get All program element ===========
$url = API_HOST_URL_PROJECT."get_all_program_element.php";
$all_program_element_arr = requestByCURL($url); 

//## get All standard indicator
$url = API_HOST_URL_STRATEGY."get_all_standard_indicator.php";
$st_indicator_arr = requestByCURL($url); 

//## get All custom indicator
$url = API_HOST_URL_STRATEGY."get_all_custom_indicator_by_ou_id.php?ou_id=".$operating_unit_id;
$cs_indicator_arr = requestByCURL($url);

$url = API_HOST_URL_PROJECT."get_all_project.php";
$all_project = requestByCURL($url);

//## get All custom indicator
$url = API_HOST_URL_STRATEGY."get_active_framework.php?ou_id=".$operating_unit_id;
$framework_arr = requestByCURL($url);
$arr = $framework_arr['data']['blocks']; 
$data_link = $framework_arr['data']['links'];


$all_act_relative_ir_subir = $act_subir_arr = $act_ir_arr = array();
//## get All linked subIR
$url = API_HOST_URL_STRATEGY."get_active_frame_association_by_association_type.php?association_id=".$project_id."&association_type=Project&elem_type=subir";
$subir_arr = requestByCURL($url);

//## get All linked IR
$url = API_HOST_URL_STRATEGY."get_active_frame_association_by_association_type.php?association_id=".$project_id."&association_type=Project&elem_type=ir";
$ir_arr = requestByCURL($url);



//## loop in array to get association of activity
for($i=0; $i<count($project_activity_arr['data']); $i++){
	$activity_id = $project_activity_arr['data'][$i]['activity_id'];
	
	$url = API_HOST_URL_STRATEGY."get_active_frame_association_by_association_type.php?association_id=".$activity_id."&association_type=Activity&elem_type=subir";
	$act_subir_arr = requestByCURL($url);

	$url = API_HOST_URL_STRATEGY."get_active_frame_association_by_association_type.php?association_id=".$activity_id."&association_type=Activity&elem_type=ir";
	$act_ir_arr = requestByCURL($url);
	
	if(count($act_subir_arr['data'])>0 && count($act_ir_arr['data'])>0){
		//array_push($all_act_relative_ir_subir, array_merge($act_subir_arr['data'],$act_ir_arr['data']));
		$all_act_relative_ir_subir  = array_merge($all_act_relative_ir_subir, $act_subir_arr['data']);
		$all_act_relative_ir_subir =  array_merge($all_act_relative_ir_subir, $act_ir_arr['data']);
	}
	else if(count($act_subir_arr['data'])>0){
		$all_act_relative_ir_subir = array_merge($all_act_relative_ir_subir, $act_subir_arr['data']);
	}
	else if(count($act_ir_arr['data'])>0){
		$all_act_relative_ir_subir = array_merge($all_act_relative_ir_subir, $act_ir_arr['data']);
	}
} 

$all_act_relative_ir_subir = array_unique($all_act_relative_ir_subir);
$all_relative_ir_subir  = array();
if(count($subir_arr['data'])>0 && count($ir_arr['data'])>0){
	$all_relative_ir_subir = array_merge($subir_arr['data'],$ir_arr['data']);
}
else if(count($subir_arr['data'])>0){
	$all_relative_ir_subir = $subir_arr['data'];
}
else{
	$all_relative_ir_subir = $ir_arr['data'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE;?></title>
	<link href="<?php echo API_MODEL_URL_STRATEGY;?>css/style.css" rel="stylesheet">	
	<link href="<?php echo API_MODEL_URL_STRATEGY;?>css/uswds.min.css" rel="stylesheet">	
	<?php include('includes/resources.php');?> 
	<script type="text/javascript" src="<?php echo API_MODEL_URL_STRATEGY;?>js/go.js"></script>
	<link rel='stylesheet' href='<?php echo HOST_URL?>css/multiple-select.css' />
	<style>
		ul li::before{
			content:'' !important;	
		}
		
		.identity-box{
						width: 20px;
						height: 20px;
						margin-right: 14px;
						border: 1px solid #000;
						display: inline-block;
						font-size: 15px;
					}
	</style>
	<?php if(count($framework_arr['data']['blocks'])>0){?>
	<script>
		function init() {
    	
		// define Converters to be used for Bindings
		function set_pushpin(pushpin) {
		  if(pushpin=="project"){
		  	return "img/pushpin-red.png";
		  }
		  else if(pushpin=="activity"){
		  	return "img/pushpin-blue.png";
		  }
		}

		
		
		if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    	var $ = go.GraphObject.make;  //for conciseness in defining node templates
    	myDiagram =
      	$(go.Diagram, "myDiagramDiv",  //Diagram refers to its DIV HTML element by id
      		{"toolManager.hoverDelay": 300, initialContentAlignment: go.Spot.Center, "undoManager.isEnabled": false});    	
		
        // To simplify this code we define a function for creating a context menu button:
    	function makeButton(text, action, visiblePredicate) {
    		return $("ContextMenuButton",
    			$(go.TextBlock, text),
    			{ click: action },
               // don't bother with binding GraphObject.visible if there's no predicate
               visiblePredicate ? new go.Binding("visible", "", visiblePredicate).ofObject() : {});
    	}
    	var nodeMenu =  // context menu for each Node
    	$(go.Adornment, "Vertical"
    		/*makeButton("Delete",
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
    			function (e, obj) { addPort("bottom"); })*/
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
	        		  new go.Binding("fill", "color")
					),
					
	        	$(go.TextBlock,
	        		{ margin: 10, wrap: go.TextBlock.WrapDesiredSize, textAlign: "center", width: 130, font: "14px  Merriweather", stroke: "#000000", editable: false, overflow: go.TextBlock.OverflowEllipsis},
	        		new go.Binding("text", "name").makeTwoWay()),
				
				$(go.Picture,
            	{
              		row: 0, column: 0, margin: 1,
              		imageStretch: go.GraphObject.Uniform,
              		alignment: go.Spot.TopLeft
            	},
				new go.Binding("desiredSize", "pushpin", function(){ return new go.Size(34, 26) }),
				new go.Binding("source", "pushpin", set_pushpin)
				)
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
		    		function(e, obj) { //e.diagram.commandHandler.pasteSelection(e.diagram.lastInput.documentPoint); },
		    			//function(o) { return o.diagram.commandHandler.canPasteSelection();
					}),
		    	makeButton("Undo",
		    		function(e, obj) { /*e.diagram.commandHandler.undo();*/ },
		    		function(o) { /*return o.diagram.commandHandler.canUndo();*/ }),
		    	makeButton("Redo",
		    		function(e, obj) { /*e.diagram.commandHandler.redo();*/ },
		    		function(o) { /*return o.diagram.commandHandler.canRedo();*/ })
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
				var elem_type_arr = obj.data.__gohashid.split("-");
				var elem_type = elem_type_arr[0];
				if(obj.constructor.name=="S" && (elem_type=='IR' || elem_type=='SR')){ /*show popup*/
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
			var project_id = $('.project_id').val();
			jQuery.ajax({
				type:'GET',
				url:'<?php echo API_HOST_URL_STRATEGY;?>get_association_for_project_and_activity.php?gohashid='+gohashid+'&project_id='+project_id,
				success:function(data){
					if(data['data']=== null || data['data']['assoc_type'] == "Project"){
						$('.chk_act_assoc').prop("checked",false);
						$('.chk_proj_assoc').prop("checked",true);
						$('.assoc_with_activity').addClass('disp-none');
						$('#proj_act_assoc').multipleSelect('setSelects', []);
					}
					else if(data['data']['assoc_type']=="Activity"){
						var activity_arr = new Array();
						activity_arr = data['data']['activity_list'];
						$('.chk_proj_assoc').prop("checked",false);
						$('.chk_act_assoc').prop("checked",true);
						$('.chk_act_assoc').trigger('click');
						$('#proj_act_assoc').multipleSelect('setSelects', activity_arr);
					}
				}
			});	 
		 }
		
	</script>  
	<?php }?>
</head>
<body onLoad="init()">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--main container start-->
    <div class="main-container container-fluid" id="main-content">		
	  <?php include('includes/project_header.php');?>
		<!-- Adding Adding Frame -->
		<div class="add-frame">
			<div class="extra_ht"></div><div class="extra_ht"></div>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Strategy Framework</div>
				<div class="clear"></div>
			</div>
				<div class="container-fluid">
				<div class="row">
				<div class="col-sm-6 col-xs-12">
					  <div class="row">
						  <div class="col-sm-6"><div style="background:#f2dcdb;" class="identity-box"></div><span>Development Goal</span></div>
						  <div class="col-sm-6"><div style="background:#b8dfec;" class="identity-box"></div><span>Development Objective</span></div>
						  <div class="col-sm-6"><div style="background:#c3d69b;" class="identity-box"></div><span>Intermediate Result</span></div>
						  <div class="col-sm-6"><div style="background:#b3a2c7;" class="identity-box"></div><span>Sub-Intermediate Result</span></div>
					  </div>	
				</div>
				<div class="col-sm-6 col-xs-12">
						<div style="padding:20px; font-size:16px; text-align:right" class="bold">
						<span><img src="img/pushpin-red.png" width="20"/> Project Association</span>&nbsp;&nbsp;<span><img src="img/pushpin-blue.png" width="20"/> Activity Association</span>	
					</div>
				</div>
				
				</div>
					<div class="col-md-12">
					
					<div class="table-container">
						<?php if(count($framework_arr['data']['blocks'])>0){?>
						<form id="form_assoc" method="post" action="" style="position:relative">
						<div id="myDiagramDiv" style="width:100%;height:500px;border:1px solid black;border-radius:10px;"></div>
						<div id="popup">
						<!--popup close btn-->
							<div id ="popup_cover">
								<div>
									<span class="pull-right close_btn" style="cursor:pointer"><i class="fa fa-times text-danger"></i></span>
									<div class="clearfix"></div>
								</div>
								<div id="build_relation">
									<div id="poup_msg" style="padding:10px; font-size:16px;" class="disp-none bold"></div>
									<input type="hidden" value="manage_strategy" name="manage_strategy"/>
									<input type="hidden" value="<?php echo $project_id;?>" name="project_id"  class="project_id"/>
									
									<input type="hidden" name="gohashid" value="" id="gohashid"/>
									<table>
									<tr>
										<td class="lbl_td" style="width:250px; min-width:250px">Component</td>
										<td class="chk_td" colspan="3">
											<span id="gohashid_text"></span>
										</td>
									</tr>
									<tr>
										<td class="lbl_td" style="width:250px; min-width:250px">Associate with</td>
										<td class="chk_td" colspan="3">
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
													<input type="radio" name="associate_with" value="Project" class="associate_with chk_proj_assoc"  checked="checked"/><label>Project</label> 
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<input type="radio" name="associate_with" value="Activity" class="associate_with chk_act_assoc"/><label>Activity</label>
												</div>
											</div>
										</td>
									</tr>
									<tr class="assoc_with_activity disp-none"> 
										<td class="lbl_td" style="width:250px; min-width:250px">Project Activity</td>
										<td class="chk_td" colspan="3">
											<select id="proj_act_assoc"  multiple="multiple" name="project_act_assoc[]">
												<?php for($i=0; $i<count($project_activity_arr['data']); $i++){?>
												<option value="<?php echo $project_activity_arr['data'][$i]['activity_id'];?>"><?php echo $project_activity_arr['data'][$i]['title'];?> (<?php echo $project_activity_arr['data'][$i]['activity_id'];?>)</option>
												<?php }?>	
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="4" class="text-center"><button type="button" class="usa-button-outline close_btn" id="cancel_assoc">Cancel</button> <button type="button" id="save_assoc">Save</button></td>
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
								
								if(in_array($arr[$i]['gohashid'],$all_relative_ir_subir) || in_array($arr[$i]['gohashid'],$all_act_relative_ir_subir)){
									if(in_array($arr[$i]['gohashid'],$all_relative_ir_subir)){
										$html.='"name":"'.$arr[$i]['name'].'", "loc":"'.$arr[$i]['location'].'", "color":"'.$arr[$i]['color'].'", "pushpin": "project",';
									
									}
									else if(in_array($arr[$i]['gohashid'],$all_act_relative_ir_subir)){
										$html.='"name":"'.$arr[$i]['name'].'", "loc":"'.$arr[$i]['location'].'", "color":"'.$arr[$i]['color'].'", "pushpin": "activity",';
									
									}
								}	
								else{
									$html.='"name":"'.$arr[$i]['name'].'", "loc":"'.$arr[$i]['location'].'", "color":"'.$arr[$i]['color'].'",';
								}
								
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
					</form>
						<?php } else{ ?>
						<div style="padding:100px 0; font-size:18px; text-align:center;" class="bold text-danger">
							No active strategy framework found. <br/>Draft a new framework. <a href="<?php echo API_MODEL_URL_STRATEGY;?>" target="_blank">Results Framework</a> 
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php include('includes/footer.php');?>
	<script src="<?php echo HOST_URL?>js/main.js"></script>
	<script type="text/javascript" src="<?php echo HOST_URL?>js/jquery.multiple.select.js"></script>
	<script>
	/*activity multiple select*/
	$(document).ready(function () {
		$('#proj_act_assoc').multipleSelect({
			columns: 1,
			placeholder: 'Select Project Activity',
			search: false
		});
	});
	/*function to reset popup*/
	function reset_popup(){
		$('.ms-parent input[type="checkbox"]').prop("checked",false);
	}
	
	/*close popup*/
	$('#popup .close_btn').click(function(){	
		reset_popup();
		$('#popup').css('display','none');	
	});
	
	/*save popup*/
	$('#save_assoc').click(function(){
		var proj = $(this).closest('form').find('.associate_with:checked');
		var actv = $(this).closest('form').find('#proj_act_assoc').next('.ms-parent').find('.ms-drop li input[type="checkbox"]:checked');
		if(proj.val()=="Project" || actv.length>0){ 
			$(this).closest('form').submit();
		}
	});
	
	$('.associate_with').click(function(){
		if($(this).val()=="Project"){
			$('.assoc_with_activity').addClass('disp-none');
		}
		else if($(this).val()=="Activity"){
			$('.assoc_with_activity').removeClass('disp-none');
		}
	});
	
	$('#cancel_assoc').click(function(){
		$('.chk_proj_assoc').trigger('click');
	});
	</script>
</body>
</html>
