<?php
/************************************************
* Module: MediaLibrary.inc.php					*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Helper methods used to build library	*
*		   and other related methods			*
*************************************************/

require_once("Main.inc.php");

class MediaLibrary extends Main {
	// Declare variables
	private $php_dynamic_image_file;

	// CSS class names
	private $delete_item_span_name;
	private $library_item_div_name;

	public function __construct($pagename) {
		// "In Main (BaseClass) constructor"
		parent::__construct($pagename);
		$this->php_dynamic_image_file = "PHPImage.inc.php";
		$this->delete_item_span_name = "overlay_delete_item";
		$this->library_item_div_name = "library_item";
	}

	public function determineLibraryId($dbtable) {
		if ($dbtable == "movies" || $dbtable == "music") {
			$id = "M_Id";
			return $id;
		} else if ($dbtable == "books") {
			$id = "B_Id";
			return $id;
		} else {
			return false;
		}
	}

	protected function deleteRecord($id, $table) {
		$tableid = $this->determineLibraryId($table);
		$this->dbConnect();
		$result = $this->doQuery("DELETE FROM ".$table." WHERE ".$tableid." = "
		  .$this->dblink->real_escape_string($id));
		return $result;
		$this->dblink->close();
	}

	// **future feature** create 'general form method' that creates the form HTML (located in Main), child
	  // methods (located in Auth, MediaLibrary, Movies, Books, etc.) will be more specific, and will
	  // inherit and expand upon parent 'general form method'
	/*public function createAddMediaForm() {
		$straddmediaform = "<form id=\"form-addMedia\" name=\"form-addMedia\" method=\"post\" action=\"\">\n";
		$straddmediaform .= "<fieldset>\n";
		$straddmediaform .= "<input id=\"form-addMedia-addRecord\" name=\"form-addMedia-addRecord\" type=\"hidden\" value=\"true\" />\n";
		$straddmediaform .= "<ol>\n<li>\n";
		$straddmediaform .= "<label for=\"form-addMedia-media-type\" form=\"form-addMedia\">Media Type</label><br />\n";
	}*/

	private function createLibraryItem($dbtable) {
		$id = $this->determineLibraryId($dbtable);
		$strlibraryitem = "";
		$this->dbConnect();
		$result = $this->doQuery("SELECT title, ".$id." FROM ".$dbtable." JOIN users ON users.U_Id=".$dbtable.".U_Id WHERE users.U_Id='".$_SESSION['uid']."'");
		if ($result->num_rows > 0) {
			$this->query_num_rows = $result->num_rows;
			while ($row = $result->fetch_assoc()) {
				$strlibraryitem .= "<div class=\"".$this->library_item_div_name."\">\n";
				$strlibraryitem .= "<span class=\"".$this->delete_item_span_name."\"><a href=\"".$this->pagename."?delete=".$row[$id]."\"";
				$strlibraryitem .= " title=\"delete\"><img src=\"images/delete_icon.png\" title=\"delete\" alt=\"delete\" /></a></span>\n";
				$strlibraryitem .= "<a href=\"edit_media.php?library=".$dbtable."&edit=".$row[$id]."\" title=\"".htmlspecialchars($row['title'])."\">";
				$strlibraryitem .= "<img src=\"".$this->php_dynamic_image_file."?h=75&w=100&text=".urlencode($row['title'])."\" alt=\"PHP image\" /></a>\n";
				$strlibraryitem .= "</div>\n";
			}
		}
		$this->dblink->close();
		return $strlibraryitem;
	}

	// **future feature** When library is empty, offer to 'add media' (see below)
	public function buildLibrary($library_type_dbtable) {
		$libraryItem = $this->createLibraryItem($library_type_dbtable);
		echo "<h1>".htmlspecialchars($this->getPageTitle())."</h1>\n";
		if ($this->isTableEmpty()) {
			echo "<section style=\"background-image: none;\" id=\"library\">\n";
		} else {
			echo "<section id=\"library\">\n";
		}
		echo $libraryItem."\n";
		echo "<div class=\"clearfloat\"></div>\n";
		if ($this->isTableEmpty()) {
			echo "<span style=\"display: block; text-align: center; vertical-align: middle\">Your library is empty</span>\n";
			// **future feature** When library is empty, offer to 'add media'
			//echo "<span style=\"background-image: none; display: block; text-align: center; vertical-align: middle\">Would you like to add something to your library?</span>\n";
		} else {
			echo "<span style=\"display: block; text-align: right; padding-right: 5px\">Total ".ucwords($library_type_dbtable)." in Catalog: ".$this->query_num_rows."</span>\n";
		}
		echo "</section>\n";
	}
}
?>