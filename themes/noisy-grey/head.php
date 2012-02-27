<?php echor(DOCTYPE); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo($pageTitle . TITLE_TAG_END); ?></title>
	<?php 
		displayCSS(); 
		displayJS();
	?>
	
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
		
</head>
<body>
    <div id="body_inner">
    	<header>
    		<div id="site_title"><a href="/"><?php echo(SITE_NAME) ?></a></div>
    		<nav>
    			<ul>
    				<li><a href="/">Blog</a></li>
    				<?php
    				displayNav('assignment', 'Assignmnets');
    				displayNav('extracredit', 'Extra Credit');
    				displayNav('resource', 'Resources');
    				?>
    				<li><a href="/timesheet/">Time Sheet</a></li>
    				<li><a href="/contact/">Contact</a></li>
    			</ul>
    		</nav>
    	</header>
    	<div id="content" class="wrap">