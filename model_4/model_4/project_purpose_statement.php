<?php 
	$clp_sel ="cdcs";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>USAID-4</title>
	<link href="css/uswds.min.css" rel="stylesheet">
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<script src="js/go.js"></script>
	<script id="code">
  function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  //for conciseness in defining node templates

    myDiagram =
      $(go.Diagram, "myDiagramDiv",  //Diagram refers to its DIV HTML element by id
        { initialContentAlignment: go.Spot.Center, "undoManager.isEnabled": true });

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
        makeButton("Copy",
                   function(e, obj) { e.diagram.commandHandler.copySelection(); }),
        makeButton("Delete",
                   function(e, obj) { e.diagram.commandHandler.deleteSelection(); }),
        $(go.Shape, "LineH", { strokeWidth: 2, height: 1, stretch: go.GraphObject.Horizontal }),
        makeButton("Add top port",
                   function (e, obj) { addPort("top"); }),
        makeButton("Add left port",
                   function (e, obj) { addPort("left"); }),
        makeButton("Add right port",
                   function (e, obj) { addPort("right"); }),
        makeButton("Add bottom port",
                   function (e, obj) { addPort("bottom"); })
      );

    var portSize = new go.Size(8, 8);

    var portMenu =  // context menu for each port
      $(go.Adornment, "Vertical",
        makeButton("Remove port",
                   // in the click event handler, the obj.part is the Adornment;
                   // its adornedObject is the port
                   function (e, obj) { removePort(obj.part.adornedObject); }),
        makeButton("Change color",
                   function (e, obj) { changeColor(obj.part.adornedObject); }),
        makeButton("Remove side ports",
                   function (e, obj) { removeAll(obj.part.adornedObject); })
      );

    // the node template
    // includes a panel on each side with an itemArray of panels containing ports
    myDiagram.nodeTemplate =
      $(go.Node, "Table",
        { locationObjectName: "BODY",
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
            { fill: "#AC193D", stroke: null, strokeWidth: 0,
              minSize: new go.Size(56, 56) }),
          $(go.TextBlock,
            { margin: 10, textAlign: "center", font: "14px  Segoe UI,sans-serif", stroke: "white", editable: true },
            new go.Binding("text", "name").makeTwoWay())
        ),  // end Auto Panel body

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
                  new go.Binding("fill", "portColor"))
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
                  new go.Binding("fill", "portColor"))
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
                  new go.Binding("fill", "portColor"))
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
                  new go.Binding("fill", "portColor"))
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
        $(go.Shape, { stroke: "#2F4F4F", strokeWidth: 2 })
      );

    // support double-clicking in the background to add a copy of this data as a node
    myDiagram.toolManager.clickCreatingTool.archetypeNodeData = {
      name: "Unit",
      leftArray: [],
      rightArray: [],
      topArray: [],
      bottomArray: []
    };

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
        } else {  // left or right
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

  // Change the color of the clicked port.
  function changeColor(port) {
    myDiagram.startTransaction("colorPort");
    var data = port.data;
    myDiagram.model.setDataProperty(data, "portColor", go.Brush.randomColor());
    myDiagram.commitTransaction("colorPort");
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
</script>
	<style>
		
		.disp-none{
			display: none;
		}
	</style>
</head>
<body onLoad="init()">

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_design_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal project_design_plan" action="" method="post">
						<div  class="form_grp" style="padding:20px 5px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Project Details</h3>
							<div>Activites scheduled for concurrent design are located on the Activtiy Tab</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Stage : <span class="bold">Project Design Plan</span></div>
						</div>
						<div class="form_grp">
							<div class="from_label">Title of Proposed Project</div>
							<div>
								<textarea class="autoh_textarea"></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Originating Operating Unit</div>
							<div>
								<input type="text" placeholder="Originating Operating Unit"/>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Implementing Operating Unit</div>
							<div>
								<input type="text" placeholder="Implementing Operating Unit"/>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Purpose</div>
							<div>
								<textarea class="autoh_textarea"></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Framework</div>
							<div id="sample">
					  <div id="myDiagramDiv" style="width:600px; height:500px;"></div>
					  <div>
							<textarea id="mySavedModel" style="width:100%;height:250px; display:none">
						{ "class": "go.GraphLinksModel",
						  "copiesArrays": true,
						  "copiesArrayObjects": true,
						  "linkFromPortIdProperty": "fromPort",
						  "linkToPortIdProperty": "toPort",
						  "nodeDataArray": [
						{"key":1, "name":"Goal 1", "loc":"101 204", "color":"#b8dfec",
						 "leftArray":[ {"portColor":"#425e5c", "portId":"left0"} ],
						 "topArray":[ {"portColor":"#d488a2", "portId":"top0"} ],
						 "bottomArray":[ {"portColor":"#316571", "portId":"bottom0"} ],
						 "rightArray":[ {"portColor":"#923951", "portId":"right0"},{"portColor":"#ef3768", "portId":"right1"} ] },
						{"key":2, "name":"DO 1", "loc":"320 152", "color" :"#b8dfec",
						 "leftArray":[ {"portColor":"#7d4bd6", "portId":"left0"},{"portColor":"#cc585c", "portId":"left1"},{"portColor":"#b1273a", "portId":"left2"} ],
						 "topArray":[ {"portColor":"#14abef", "portId":"top0"} ],
						 "bottomArray":[ {"portColor":"#dd45c7", "portId":"bottom0"},{"portColor":"#995aa6", "portId":"bottom1"},{"portColor":"#6b95cb", "portId":"bottom2"} ],
						 "rightArray":[  ] },
						{"key":3, "name":"IR", "loc":"384 319", "color":"#b8dfec",
						 "leftArray":[ {"portColor":"#bd8f27", "portId":"left0"},{"portColor":"#c14617", "portId":"left1"},{"portColor":"#47fa60", "portId":"left2"} ],
						 "topArray":[ {"portColor":"#d08154", "portId":"top0"} ],
						 "bottomArray":[ {"portColor":"#6cafdb", "portId":"bottom0"} ],
						 "rightArray":[  ] },
						{"key":4, "name":"Project", "loc":"138 351", "color":"#b8dfec",
						 "leftArray":[ {"portColor":"#491389", "portId":"left0"} ],
						 "topArray":[ {"portColor":"#77ac1e", "portId":"top0"} ],
						 "bottomArray":[ {"portColor":"#e9701b", "portId":"bottom0"} ],
						 "rightArray":[ {"portColor":"#24d05e", "portId":"right0"},{"portColor":"#cfabaa", "portId":"right1"} ] }
						 ],
						  "linkDataArray": [
						{"from":4, "to":2, "fromPort":"top0", "toPort":"bottom0"},
						{"from":4, "to":2, "fromPort":"top0", "toPort":"bottom0"},
						{"from":3, "to":2, "fromPort":"top0", "toPort":"bottom1"},
						{"from":4, "to":3, "fromPort":"right0", "toPort":"left0"},
						{"from":4, "to":3, "fromPort":"right1", "toPort":"left2"},
						{"from":1, "to":2, "fromPort":"right0", "toPort":"left1"},
						{"from":1, "to":2, "fromPort":"right1", "toPort":"left2"}
						 ]}
							</textarea>
					  </div>
					</div>
							<div class="from_label">
								Describe how this project supports one or more IRs or Sub-IRs in the CDCS or other country strategic plan.
							</div>
							<div>
								<textarea style="min-height:300px" class="autoh_textarea"></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label bold">Rational For choosing each IR or Sub-IR</div>
							<div>
								<textarea style="min-height:300px" class="autoh_textarea"></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label bold">Indentity proposed for project role</div>
							<div class="from_label">Project Manager</div>
							<div class="from_label">Assistant Project Manager</div>
							<div class="from_label">COR/AOR</div>
						</div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button" id="proceed">Save</button> 
						</div>
					</form> 
				</div> 
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>	
									
				</div>
			</div>
			<div class="third-stage disp-none blk">A Mission must notify its Regional Bureau and PPL of its plans to extend its CDCS at least nine months, but no more than 18 months, before its CDCS expiration date. Notification of an intended extension in less than nine months before the CDCS expiration, due to emergency circumstances will be considered on a case-by-case basis. A request for extension, which is submitted but not approved, cannot serve as a justification for a Mission failing to complete a new CDCS prior to the expiration of its existing CDCS
			</div>				
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('#proceed').click(function(){
			window.location = "existing_project.php";
		});
	</script>
</body>
</html>