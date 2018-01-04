<header>
<nav class="navbar navbar-default header">
  <div class="container-fluid">
    <div class="navbar-header pull-right">
      <a class="navbar-brand" href="home"><img src="img/logo.png" width="150"/></a>
	   <?php if(strpos("/login",$_SERVER['REQUEST_URI']) === false && strpos("/login.php",$_SERVER['REQUEST_URI']) === false){?><span class="mb-leftpanel-btn text-center"><i class="fa fa-chevron-down"></i></span><?php }?>
	   <ul class="lft-dpw dropdown-menu">
		<li>
			<form method="post" class="form-inline form_logout" action="login">
				<input type="hidden" name="logout_user" value="logout_user"/>
				<a href="javascript:logout()" class="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
			</form>
		</li>
	  </ul>
	  <div class="clear"></div>
	</div>
    <div class="navabar-l-head">
		<a href="home">Model 3</a> 
	</div>
	<div class="heading">
		<h1>Development Portal</h1> 
	</div>
	<div class="clear"></div>
  </div>
</nav>
</header>
