<link href="css/login_css.css" rel="stylesheet" />
<header id="header">
	<div class="container-fluid">
	<div class="row">
		<ul class="nav navbar-nav head">
		<div class="col-sm-2">
			<li class="pull-left"><h2 style="font-family: Arial; font-weight: bold;"><a href=".">Model 4</a></h2></li></div>
			<div class="col-sm-7"><li><h2 style="text-transform: uppercase;">Program Management Center</h2></li></div>
			<div class="col-sm-2"><li class="social"><img src="img/logo.png" alt="" class="img-responsive" width="150" /></li></div>
			<div class="col-sm-1"><li><span class="mb-leftpanel-btn text-center">
		      <i class="fa fa-chevron-down"></i>
		      </span>
			<ul class="lft-dpw dropdown-menu">
			<li>
				<form method="post" class="form-inline form_logout" action="login.php">
					<input type="hidden" name="logout_user" value="logout_user">
					<a href="javascript:logout()" class="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
				</form>
			</li>
		  </ul>
		 </li>		  
		</div>
			
		</ul>
	 </div>
	  </div>
	
</header>

 <script src="js/jquery.min.js"></script>
       <script>
  $(document).ready(function(){
	  $(".mb-leftpanel-btn").click(function(){
		  $(".dropdown-menu").toggle();
		  });
  });
  
  function logout(){
	$('.form_logout').submit();
}
  </script>