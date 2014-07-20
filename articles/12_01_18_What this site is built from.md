Title: What this Site is Built From
Type: resource
Date: 2012-01-18
Time Spent: none

##Blocking IE

	<?php
	   if (eregi("MSIE",getenv("HTTP_USER_AGENT")) ||
	       eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))) {
		Header("Location: http://www.domain.com/ie_reject.html");
		exit;
	   }
	?>

[Blocking MSIE from your Site](http://www.devin.com/ieblock_howto.shtml).

##PHP Markdown Extra
[PHP Markdown Extra](http://michelf.com/projects/php-markdown/extra/)

Parses markdown files that all the articles/content are written in. It's the best php Markdown parser that i've come across. If you don't know what Markdown is read [this](http://daringfireball.net/projects/markdown/).

##Code Highlighter
[Highlight.js](http://softwaremaniacs.org/soft/highlight/en/)

Highlights code that is in my posts. It mislabels what language the code is. It's not the biggest issue as you can add the language as a class to code like this `<code class="php">`. The reason I can't do this is that it's quite a workaround to add a class to elements markdown.

##Less CSS

[Less CSS](http://lesscss.org/)
