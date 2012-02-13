Title: Two &#10093;&#10093; Templates
Type: assignment
Date: 2012-01-10
Time Spent: 10

This one was really simple, since I had already made the templates when I created the site.

##Full HTML Template


	<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Time Sheet | ITC 280 | Thane Gill</title>
		<link rel="stylesheet" href="/css/style.css" type="text/css"/>
		
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<!-- Code Highlighter -->
		<script src="/js/highlight.pack.js" type="text/javascript"></script>
		<script type="text/javascript">hljs.tabReplace = '    '; hljs.initHighlightingOnLoad();</script>
		
	</head>
	<body>
	<div id="body_inner">
		<header>
			<div id="site_title"><a href="/">Thane Gill &#10093; ITC 280</a></div>
			<nav>
				<ul>
					<li><a href="/">Blog</a></li>
					<li><a href="/">Assignments</a>
						<ul>
							<li><a href="/posts/?post=assignment-one-this-site">One &#10093;&#10093; This Site</a></li>
							<li><a href="/posts/?post=assignment-two-html-and-php-templates">Two &#10093;&#10093; HTML & PHP Templates</a></li>
						</ul>
					</li>
					<li><a href="/resources/">Resources</a>
						<ul>
							<li><a href="/posts/?post=resource-ui-ux">UI/UX</a></li>
							<li><a href="/posts/?post=resource-typography">Typography</a></li>
							<li><a href="/posts/?post=resource-tools-and-applications">Tools and Applications</a></li>
							<li><a href="/posts/?post=resource-php">PHP</a></li>
							<li><a href="/posts/?post=resource-html">HTML</a></li>
							<li><a href="/posts/?post=resource-css">CSS</a></li>
							<li><a href="/posts/?post=resource-code-standards-and-etiquette">Code Standards & Etiquette</a></li>
							<li><a href="/posts/?post=resource-cms">CMS</a></li>
							<li><a href="/posts/?post=resource-javascript">JavaScript</a></li>
						</ul>
					</li>
					<li><a href="/time_sheet/">Time Sheet</a></li>
					<li><a href="/contact/">Contact Me</a></li>
				</ul>
			</nav>
		</header>
		<div id="content" class="wrap">
			<article>
			
				 <!-- Article Goes Here -->
			
			</article>
	</div><!--#content-->	
		<div id="footer" class="wrap">
			<small id="footer_links">
				<a href="/contact/">Contact Me</a>
				 | <a href="http://twitter.com/#!/thanegill">Follow Me on Twitter</a>
				 | <a href="http://thanegill.com/">thanegill.com</a>
				 | <a href="http://validator.w3.org/check?uri=referer">Validate HTML</a>
				 | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">Validate CSS</a>
			 </small><!--footer_links-->
		</div><!--#footer-->
	</div><!--body_inner-->
	</body>
	</html>

##PHP Template

	<?php
	include('../Post.php');
	
	$posts = getPosts('../posts_content');
	$pageTitle="Template";
	
	include("../includes/head.php");
	?>
	<article>
		 <!-- Article Goes Here -->
	</article>
	<?php
		include("../includes/comment.php");
		include("../includes/footer.php");
	?>

##Post Template

	Title: The Title Goes Here
	Type: assignmnet
	Date: 2012-01-10
	Time Spent: 60
	
	##Header 2
	
	Body of the post.
	