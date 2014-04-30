<?php
/************************************************
* Module: Books.inc.php							*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Book library helper methods.			*
*		   Such as: adding, deleting, editing,	*
*		   and retrieveing books.				*
*************************************************/

require_once("MediaLibrary.inc.php");

class Books extends MediaLibrary {
	// Declare variables
	private $title;
	private $databaseColumn;
	private $bookColumn;

	public function __construct($pagename) {
		// "In Main (BaseClass) constructor"
		parent::__construct($pagename);
		$this->bookColumn = array(
						"title" => "",
						"author" => "",
						"publisher" => ""
						);
	}

	// 'set' Methods
	private function setTitle($title) {
		$this->title = $title;
	}

	// 'get' Methods
	public function getTitle() {
		return $this->title;
	}

	public function deleteBookRecord($id) {
		$result = $this->deleteRecord($id, "books");
		return $result;
	}

	public function insertBookRecord($title, $author, $publisher) {
		$this->dbConnect();
		$current_date = date("Y-m-d");
		$book_title = $this->dblink->real_escape_string($title);
		$book_author = $this->dblink->real_escape_string($author);
		$book_publisher = $this->dblink->real_escape_string($publisher);
		$this->insertRecord("INSERT INTO `books`(`current_date`, `title`, `author`, `publisher`, `U_Id`) VALUES('$current_date',
		  '$book_title', '$book_author', '$book_publisher', '".$_SESSION['uid']."')");
		$this->dblink->close();
	}

	public function retrieveBookRecord($id, $editRecordAction) {
		$strbookrecord = "";
		$this->dbConnect();
		$result = $this->doQuery("SELECT title, author, publisher FROM books JOIN users ON users.U_Id=books.U_Id WHERE users.U_Id='".$_SESSION['uid']."' AND B_Id='".$id."'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				foreach ($this->bookColumn as $key => $value) {
					$value = $row[$key];
					$this->bookColumn[$key] = $value;
				}
			}
		}
		$strbookrecord .= "<h1>".htmlspecialchars($this->bookColumn['title']);
		if ($this->bookColumn['author'] == "") {
			$strbookrecord .= "</h1>\n<section id=\"media_item\">\n";
		} else {
			$strbookrecord .= "<br />by ".htmlspecialchars($this->bookColumn['author'])."</h1>\n<section id=\"media_item\">\n";
		}
		$strbookrecord .= "<img src=\"PHPImage.inc.php?h=400&w=500&text=".urlencode($this->bookColumn['title'])."\" />\n";
		$strbookrecord .= "<div class=\"media_info_container\">\n";
		$strbookrecord .= "<div class=\"media_info\">\n<ul>\n";
		foreach ($this->bookColumn as $key => $value) {
			$key = str_replace("_", " ", $key);
			$key = ucwords($key);
			if ($value == "") {
				$strbookrecord .= "<li><span>".$key.":</span></li>\n";
			} else {
				$strbookrecord .= "<li><span>".$key.": ".htmlspecialchars($value)."</span></li>\n";
			}
		}
		$this->dblink->close();

		$strbookrecord .= "</ul>\n</div>\n<div id=\"media_overlay_link\" class=\"media_overlay_item\">\n";
		$strbookrecord .= "<button id=\"opener\" type=\"button\">edit</button>\n</div>\n";
		$strbookrecord .= "<div id=\"formbox-dialog-editBooks\" class=\"formbox\" title=\"Edit ".htmlspecialchars($this->bookColumn['title'])."\">\n";
		$strbookrecord .= "<form id=\"form-dialog-editMedia\" name=\"form-dialog-editMedia\" method=\"post\" onSubmit=\"return false\">\n";
		$strbookrecord .= "<fieldset>\n";
		$strbookrecord .= "<input id=\"form-dialog-editBook-editRecord\" name=\"form-dialog-editBook-editRecord\" type=\"hidden\" value=\"true\" />\n";
		$strbookrecord .= "<ol>\n";
		$strbookrecord .= "<li>\n<label for=\"form-dialog-editMedia-media-type\" form=\"form-dialog-editMedia\">Media Type</label><br />\n";
		$strbookrecord .= "<input id=\"form-dialog-editMedia-media-type\" name=\"form-dialog-editMedia-media-type\" disabled=\"disabled\" value=\"Book\" type=\"text\" />\n</li>\n";
		$strbookrecord .= "<li>\n<label for=\"form-dialog-editMedia-book-title\" form=\"form-dialog-editMedia\">Album Title</label><br />\n";
		$strbookrecord .= "<input id=\"form-dialog-editMedia-book-title\" name=\"form-dialog-editMedia-book-title\" value=\"".htmlspecialchars($this->bookColumn['title'])."\"";
		$strbookrecord .= " type=\"text\" size=\"50\" maxlength=\"60\" />\n</li>\n";
		$strbookrecord .= "<li>\n<label for=\"form-dialog-editMedia-book-author\" form=\"form-dialog-editMedia\">Author</label><br />\n";
		$strbookrecord .= "<input id=\"form-dialog-editMedia-book-author\" name=\"form-dialog-editMedia-book-author\" value=\"".htmlspecialchars($this->bookColumn['author'])."\"";
		$strbookrecord .= " type=\"text\" size=\"50\" maxlength=\"40\" />\n</li>\n";
		$strbookrecord .= "<li>\n<label for=\"form-dialog-editMedia-book-publisher\" form=\"form-dialog-editMedia\">Publisher</label><br />\n";
		$strbookrecord .= "<input id=\"form-dialog-editMedia-book-publisher\" name=\"form-dialog-editMedia-book-publisher\" value=\"".htmlspecialchars($this->bookColumn['publisher'])."\"";
		$strbookrecord .= " type=\"text\" size=\"50\" maxlength=\"50\" />\n</li>\n";
		$strbookrecord .= "<li><input type=\"submit\" style=\"display:none\"></li>";
		$strbookrecord .= "</ol>\n</fieldset>\n</form>\n</div>\n</div>\n</section>\n";
		return $strbookrecord;
	}

	public function editBookRecord($id, $title, $author, $publisher) {
		$this->dbConnect();
		$book_title = $this->dblink->real_escape_string($title);
		$book_author = $this->dblink->real_escape_string($author);
		$book_publisher = $this->dblink->real_escape_string($publisher);
		$this->doQuery("UPDATE `books` SET `title`='$book_title', `author`='$book_author', `publisher`='$book_publisher' WHERE `B_Id`='$id'");
		$this->dblink->close();
	}
}
?>