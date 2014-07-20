<?php

include('Head.php');

class Page {

	//VARIABLES
	public
	$head, //Head object
	$header,
	$content,
	$footer;
	
	private $openingTag = '<body>';
	private $closingTag = "</body>\n</html>";

	//CONSTRUCTOR
	public function __construct($head, $header, $content, $footer) {
		$this->head = $head;
		$this->header = $header;
		$this->content = $content;
		$this->footer = $footer;
	}

	//METHODS
	public function printPage() {
		$this->head->printHead();
		$begining = array(
			$this->openingTag,
			$this->header,
			$this->content,
			$this->footer,
			$this->closingTag,
		);
		
		echo(implode("\n", $begining));
	}
	
	//PRIVATE METHODS
	private function printHeader() {
		echo('<body>');
	}
}

$meta = array(
	new Meta('Author', 'Thane Gill'),
	new Meta('viewport', 'width=1060')
);

$favicon = new Favicon('icon.png');

$CSS = array(
	new CSS('style.css'),
	new CSS('css.css'),
	new CSS('master.css'),
	new CSS('style2.css')
);

$JS = array(
	new JS('util.js'),
	new JS('', 'alert("hello");'),
	new JS('validate.js')
);

$figlet = '<!--#####################################################
##  	______                           __   ______   ##
##     / ____/___  ____  _ ____  ___    / / /  __   |  ##
##    / __/ / __ \/ __ `/ / __ \/ _ \  / / |  /_/  /   ##
##   / /___/ / / / /_/ / / / / /  __/ / / /  __   /    ##
##  /_____/_/ /_/\__, /_/_/ /_/\___/ / / /  /_/  |     ##
##           /_______/ D E S I G N  /_/  \______/      ##
##                                                     ##
##                 engine18design.com                  ##
##                   copyright 2012                    ##
######################################################-->';

$myHead = new Head('<!DOCTYPE html>', 'My Cool Title', $favicon, $meta, $CSS, $JS, $figlet);

//var_dump($myHeader);

//$myHead->printHeader();

$header = '';

$footer='	</div><!--#content-->
		<footer class="wrap">
			<small id="footer_links">
				<?php displayFooterLinks(); ?>
			 </small><!--footer_links-->
		</footer>
	</div><!--#body_inner-->';


$myPage = new Page($myHead, $header, 'the content', $footer);

$myPage->printPage();

?>
