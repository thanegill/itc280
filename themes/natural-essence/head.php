<?php echor(DOCTYPE); ?>
<html>
<head>
	<meta name="description" content=""/>
	<meta name="keywords" content=""/>
	<meta name="author" content=""/>
	<title><?php echo($pageTitle . TITLE_TAG_END); ?></title>
	<?php 
		displayCSS(); 
	 	displayJS();
		displayShim();
	?>
	
</head>

<body>
	<div id="wrap">
		
		<div id="site-title">
			<a href="/"><?php echo(SITE_NAME) ?></a>
		</div>
	
		<nav>
			<ul>
				<li><a class="nav-title" href="/">Blog</a></li>
				<li><a class="nav-title" href="/posts/?t=assignment">Assignmnets</a></li>
				<li><a class="nav-title" href="/posts/?t=extracredit">Extra Credit</a></li>
				<li><a class="nav-title" href="/posts/?t=resource">Resources</a></li>
				<li><a class="nav-title" href="/timesheet/">Time Sheet</a></li>
				<li><a class="nav-title" href="/contact/">Contact</a></li>
			</ul>
		</nav>
	
		<div class="main">
			<div id="content">