
<!DOCTYPE html>
<html>
<head>
<title>Data Inspector</title>
<!-- Copyright 1998-2016 by Northwoods Software Corporation. -->
<meta charset="UTF-8">
<script src="js/go.js"></script>
<link href="assets/css/goSamples.css" rel="stylesheet" type="text/css" />  <!-- you don't need to use this -->
<!-- this is only for the GoJS Samples framework -->

<link rel='stylesheet' href='assets/css/dataInspector.css' />
<script src="assets/js/dataInspector.js"></script>

<script id="code">
  var nodeIdCounter = -1; // use a sequence to guarantee key uniqueness as we add/remove/modify nodes

  function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for conciseness in defining templates

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
                   function(e, obj) { e.diagram.commandHandler.copySelection(); console.log(e.diagram.commandHandler);}),
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


    // manage boss info manually when a node or link is deleted from the diagram
    myDiagram.addDiagramListener("SelectionDeleting", function(e) {
      var part = e.subject.first(); // e.subject is the myDiagram.selection collection,
                                    // so we'll get the first since we know we only have one selection
      myDiagram.startTransaction("clear boss");
      if (part instanceof go.Node) {
        var it = part.findTreeChildrenNodes(); // find all child nodes
        while(it.next()) { // now iterate through them and clear out the boss information
          var child = it.value;
          var bossText = child.findObject("boss"); // since the boss TextBlock is named, we can access it by name
          if (bossText === null) return;
          bossText.text = undefined;
        }
      } else if (part instanceof go.Link) {
        var child = part.toNode;
        var bossText = child.findObject("boss"); // since the boss TextBlock is named, we can access it by name
        if (bossText === null) return;
        bossText.text = undefined;
      }
      myDiagram.commitTransaction("clear boss");
    });

    
    // override TreeLayout.commitNodes to also modify the background brush based on the tree depth level
    myDiagram.layout.commitNodes = function() {
      go.TreeLayout.prototype.commitNodes.call(myDiagram.layout);  // do the standard behavior
      // then go through all of the vertexes and set their corresponding node's Shape.fill
      // to a brush dependent on the TreeVertex.level value
      myDiagram.layout.network.vertexes.each(function(v) {
        if (v.node) {
          var level = v.level % (levelColors.length);
          var colors = levelColors[level].split("/");
          var shape = v.node.findObject("SHAPE");
          if (shape) shape.fill = $(go.Brush, "Linear", { 0: colors[0], 1: colors[1], start: go.Spot.Left, end: go.Spot.Right });
        }
      });
    };

    // This function is used to find a suitable ID when modifying/creating nodes.
    // We used the counter combined with findNodeDataForKey to ensure uniqueness.
    function getNextKey() {
      var key = nodeIdCounter;
      while (myDiagram.model.findNodeDataForKey(key.toString()) !== null) {
        key = nodeIdCounter -= 1;
      }
      return key.toString();
    }
	
	
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
          { fill: "whitesmoke", stroke: "black" }),
        $(go.TextBlock,
          { font: "bold 8pt Helvetica, bold Arial, sans-serif",
            wrap: go.TextBlock.WrapFit,
            margin: 5 },
          new go.Binding("text", "", tooltipTextConverter))
      );
	  
	  
	  $(go.Adornment, "Auto",
        $(go.Shape, "Rectangle",
          { fill: "whitesmoke", stroke: "black" }),
        $(go.TextBlock,
          { font: "bold 8pt Helvetica, bold Arial, sans-serif",
            wrap: go.TextBlock.WrapFit,
            margin: 5 },
          new go.Binding("text", "", tooltipTextConverter))
      );
	  
    // this is used to determine feedback during drags
    function mayWorkFor(node1, node2) {
      if (!(node1 instanceof go.Node)) return false;  // must be a Node
      if (node1 === node2) return false;  // cannot work for yourself
      if (node2.isInTreeOf(node1)) return false;  // cannot work for someone who works for you
      return true;
    }

    // This function provides a common style for most of the TextBlocks.
    // Some of these values may be overridden in a particular TextBlock.
    function textStyle() {
      return { font: "9pt  Segoe UI,sans-serif", stroke: "white" };
    }

    // This converter is used by the Picture.
    function findHeadShot(key) {
      if (key < 0 || key > 16) return "images/HSnopic.png"; // There are only 16 images on the server
      return "images/HS" + key + ".png"
    }

    // define the Node template
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
            stretch: go.GraphObject.Fill, toolTip: tooltiptemplate  },
          $(go.Shape, "Rectangle",
            { stroke: null, strokeWidth: 0, 
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
	  
	  
    // the context menu allows users to make a position vacant,
    // remove a role and reassign the subtree, or remove a department
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


    // define the Link template
    myDiagram.linkTemplate =
      $(go.Link, go.Link.Orthogonal,
        { corner: 5, relinkableFrom: true, relinkableTo: true },
        $(go.Shape, { strokeWidth: 4, stroke: "#00a4a4" }));  // the link shape

    // read in the JSON-format data from the "mySavedModel" element
    load();


    // support editing the properties of the selected person in HTML
    if (window.Inspector) myInspector = new Inspector('myInspector', myDiagram,
      {
        properties: {
          'key': { readOnly: true },
          'comments': {}
        }
      });

	
	showpopup(myDiagram); 
  }

  // Remove the clicked port from the node.
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
 
 function showpopup(myDiagram){
 	myDiagram.addDiagramListener("ChangedSelection", function(e) { 
		/*check whether obejct is block or a line*/
		var obj = myDiagram.selection.first();
		if(obj.constructor.name=="S"){ /*show popup*/
			document.getElementById("popup").style.display="block";
		}
		
		console.log(obj.data);
	});		 
 }
</script>
</head>
<body onLoad="init()">
  <div id="sample">
  <div id="myDiagramDiv" style="border: solid 1px black; height: 500px"></div>
  <div>
    <div id="myInspector" style="display:none">

    </div>
	<div id="popup" style="display:none">
		<table>
			<tr>
			<td>Project</td>
			<td>
				<select>
					<option></option>
				</select>
			</td></tr>
		</table>
	</div>
  </div>
  <p>
    This editable organizational chart sample color-codes the Nodes according to the tree level in the hierarchy.
  </p>
  <p>
    Double click on a node in order to add a person or the diagram background to add a new boss. Double clicking the diagram uses the <a>ClickCreatingTool</a>
    with a custom <a>ClickCreatingTool.insertPart</a> to assign an ID.
  </p>
  <p>
    Drag a node onto another in order to change relationships.
    You can also draw a link from a node's background to other nodes that have no "boss". Links can also be relinked to change relationships.
    Right-click or tap-hold a Node to bring up a context menu which allows you to:
    </p><ul>
      <li>Vacate Position - remove the information specfic to the current person in that role</li>
      <li>Remove Role - removes the role entirely and reparents any children</li>
      <li>Remove Department - removes the role and the whole subtree</li>
    </ul>
    Deleting a Node or Link will orphan the child Nodes and generate a new tree. A custom SelectionDeleting <a>DiagramEvent</a> listener will clear out the boss info
    when the parent becomes undefined.
  <p></p>
  <p>
    Select a node to edit/update node data values. This sample uses the <a href="../extensions/dataInspector.html">Data Inspector</a> extension to display and modify Part data.
  </p>
  <p>
    To learn how to build an org chart from scratch with GoJS, see the <a href="../learn/index.html">Getting Started tutorial</a>.
  </p>
  <div>
    <div>
      <button id="SaveButton" onClick="save()">Save</button>
      <button onClick="load()">Load</button>
      Diagram Model saved in JSON format:
    </div>
    <textarea id="mySavedModel" style="width:100%;height:250px">{ "class": "go.GraphLinksModel",
	"copiesArrays": true,
  "copiesArrayObjects": true,
  "linkFromPortIdProperty": "fromPort",
  "linkToPortIdProperty": "toPort",
  "nodeDataArray": [
{"key":1, "__gohashid":"dd_12","name":"unit One", "loc":"101 204",
 "leftArray":[ {"portColor":"#425e5c", "portId":"left0"} ],
 "topArray":[ {"portColor":"#d488a2", "portId":"top0"} ],
 "bottomArray":[ {"portColor":"#316571", "portId":"bottom0"} ],
 "rightArray":[ {"portColor":"#923951", "portId":"right0"},{"portColor":"#ef3768", "portId":"right1"} ] },
{"key":2,"__gohashid":"dd13","name":"unit Two", "loc":"320 152",
 "leftArray":[ {"portColor":"#7d4bd6", "portId":"left0"},{"portColor":"#cc585c", "portId":"left1"},{"portColor":"#b1273a", "portId":"left2"} ],
 "topArray":[ {"portColor":"#14abef", "portId":"top0"} ],
 "bottomArray":[ {"portColor":"#dd45c7", "portId":"bottom0"},{"portColor":"#995aa6", "portId":"bottom1"},{"portColor":"#6b95cb", "portId":"bottom2"} ],
 "rightArray":[  ] },
{"key":3,"__gohashid":"dd14","name":"unit Three", "loc":"384 319",
 "leftArray":[ {"portColor":"#bd8f27", "portId":"left0"},{"portColor":"#c14617", "portId":"left1"},{"portColor":"#47fa60", "portId":"left2"} ],
 "topArray":[ {"portColor":"#d08154", "portId":"top0"} ],
 "bottomArray":[ {"portColor":"#6cafdb", "portId":"bottom0"} ],
 "rightArray":[  ] },
{"key":4, "__gohashid":"dd15", "name":"unit Four", "loc":"138 351",
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
 ]
}
    </textarea>
  </div>
</div>


</body>
</html>
