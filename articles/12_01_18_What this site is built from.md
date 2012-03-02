Title: What this Site is Built From
Type: resource
Date: 2012-01-18
Time Spent: none

##Blocking IE
This is what I use to block Internet Exploder from loading the site:

	<?php
	   if (eregi("MSIE",getenv("HTTP_USER_AGENT")) ||
	       eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))) {
		Header("Location: http://www.domain.com/ie_reject.html");
		exit;
	   }
	?>

Definitely worth a Read: [Blocking MSIE from your Site](http://www.devin.com/ieblock_howto.shtml).

##PHP Markdown Extra
[PHP Markdown Extra](http://michelf.com/projects/php-markdown/extra/)

This is what parses the markdown files that all the articles/content is written in. It's the best php Markdown parser that if come across. If you don't know what Markdown is read [this](http://daringfireball.net/projects/markdown/). It's the best thing to madden to the web since CSS. Markdown was created by John Gruber.

##Code Highlighter
[Highlight.js](http://softwaremaniacs.org/soft/highlight/en/)

This is what highlights my code that is in my posts. It's the best that I've found, but in no way is it perfect. It mislabels what language the code is. It's not the biggest issue as you can add the language as a class to code like this `<code class="php">`. It will highlight the code in what ever language you put there. The reason I can't do this is that it's quite a workaround to add a class to elements markdown.

##Less CSS

This is one that from your stand point you would never know that I was using it. Less takes CSS closer to a real programing language. It has variables, and function like things called mixins. But the best feature is nesting. Take this as an example:
	
####LESS
	#header {
		h1 {
			font-size: 26px;
			font-weight: bold;
		}
		
		p {
			font-size: 12px;
			
			a {
				text-decoration: none;
				
				&:hover {
					border-width: 1px;
				}
			}
		}
	}
	
####Compiled CSS
	#header h1 {
		font-size: 26px;
		font-weight: bold;
	}
	#header p {
		font-size: 12px;
	}
	#header p a {
		text-decoration: none;
	}
	#header p a:hover {
		border-width: 1px;
	}

Well worth a look at. [Less CSS](http://lesscss.org/)