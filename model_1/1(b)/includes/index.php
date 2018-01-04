<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?> 
</head>
<body class="page-ui-components" style="padding-top: 0px;">
  	<a class="skipnav" href="#main-content">Skip to main content</a>
	<header class="usa-site-header" role="banner">
	  <div class="site-navbar" style="text-align:center">
		<a class="menu-btn" href="#">Menu</a>
		<div class="site-logo" id="logo">
		  <em>
			<a href="/" accesskey="1" title="Home" aria-label="Home"><img src="img/logo.png" width="150"/></a>
		  </em>
		</div>
		<div class="header-heading">AID Manager</div>
		<ul class="usa-button-list usa-unstyled-list"><li> <em>Model 1</em></li></ul>
	  </div>
	</header>
	<div class="overlay"></div>
    <aside class="sidenav">
      	<nav>
		  <ul class="usa-sidenav-list">
			<li><a class="usa-current" href="index.php"><i class="fa fa-bar-chart" aria-hidden="true"></i> Dashboard</a></li>
			<li>
			  <a href="#"><i class="fa fa-th" aria-hidden="true"></i> Projects</a>
			  <ul class="usa-sidenav-sub_list">
                <li><a href="projects.php"><i class="fa fa-link" aria-hidden="true"></i> View Projects</a></li>
			  </ul>
			</li>
		  </ul>
		</nav>
    </aside>
<!--<aside class="sidenav-mobile">
  <a class="sliding-panel-close" href="#">
    <img src="/assets/img/close.svg" alt="close">
  </a>
  <nav>
    <ul class="usa-sidenav-list usa-accordion">
      <li>
        <button class="usa-current" aria-controls="side-nav-1">UI components</button>
        <ul id="side-nav-1" class="usa-sidenav-sub_list">
          <li>
            <a class="usa-current" href="/">Overview</a>
          </li>
          <li>
            <a href="/typography/">Typography</a>
          </li>
          <li>
            <a href="/colors/">Colors</a>
          </li>
          <li>
            <a href="/grids/">Grid</a>
          </li>
          <li>
            <a href="/buttons/">Buttons</a>
          </li>
          <li>
            <a href="/labels/">Labels</a>
          </li>
          <li>
            <a href="/tables/">Tables</a>
          </li>
          <li>
            <a href="/alerts/">Alerts</a>
          </li>
          <li>
            <a href="/accordions/">Accordions</a>
          </li>
          <li>
            <a href="/form-controls/">Form controls</a>
          </li>
          <li>
            <a href="/form-templates/">Form templates</a>
          </li>
          <li>
            <a href="/search-bar/">Search bar</a>
          </li>
          <li>
            <a href="/sidenav/">Side navigation</a>
          </li>
          <li>
            <a href="/footers/">Footers</a>
          </li>
        </ul>
      </li>
      <li>
        <button aria-expanded="false" aria-controls="sidenav-2">Getting started</button>
        <ul id="sidenav-2" class="usa-sidenav-sub_list">
          <li>
            <a href="/getting-started/">Overview</a>
          </li>
          <li>
            <a href="/getting-started/developers/">For developers</a>
          </li>
          <li>
            <a href="/getting-started/designers/">For designers</a>
          </li>
          <li>
            <a href="/download">Download code and design files</a>
          </li>
        </ul>
      </li>
      <li>
        <a href="/design-principles/">Design principles</a>
      </li>
      <li>
        <button aria-expanded="false" aria-controls="sidenav-3">About our work</button>
        <ul id="sidenav-3" class="usa-sidenav-sub_list">
          <li>
            <a href="/about-our-work/">Overview</a>
          </li>
          <li>
            <a href="/about-our-work/product-roadmap/">Product roadmap</a>
          </li>
          <li>
            <a href="/about-our-work/component-maturity-scale/">Component maturity scale</a>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="usa-button-list usa-unstyled-list">
  <li>
    <a class="usa-button" href="/download/" onClick="ga('send', 'event', 'Downloaded code and design files', 'Clicked Download code and design files from inside site');">
      Download code and design files
    </a>
  </li>
  <li>
    <a class="usa-button usa-button-outline-inverse" href="https://github.com/18F/web-design-standards" onClick="ga('send', 'event', 'Viewed on Github', 'Clicked View on Github from inside site');">
      View on GitHub
    </a>
  </li>
</ul>

  </nav>
</aside>-->
    <div class="main-content" id="main-content">
      <div class="styleguide-content usa-content">
        <header><h1 id="ui-components" style="color:#112e51">Program Cycle Management</h1></header>
		<button onClick="window.location='projects.php'">View Projects</button>
		<div class="dsb-progress-reader">
			<div class="progress-reader-blk" style="background:#dce4ef">
				<div class="text-center">
					<div>Total Projects</div><div class="bold">500</div>
				</div>
			</div>
			<div class="progress-reader-blk" style="background:#e7f4e4">
				<div class="text-center">
					<div>Active Projects</div><div class="bold">430</div>
				</div>
			</div>
			<div class="progress-reader-blk" style="background:#f9dede">
				<div class="text-center">
					<div>Committed Funds</div><div class="bold">$ 3.2 b</div>
				</div>
			</div>
			<div class="progress-reader-blk" style="background:#fff1d2">
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
