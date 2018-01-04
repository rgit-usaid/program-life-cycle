<?php 
$lp_sel="home";
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
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		span>a:visited{
			color: #fff;
		}
		.dataTables_length{display:none;}
		#left-menu > li >a >p{
			display: table;
		}
		#left-menu > li >a >img{
			margin-top: 18px;
			margin-right: 10px;
			-ms-transform: rotate(270deg); /* IE 9 */
			-webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
			transform: rotate(270deg);
		}
		#left-menu > li >a {
			text-decoration: none;
		}
		#manage-table_wrapper{
			margin-top: 5px;
		}
		.btn-warning,.btn-danger{
			border-radius: 3px;
		}
		#example_filter{
			display:none;
		}
	#myselect{
		width:200px;
		max-width:100%;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Todo List</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/left_panel.php'; ?>
		</div>
		<div class="col-md-6">
			<div class="wrap">
				<div class="container-fluid">
					<div class="col-md-5">
						<h3 class="text-left">To-do-list</h3>
					</div>
					<div class="col-md-7">
					
						<h5 class="text-right" style="display:block">To be completed by:[Name]</h5>
						<h5 class="text-right" style="display:block">Deadline:[Date]</h5>
						
					</div>
					<div class="clearfix"></div>
					<div class="drop1" style="float:right; padding-bottom:20px;">
						<select name="myselect" id="myselect">
							<option >Choose</option>
							<option value="done" >Completed</option>
							<option value="notdone">InCompleted</option>
						</select>
					</div>
					<table id="example" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Done?</th>
								<th>Task</th>
								<th>Project Name</th>
								<th>Due By</th>
								<th>Notes</th>      
							</tr>
						</thead>
						<tbody>
							<tr class="Completed">
								<td class="text-center text-success"><i class="fa fa-check" aria-hidden="true"></i></td>
								<td class="text-center">Paperwork</td>
								<td class="text-center">Project 1</td>
								<td>dfgh</td>
								<td>dfg</td>
							</tr>
							<tr class="Completed">
								<td class="text-center text-success"><i class="fa fa-check" aria-hidden="true"></i></td>
								<td class="text-center">Task b</td>
								<td class="text-center">Project 1</td>
								<td></td>
								<td></td>
							</tr>
							<tr class="Completed">
								<td class="text-center text-success"><i class="fa fa-check" aria-hidden="true"></i></td>
								<td class="text-center">Paperwork</td>
								<td class="text-center">Project 3</td>
								<td></td>
								<td></td>
							</tr>
							<tr class="InCompleted">
							   <td ></td>
								<td class="text-center">Task a</td>
								<td class="text-center">Project 1</td>
								<td></td>
								<td></td>
							</tr>
							<tr class="Completed">
								<td class="text-center text-success"><i class="fa fa-check" aria-hidden="true"></i></td>
								<td class="text-center">Task a</td>
								<td class="text-center">Project 2</td>
								<td></td>
								<td></td>
							</tr>
							<tr class="Completed">
								<td class="text-center text-success"><i class="fa fa-check" aria-hidden="true"></i></td>
								<td class="text-center">Task a</td>
								<td class="text-center">Project 2</td>
								<td></td>
								<td></td>
							</tr>
							<tr class="InCompleted">
								<td></td>
								<td class="text-center">Paperwork</td>
								<td class="text-center">Project 2</td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div> 
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>	
					<div class="blk">
						This screen is not functioning. It has been placed here to present how staff might see the tasks they have been assigned and how they can address them in an organized, easy-to-understand way.  
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>
	<script type="text/javascript">	
		$(document).ready(function() {
    var table = $('#example').DataTable({
        "columnDefs": [
            { "visible": false, "targets": 2 }
        ],
        "order": [[ 2, 'asc' ]],
        "displayLength": 25,
		"responsive" :true,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );
 
    // Order by the grouping
    $('#example tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
            table.order( [ 2, 'desc' ] ).draw();
        }
        else {
            table.order( [ 2, 'asc' ] ).draw();
        }
    } );
} );
	</script>

<script type="text/javascript">
$(document).ready(function(){
    $("#myselect").change(function(){
        $(this).find("option:selected").each(function(){
            if($(this).attr("value")=="done"){
                //$("tbody").find("tr").not(".text-success").hide();
				$(".Completed").show();
				$(".InCompleted").hide();
                
            }
            else if($(this).attr("value")=="notdone"){
                $(".InCompleted").show();
				$(".Completed").hide();
            }
			
            else{
                $(".Completed,.InCompleted").show();
				
            }
        });
    }).change();
});
</script>
</body>
</html>