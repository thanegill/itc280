<?php echor(DOCTYPE); ?>
<!--#####################################################
##  	______                           __   ______   ##
##     / ____/___  ____  _ ____  ___    / / /  __   |  ##
##    / __/ / __ \/ __ `/ / __ \/ _ \  / / |  /_/  /   ##
##   / /___/ / / / /_/ / / / / /  __/ / / /  __   |    ##
##  /_____/_/ /_/\__, /_/_/ /_/\___/ / / /  /_/  /     ##
##              /____/ D E S I G N  /_/  \______/      ##
##                                                     ##
##                  engine18design.com                 ##
##                        ©2012                        ##
######################################################-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo($pageTitle . TITLE_TAG_END); ?></title>
	<?php 
	displayCSS(); 
	displayJS();
	displayShim();
	?>
</head>
<body>
	<div id="body_inner">
		<header>
			<div id="site_title"><a href="/"><?php echo(SITE_NAME) ?></a></div>
		</header>
		<nav>
			<ul>
				<li><a class="nav-title" href="/">Blog</a></li>
				<?php
				displayNav('assignment', 'Assignmnets');
				displayNav('extracredit', 'Extra Credit');
				displayNav('resource', 'Resources');
				?>
				<li><a class="nav-title" href="/timesheet/">Time Sheet</a></li>
				<li><a class="nav-title" href="/contact/">Contact</a></li>
			</ul>
		</nav>
		<div id="content" class="wrap">
		