<?php
if((isset($_POST) && count($_POST)==0) || !isset($_POST)){
	header("Location:login");
}
?>
<?php include('config/config.inc.php');?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?> 
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--leftpanel start-->
	<?php include('includes/leftpanel.php');?>
    <!--leftpanel end-->
	<div class="main-content" id="main-content">
      <div class="styleguide-content usa-content">
        <header><h1 id="ui-components">Program Cycle Management</h1></header>
		<button onClick="window.location='projects'">View Projects</button>
		<div class="dsb-progress-reader">
			<div class="progress-reader-blk blue">
				<div class="text-center">
					<div>Total Projects</div><div class="bold">500</div>
				</div>
			</div>
			<div class="progress-reader-blk green">
				<div class="text-center">
					<div>Active Projects</div><div class="bold">430</div>
				</div>
			</div>
			<div class="progress-reader-blk red">
				<div class="text-center">
					<div>Committed Funds</div><div class="bold">$ 3.2 b</div>
				</div>
			</div>
			<div class="progress-reader-blk yellow">
				<div class="text-center" >
					<div>Obligated Funds</div><div class="bold">$ 2.6 b</div>
				</div>
			</div>
		</div>
      </div>
    </div>
	<?php include('includes/footer.php');?>
</body>
</html>
