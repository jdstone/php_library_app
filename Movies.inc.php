<?php
/************************************************
* Module: Movies.inc.php						*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Movie library helper methods.		*
*		   Such as: adding, deleting, editing,	*
*		   and retrieveing movies.				*
*************************************************/

require_once("MediaLibrary.inc.php");

class Movies extends MediaLibrary {
	// Declare variables
	private $title;
	private $actors;
	private $rating;
	private $format;
	private $description;
	private $release_year;
	private $databaseColumn;
	private $movieColumn;
	private $movievar;

	// CSS class names
	private $divwrappername = "content";

	public function __construct($pagename) {
		// "In Main (BaseClass) constructor"
		parent::__construct($pagename);
		$this->movieColumn = array(
						"title" => "",
						"year" => "",
						"rating" => "",
						"aspect_ratio" => "",
						"director" => "",
						"runtime" => "",
						"language" => ""
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

	public function deleteMoviesRecord($id) {
		$result = $this->deleteRecord($id, "movies");
		return $result;
	}

	public function insertMovieRecord($title, $year, $rating, $aspect_ratio, $director, $runtime,
	  $language) {
		$this->dbConnect();
		$current_date = date("Y-m-d");
		$movie_title = $this->dblink->real_escape_string($title);
		$movie_year = $this->dblink->real_escape_string($year);
		$movie_rating = $this->dblink->real_escape_string($rating);
		if ($aspect_ratio == "Select...") {
			$aspect_ratio = "";
			$movie_aspect_ratio = $aspect_ratio;
		} else {
			$movie_aspect_ratio = $this->dblink->real_escape_string($aspect_ratio);
		}
		$movie_director = $this->dblink->real_escape_string($director);
		$movie_runtime = $this->dblink->real_escape_string($runtime);
		$movie_language = $this->dblink->real_escape_string($language);
		$this->insertRecord("INSERT INTO `movies`(`current_date`, `title`, `year`, `rating`,
		  `aspect_ratio`, `director`, `runtime`, `language`, `U_Id`) VALUES('$current_date',
		  '$movie_title', '$movie_year', '$movie_rating', '$movie_aspect_ratio',
		  '$movie_director', '$movie_runtime', '$movie_language', '".$_SESSION['uid']."')");
		$this->dblink->close();
	}

	public function retrieveMovieRecord($id, $editRecordAction) {
		$strmovierecord = "";
		$this->dbConnect();
		$result = $this->doQuery("SELECT title, year, rating, aspect_ratio, director, runtime, language
		  FROM movies JOIN users ON users.U_Id=movies.U_Id
		  WHERE users.U_Id='".$_SESSION['uid']."' AND M_Id='".$id."'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				foreach ($this->movieColumn as $key => $value) {
					$value = $row[$key];
					$this->movieColumn[$key] = $value;
				}
			}
		}
		$strmovierecord .= "<h1>".htmlspecialchars($this->movieColumn['title']);
		if ($this->movieColumn['year'] == "0") {
			$strmovierecord .= "</h1>\n<section id=\"media_item\">\n";
		} else {
			$strmovierecord .= " (".htmlspecialchars($this->movieColumn['year']).")</h1>\n<section id=\"media_item\">\n";
		}
		$strmovierecord .= "<img src=\"PHPImage.inc.php?h=400&w=500&text=".urlencode($this->movieColumn['title'])."\" />\n";
		$strmovierecord .= "<div class=\"media_info_container\">\n";
		$strmovierecord .= "<div class=\"media_info\">\n<ul>\n";
		foreach ($this->movieColumn as $key => $value) {
			$key = str_replace("_", " ", $key);
			$key = ucwords($key);
			if ($value == "0" || $value == "") {
				$strmovierecord .= "<li><span>".$key.":</span></li>\n";
			} else {
				$strmovierecord .= "<li><span>".$key.": ".htmlspecialchars($value)."</span></li>\n";
			}
		}
		$this->dblink->close();

		$strmovierecord .= "</ul>\n</div>\n<div id=\"media_overlay_link\" class=\"media_overlay_item\">\n";
		$strmovierecord .= "<button id=\"opener\" type=\"button\">edit</button>\n</div>\n";
		$strmovierecord .= "<div id=\"formbox-dialog-editMovies\" class=\"formbox\" title=\"Edit ".htmlspecialchars($this->movieColumn['title'])."\">\n";
		$strmovierecord .= "<form id=\"form-dialog-editMedia\" name=\"form-dialog-editMedia\" method=\"post\" onSubmit=\"return false\">\n";
		$strmovierecord .= "<fieldset>\n";
		$strmovierecord .= "<input id=\"form-dialog-editMovie-editRecord\" name=\"form-dialog-editMovie-editRecord\" type=\"hidden\" value=\"true\" />\n";
		$strmovierecord .= "<ol>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-media-type\" form=\"form-dialog-editMedia\">Media Type</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-media-type\" name=\"form-dialog-editMedia-media-type\" disabled=\"disabled\" value=\"Movie\" type=\"text\" />\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-title\" form=\"form-dialog-editMedia\">Title</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-movie-title\" name=\"form-dialog-editMedia-movie-title\" value=\"".htmlspecialchars($this->movieColumn['title'])."\"";
		$strmovierecord .= " type=\"text\" size=\"50\" maxlength=\"60\" />\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-year\" form=\"form-dialog-editMedia\">Year Released</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-movie-year\" name=\"form-dialog-editMedia-movie-year\"";
		if ($this->movieColumn['year'] == "0") {
			$strmovierecord .= " value=\"\"";
		} else {
			$strmovierecord .= " value=\"".htmlspecialchars($this->movieColumn['year'])."\"";
		}
		$strmovierecord .= " type=\"text\" size=\"50\" maxlength=\"4\" />\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-rating\" form=\"form-dialog-editMedia\">Rating</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-movie-rating\" name=\"form-dialog-editMedia-movie-rating\" value=\"".htmlspecialchars($this->movieColumn['rating'])."\"";
		$strmovierecord .= " type=\"text\" size=\"50\" maxlength=\"5\" />\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-aspect-ratio\" form=\"form-dialog-editMedia\">Aspect Ratio</label><br />\n";
		$strmovierecord .= "<select id=\"form-dialog-editMedia-movie-aspect-ratio\" name=\"form-dialog-editMedia-aspect-ratio\">\n";
		if ($this->movieColumn['aspect_ratio'] == "Widescreen") {
			$strmovierecord .= "<option id=\"form-dialog-editMedia-ar-select\">Select...</option>\n<option id=\"form-dialog-editMedia-ar-ws\" selected=\"selected\" value=\"Widescreen\">";
			$strmovierecord .= "Widescreen</option>\n<option id=\"form-dialog-editMedia-ar-fs\" value=\"Fullscreen\">Fullscreen</option>\n";
		} else if ($this->movieColumn['aspect_ratio'] == "Fullscreen") {
			$strmovierecord .= "<option id=\"form-dialog-editMedia-ar-select\">Select...</option>\n<option id=\"form-dialog-editMedia-ar-ws\" value=\"";
			$strmovierecord .= "Widescreen\">Widescreen</option>\n<option id=\"form-dialog-editMedia-ar-fs\" selected=\"selected\" value=\"Fullscreen\">Fullscreen</option>\n";
		} else {
			$strmovierecord .= "<option id=\"form-dialog-editMedia-ar-select\" selected=\"selected\">Select...</option>\n<option id=\"form-dialog-editMedia-ar-ws\" value=\"";
			$strmovierecrod .= "Widescreen\">Widescreen</option>\n<option id=\"form-dialog-editMedia-ar-fs\" value=\"Fullscreen\">Fullscreen</option>\n";
		}
		$strmovierecord .= "</select>\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-director\" form=\"form-dialog-editMedia\">Director(s)</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-movie-director\" name=\"form-dialog-editMedia-movie-director\" value=\"".htmlspecialchars($this->movieColumn['director'])."\"";
		$strmovierecord .= " type=\"text\" size=\"50\" maxlength=\"50\" />\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-runtime\" form=\"form-dialog-editMedia\">Runtime</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-movie-runtime\" name=\"form-dialog-editMedia-movie-runtime\"";
		if ($this->movieColumn['runtime'] == "0") {
			$strmovierecord .= " value=\"\"";
		} else {
			$strmovierecord .= " value=\"".htmlspecialchars($this->movieColumn['runtime'])."\"";
		}
		$strmovierecord .= " type=\"text\" size=\"50\" maxlength=\"3\" /> min.\n</li>\n";
		$strmovierecord .= "<li>\n<label for=\"form-dialog-editMedia-movie-language\" form=\"form-dialog-editMedia\">Language</label><br />\n";
		$strmovierecord .= "<input id=\"form-dialog-editMedia-movie-language\" name=\"form-dialog-editMedia-movie-language\" value=\"".htmlspecialchars($this->movieColumn['language'])."\"";
		$strmovierecord .= " type=\"text\" size=\"50\" maxlength=\"12\" />\n</li>\n";
		$strmovierecord .= "<li><input type=\"submit\" style=\"display:none\"></li>";
		$strmovierecord .= "</ol>\n</fieldset>\n</form>\n</div>\n</div>\n</section>\n";
		return $strmovierecord;
	}

	public function editMovieRecord($id, $title, $year, $rating, $aspect_ratio, $director, $runtime,
	  $language) {
		$this->dbConnect();
		$movie_title = $this->dblink->real_escape_string($title);
		$movie_year = $this->dblink->real_escape_string($year);
		$movie_rating = $this->dblink->real_escape_string($rating);
		if ($aspect_ratio == "Select...") {
			$aspect_ratio = "";
			$movie_aspect_ratio = $this->dblink->real_escape_string($aspect_ratio);
		} else {
			$movie_aspect_ratio = $this->dblink->real_escape_string($aspect_ratio);
		}
		$movie_director = $this->dblink->real_escape_string($director);
		$movie_runtime = $this->dblink->real_escape_string($runtime);
		$movie_language = $this->dblink->real_escape_string($language);
		$this->doQuery("UPDATE `movies` SET `title`='$movie_title', `year`='$movie_year', `rating`='$movie_rating', `aspect_ratio`='$movie_aspect_ratio',
		  `director`='$movie_director', `runtime`='$movie_runtime', `language`='$movie_language' WHERE `M_Id`='$id'");
		$this->dblink->close();
	}
}
?>