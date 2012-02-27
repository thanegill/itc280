<!DOCTYPE html>
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
		