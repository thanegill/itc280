<?php
class Post {

	//VARIABLES
	private $sourceFile = '';
	private $link = '';
	private $title = '';
	private $date = '';
	private $type = '';
	private $body = '';
	private $timeSpent = 0;
	private $comments = array();

	//CONSTRUCTOR
	public function __construct($sourceFile) {
		$this->sourceFile = $sourceFile;
		$this->readFile();
		$this->link = linkText($this->type . ' ' . $this->title);
	}

	//GETTERS
	public function getsourceFile() {
		return($this->sourceFile);
	}
	public function getLink() {
		return($this->link);
	}
	public function getTitle() {
		return($this->title);
	}
	public function getFullTitle() {

		if ($this->type == 'resource') {
			return('Resource &#10093;&#10093; ' . $this->title);
		} elseif ($this->type == 'assignment') {
			return('Assignment ' . $this->title);
		} elseif ($this->type == 'extracredit') {
			return('Extra Credit ' . $this->title);
		} elseif ($this->type == 'article') {
			return($this->title);
		} else {
			return('Type ERROR' . $this->title);
		}
	}
	public function getDate() {
		return($this->date);
	}
	public function getDatePretty() {
		return(date('l\, F jS Y', strtotime($this->date)));
	}
	public function getType() {
		return($this->type);
	}
	public function getBody() {
		return($this->body);
	}
	public function getTimeSpent() {
		return($this->timeSpent);
	}
	public function getComments() {
		return($this->comments);
	}

	//SETTERS
	public function setsourceFile($sourceFile) {
		$this->sourceFile = $sourceFile;
	}
	public function setLink($link) {
		$this->link = $link;
	}
	public function setTitle($title) {
		$this->title = $title;
	}
	public function setDate($date) {
		$this->date = $date;
	}
	public function setType($type) {
		$this->type = $type;
	}
	public function setBody($body) {
		$this->body = $body;
	}
	public function setTimeSpent($timeSpent) {
		$this->timeSpent = $timeSpent;
	}
	public function setComments($comments) {
		$this->comments = $comments;
	}

	//METHODS
	private function readFile() {
		$handle = fopen($this->sourceFile, "r");

		$header = array();
		$i = 0;
		while (!feof($handle) && $i <= 4) {
			$text = rtrim(fgets($handle, 4096));
			$header[$i] = substr($text, strrpos($text, ': ') + 2, strlen($text));
			$i++;
		}

		$this->title = $header[0];
		$this->type = strtolower(str_replace(' ', '', $header[1])); //To lower case & remove spaces
		$this->date = $header[2];
		$this->timeSpent = $header[3];

		while (!feof($handle)) {
		   $this->body .= fgets($handle, 4096);
		}

		fclose($handle);
	}


	//Display Articles
	function displayArticle() {

		//Should I display $timeSpent?
		if (!isset($this->timeSpent) || $this->timeSpent == null || $this->timeSpent == "" || $this->timeSpent == 'none' || $this->timeSpent == 0) {
			$timeSpentWord = "";
		} else {
			$timeSpentWord = " - Time Spent: " . displayTimeSpent($this->timeSpent);
		}

		//Pluralization of "comments"
		if (sizeof($this->comments) == 1) {
			$commentWord = "Comment";
		} else {
			$commentWord = "Comments";
		}

		echo('<article>
		<h1><a href="/?a=' . $this->link .'">' . $this->getFullTitle() . '</a></h1>
		' . Markdown($this->body). '
		<span class="date-comments">' . date('l\, F jS Y', strtotime($this->date)) . $timeSpentWord . ' - <a href="/?a=' . $this->link . '#comments">' . sizeof($this->comments) . ' ' . $commentWord . '</a></span>
		</article>');

	}

	//Display Comments
	function displayComments() {

		if (sizeof($this->comments) > 0) {
			echo("<div id=\"comments\"><h2>Comments</h2>");

			foreach ($this->comments as $name => $comment) {
				echo("<div class=\"article-comment\"><h4>" . $name . ":</h4>". $comment . "</div>");
			}

			echo("</div>");
		}
	}

}

?>
