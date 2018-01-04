<!DOCTYPE html>
<html>
<head>
<title>USAID - AMP</title>
<?php include('includes/resources.php');?> 
</head>
<body>
	<!--header start-->
	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container-fluid">
		<ul class="header">
			<li>
				<span class="header-heading">Aid Manager</span>
				<span class="header-modelname">Model 1</span>
				<a href="./"><img src="images/logo.png" class="logo"/></a>
			</li>
		</ul>
	  </div>
	</nav>
	<!--header end-->
	<div class="main-container">
		<!--leftpanel start-->
		<div class="leftpanel">
			<ul>
				<li>
					<a class="menu-item" href="./"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard </a>
				</li>
				<li class="active">
					<a class="menu-item"><i class="fa fa-th" aria-hidden="true"></i> Projects</a>
					<ul class="list-subitem-par" style="display:block">
						<li><a href="projects.php">View Projects</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<!--leftpanel end-->
		<div class="data-container">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<nav class="breadcrumb">
						  <a class="breadcrumb-item" href="./">Home</a> &raquo;
						  <span class="breadcrumb-item active">Projects</span>
						</nav>
					</div>					
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search-blk">
						<div class="search-desc">
							Find Project Related Information
						</div>
						<div class="data-search">
							<div class="input-group">
							  <input type="text" class="form-control" placeholder="Find Project" aria-describedby="basic-addon1">
							  <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="tbl-block">
							<div class="tbl-caption">
								<div class="tbl-content-head">Projects List </div>
								<div class="tbl-content-btn">
									<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
									<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
								</div>
								<div class="clear"></div>
							</div>
							<div class="table-container">
								<table id="example" class="display table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Project Id</th>
										<th>Project Title</th>
										<th class="text-center">Approved Budget</th>
										<th class="text-center">Stage</th>
										<th class="text-center">Next Review</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$pid=10001;
									$p_title="Safety and justice";
									$p_stages="Implementation";
									for($i=1;$i<300;$i++){
										$p_budget=rand(10000,99999999);
										if($i%2==0){$p_title="Safety and justice"; }
										else{ $p_title="Education";}
									?>
									<tr>
										<td><?php echo $pid;?></td>
										<td><?php echo $p_title;?></td>
										<td class="text-right">$<?php echo $p_budget;?></td>
										<td class="text-center"><?php echo $p_stages;?></td>
										<td class="text-right">2011/07/25</td>
										<td class="text-center"> <a style="color:#00a6d2">Details</a> | <a style="color:#cd2026">Remove</a></td>
									</tr>
									<?php $pid=$pid+1; }?>
								</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!--plugins-->
<script src="http://rgdemo.com/usaid/amp/1/js/main.js"></script>
</body>
</html>
