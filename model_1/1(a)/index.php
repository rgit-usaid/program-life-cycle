<?php include('config/config.inc.php');?> 
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
				<li class="active">
					<a class="menu-item" href="./"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard </a>
				</li>
				<li>
					<a class="menu-item"><i class="fa fa-th" aria-hidden="true"></i> Projects</a>
					<ul class="list-subitem-par">
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
						<div class="dashboard-heading">Program Cycle Management</div>
						<a href="projects.php" class="btn" style="background:#0071bc; font-size:17px; font-weight:bold;color:white">View Projects</a>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px; margin-bottom:150px">
						<div style="width:50%; float:left; text-align:left">
							<div style="padding:50px 10px; background:white; width:400px; display:inline-block;font-size:35px; box-shadow:2px 2px 4px #333;color:#5b616b">
								<div class="text-center"> <span>Total Projects </span><br/><span style="color:#205493;font-weight:bold">500</span></div>
							</div>
						</div>
						<div style="width:50%; float:left; text-align:left">
							<div style="padding:50px 10px; background:white; width:400px; display:inline-block;font-size:35px; box-shadow:2px 2px 4px #333;color:#5b616b">
								<div class="text-center"> <span>Active Projects </span><br/><span style="color:#205493; font-weight:bold">430</span></div>
							</div>
						</div>
						<div style="height:50px;" class="clear"></div>
						<div style="width:50%; float:left; text-align:left">
							<div style="padding:50px 10px; background:white; width:400px; display:inline-block;font-size:35px; box-shadow:2px 2px 4px #333;color:#5b616b">
								<div class="text-center"> <span>Committed Funds </span><br/><span style="color:#205493;font-weight:bold">$ 3.2 b</span></div>
							</div>
						</div>
						<div style="width:50%; float:left; text-align:left">
							<div style="padding:50px 10px; background:white; width:400px; display:inline-block;font-size:35px;box-shadow:2px 2px 4px #333;color:#5b616b">
								<div class="text-center"> <span>Obligated Funds </span><br/><span style="color:#205493;font-weight:bold">$ 2.6 b</span></div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!--plugins-->
<script src="http://rgdemo.com/usaid/amp/1(a)/js/main.js"></script>
</body>
</html>
