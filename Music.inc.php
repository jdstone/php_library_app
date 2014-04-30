<?php
/************************************************
* Module: Music.inc.php							*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Music library helper methods.		*
*		   Such as: adding, deleting, editing,	*
*		   and retrieveing music.				*
*************************************************/

require_once("MediaLibrary.inc.php");

class Music extends MediaLibrary {
	// Declare variables
	private $title;
	private $databaseColumn;
	private $musicColumn;

	public function __construct($pagename) {
		// "In Main (BaseClass) constructor"
		parent::__construct($pagename);
		$this->musicColumn = array(
						"title" => "",
						"artist" => "",
						"year" => ""
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

	public function deleteMusicRecord($id) {
		$result = $this->deleteRecord($id, "music");
		return $result;
	}

	public function insertMusicRecord($title, $artist, $year) {
		$this->dbConnect();
		$current_date = date("Y-m-d");
		$music_title = $this->dblink->real_escape_string($title);
		$music_artist = $this->dblink->real_escape_string($artist);
		$music_year = $this->dblink->real_escape_string($year);
		$this->insertRecord("INSERT INTO `music`(`current_date`, `title`, `artist`, `year`, `U_Id`) VALUES('$current_date',
		  '$music_title', '$music_artist', '$music_year', '".$_SESSION['uid']."')");
		$this->dblink->close();
	}

	public function retrieveMusicRecord($id, $editRecordAction) {
		$strmusicrecord = "";
		$this->dbConnect();
		$result = $this->doQuery("SELECT title, artist, year FROM music JOIN users ON users.U_Id=music.U_Id WHERE users.U_Id='".$_SESSION['uid']."' AND M_Id='".$id."'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				foreach ($this->musicColumn as $key => $value) {
					$value = $row[$key];
					$this->musicColumn[$key] = $value;
				}
			}
		}
		$strmusicrecord .= "<h1>".htmlspecialchars($this->musicColumn['title'])."</h1>\n<section id=\"media_item\">\n";
		$strmusicrecord .= "<img src=\"PHPImage.inc.php?h=400&w=500&text=".urlencode($this->musicColumn['title'])."\" />\n";
		$strmusicrecord .= "<div class=\"media_info_container\">\n";
		$strmusicrecord .= "<div class=\"media_info\">\n<ul>\n";
		foreach ($this->musicColumn as $key => $value) {
			$key = str_replace("_", " ", $key);
			$key = ucwords($key);
			if ($value == "0" || $value == "") {
				$strmusicrecord .= "<li><span>".$key.":</span></li>\n";
			} else {
				$strmusicrecord .= "<li><span>".$key.": ".htmlspecialchars($value)."</span></li>\n";
			}
		}
		$this->dblink->close();

		$strmusicrecord .= "</ul>\n</div>\n<div id=\"media_overlay_link\" class=\"media_overlay_item\">\n";
		$strmusicrecord .= "<button id=\"opener\" type=\"button\">edit</button>\n</div>\n";
		$strmusicrecord .= "<div id=\"formbox-dialog-editMusic\" class=\"formbox\" title=\"Edit ".htmlspecialchars($this->musicColumn['title'])."\">\n";
		$strmusicrecord .= "<form id=\"form-dialog-editMedia\" name=\"form-dialog-editMedia\" method=\"post\" onSubmit=\"return false\">\n";
		$strmusicrecord .= "<fieldset>\n";
		$strmusicrecord .= "<input id=\"form-dialog-editMusic-editRecord\" name=\"form-dialog-editMusic-editRecord\" type=\"hidden\" value=\"true\" />\n";
		$strmusicrecord .= "<ol>\n";
		$strmusicrecord .= "<li>\n<label for=\"form-dialog-editMedia-media-type\" form=\"form-dialog-editMedia\">Media Type</label><br />\n";
		$strmusicrecord .= "<input id=\"form-dialog-editMedia-media-type\" name=\"form-dialog-editMedia-media-type\" disabled=\"disabled\" value=\"Music\" type=\"text\" />\n</li>\n";
		$strmusicrecord .= "<li>\n<label for=\"form-dialog-editMedia-music-title\" form=\"form-dialog-editMedia\">Album Title</label><br />\n";
		$strmusicrecord .= "<input id=\"form-dialog-editMedia-music-title\" name=\"form-dialog-editMedia-music-title\" value=\"".htmlspecialchars($this->musicColumn['title'])."\"";
		$strmusicrecord .= " type=\"text\" size=\"50\" maxlength=\"60\" />\n</li>\n";
		$strmusicrecord .= "<li>\n<label for=\"form-dialog-editMedia-music-artist\" form=\"form-dialog-editMedia\">Artist</label><br />\n";
		$strmusicrecord .= "<input id=\"form-dialog-editMedia-music-artist\" name=\"form-dialog-editMedia-music-artist\" value=\"".htmlspecialchars($this->musicColumn['artist'])."\"";
		$strmusicrecord .= " type=\"text\" size=\"50\" maxlength=\"50\" />\n</li>\n";
		$strmusicrecord .= "<li>\n<label for=\"form-dialog-editMedia-music-year\" form=\"form-dialog-editMedia\">Year</label><br />\n";
		$strmusicrecord .= "<input id=\"form-dialog-editMedia-music-year\" name=\"form-dialog-editMedia-music-year\"";
		if ($this->musicColumn['year'] == "0") {
			$strmusicrecord .= " value=\"\"";
		} else {
			$strmusicrecord .= " value=\"".htmlspecialchars($this->musicColumn['year'])."\"";
		}
		$strmusicrecord .= " type=\"text\" size=\"50\" maxlength=\"4\" />\n</li>\n";
		$strmusicrecord .= "<li><input type=\"submit\" style=\"display:none\"></li>";
		$strmusicrecord .= "</ol>\n</fieldset>\n</form>\n</div>\n</div>\n</section>\n";
		return $strmusicrecord;
	}

	public function editMusicRecord($id, $title, $artist, $year) {
		$this->dbConnect();
		$music_title = $this->dblink->real_escape_string($title);
		$music_artist = $this->dblink->real_escape_string($artist);
		$music_year = $this->dblink->real_escape_string($year);
		$this->doQuery("UPDATE `music` SET `title`='$music_title', `artist`='$music_artist', `year`='$music_year' WHERE `M_Id`='$id'");
		$this->dblink->close();
	}
}
?>