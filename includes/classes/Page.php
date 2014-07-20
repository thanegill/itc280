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

?>
