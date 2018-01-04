<header>
	<div class="container-fluid">
		<ul class="nav navbar-nav head">
			<li class="pull-left"><h2><a href=".">Model</a></h2></li>
			<li><h2>STRATEGIC PLANNING, FRAMEWORKS, DOAGs & SOAGs</h2></li>
			<li class="social pull-right"><img src="img/logo.png" alt="" class="img-responsive" width="150"></li>
		</ul>
	</div>
</header>
<?php 
if(($_SERVER['REQUEST_URI']!='/usaid/strategy3/')){?> 
<div class="menu">
	<div class="container-fluid">
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline-inverse <?php if($page_name=="framework_management"){?> active-class <?php }?>" href="framework_management.php">Framework Management</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline-inverse <?php if($page_name=="indicator_management"){?> active-class <?php }?>" href="indicator_management.php">Indicator Management</a>
		</div>
		<div class="col-md-4 text-center">
			<a class="usa-button usa-button-outline-inverse <?php if($page_name=="doag_management"){?> active-class <?php }?>" href="go_to_doag.php">DOAGs and SOAGs</a>
		</div>
	</div>
</div>
<?php }?>