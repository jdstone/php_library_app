<?php
/************************************************
* Module: Main.inc.php							*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Contains generic methods used to		*
*		   build a specific page				*
*************************************************/

require_once("MySQL.inc.php");

class Main extends MySQL {
	// Declare variables
	private $page_title;
	public $pagename;
	private $addmediapage_file;
	private $movielibrarypage_file;
	private $booklibrarypage_file;
	private $musiclibrarypage_file;
	private $loginpage_file;
	private $profilepage_file;
	private $acctsettingspage_file;
	protected $query_num_rows;

	public function __construct($pagename) {
		// "In Main (BaseClass) constructor"
		parent::__construct();
		$this->pagename = $pagename;
		$this->addmediapage_file = "add_media.php";
		$this->movielibrarypage_file = "movie_library.php";
		$this->booklibrarypage_file = "book_library.php";
		$this->musiclibrarypage_file = "music_library.php";
		$this->loginpage_file = "login.php";
		$this->profilepage_file = "javascript:alert('No Page Exists Yet');";
		$this->acctsettingspage_file = "acct_settings.php";
	}

	// 'set' Methods
	public function setPageTitle($page_title) {
		$this->page_title = $page_title;
	}

	// 'get' Methods
	public function getPageTitle() {
		return $this->page_title;
	}

	public function isTableEmpty() {
		if ($this->query_num_rows < 1) {
			return true;
		} else {
			return false;
		}
	}

	// Create navigation bar depending on what page the user is currently at
	public function buildNavBar($selected, $logoutAction) {
		$strnavbar = "<h4>library</h4>\n";
		$strnavbar .= "<nav>\n<ul>\n";
		switch ($selected) {
			case "none":
			  $strnavbar .= "<li><a href=\"".$this->addmediapage_file."\">Add Media</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->movielibrarypage_file."\">Movies</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->musiclibrarypage_file."\">Music</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->booklibrarypage_file."\">Books</a></li>\n";
			  break;
			case "addmedia":
			  $strnavbar .= "<li class=\"selected\">Add Media</li>\n";
			  $strnavbar .= "<li><a href=\"".$this->movielibrarypage_file."\">Movies</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->musiclibrarypage_file."\">Music</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->booklibrarypage_file."\">Books</a></li>\n";
			  break;
			case "movies":
			  $strnavbar .= "<li><a href=\"".$this->addmediapage_file."\">Add Media</a></li>\n";
			  $strnavbar .= "<li class=\"selected\">Movies</li>\n";
			  $strnavbar .= "<li><a href=\"".$this->musiclibrarypage_file."\">Music</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->booklibrarypage_file."\">Books</a></li>\n";
			  break;
			case "books":
			  $strnavbar .= "<li><a href=\"".$this->addmediapage_file."\">Add Media</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->movielibrarypage_file."\">Movies</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->musiclibrarypage_file."\">Music</a></li>\n";
			  $strnavbar .= "<li class=\"selected\">Books</li>\n";
			  break;
			case "music":
			  $strnavbar .= "<li><a href=\"".$this->addmediapage_file."\">Add Media</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->movielibrarypage_file."\">Movies</a></li>\n";
			  $strnavbar .= "<li class=\"selected\">Music</li>\n";
			  $strnavbar .= "<li><a href=\"".$this->booklibrarypage_file."\">Books</a></li>\n";
			  break;
			default:
			  $strnavbar .= "<li><a href=\"".$this->addmediapage_file."\">Add Media</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->movielibrarypage_file."\">Movies</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->musiclibrarypage_file."\">Music</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->booklibrarypage_file."\">Books</a></li>\n";
		}
		$strnavbar .= "</ul>\n</nav>\n";
		$strnavbar .= "<h4>account</h4>\n";
		$strnavbar .= "<nav>\n<ul>\n";
		switch ($selected) {
			case "profile":
			  $strnavbar .= "<li class=\"selected\">Profile</li>\n";
			  $strnavbar .= "<li><a href=\"".$this->acctsettingspage_file."\">Account Settings</a></li>\n";
			  break;
			case "acctsettings":
			  $strnavbar .= "<li><a href=\"".$this->profilepage_file."\">Profile</a></li>\n";
			  $strnavbar .= "<li class=\"selected\">Account Settings</li>\n";
			  break;
			default:
			  $strnavbar .= "<li><a href=\"".$this->profilepage_file."\">Profile</a></li>\n";
			  $strnavbar .= "<li><a href=\"".$this->acctsettingspage_file."\">Account Settings</a></li>\n";
		}
		$strnavbar .= "<li><a href=\"".$logoutAction."\">Log Out</a></li>\n";
		$strnavbar .= "</ul>\n</nav>\n";

		return $strnavbar;
	}

	// Method that creates a link given certain paramaters
	public function createLink($paramname, $paramvalue, $linkname) {
		$strlink = "<a href=\"".$this->pagename."?".$paramname."=";
		$strlink .= $paramvalue."\">".$linkname."</a>";
		return $strlink;
	}

	public function getRecord($query) {
		$result = $this->doQuery($query);
		return $result;
	}

	protected function insertRecord($query) {
		$result = $this->doQuery($query);
		return $result;
	}
}
?>