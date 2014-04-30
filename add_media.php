<?php
/************************************************
* Module: add_media.php							*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Allows user to add new media (books, *
*		   movies, music) to their library		*
*************************************************/

require_once("Auth.inc.php");
require_once("Movies.inc.php");
require_once("Music.inc.php");
require_once("Books.inc.php");
$pagename = "";
$auth = new Auth();
$movies = new Movies($pagename);
$music = new Music($pagename);
$books = new Books($pagename);

// Initialize the session
if (!isset($_SESSION)) {
	session_start();
}

// Logout the current user
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
	$logoutAction .= "&".htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
	$auth->doLogout();
}

// Restrict Access To Page: Grant or deny access to specified page
$authorizedUsers = "3";
$donotCheckaccess = "false";
$auth->checkAccess($authorizedUsers);

// Insert into database
$insertRecordAction = $_SERVER['PHP_SELF'];
if ((isset($_POST['form-addMovie-insertRecord'])) && ($_POST['form-addMovie-insertRecord'] == "true")) {
	$movies->insertMovieRecord($_POST['form-addMovie-movie-title'], $_POST['form-addMovie-movie-year'],
	  $_POST['form-addMovie-movie-rating'], $_POST['form-addMovie-movie-aspect-ratio'],
	  $_POST['form-addMovie-movie-director'], $_POST['form-addMovie-movie-runtime'],
	  $_POST['form-addMovie-movie-language']);
}
if ((isset($_POST['form-addMusic-insertRecord'])) && ($_POST['form-addMusic-insertRecord'] == "true")) {
	$music->insertMusicRecord($_POST['form-addMusic-music-title'], $_POST['form-addMusic-music-artist'],
	  $_POST['form-addMusic-music-year']);
}
if ((isset($_POST['form-addBook-insertRecord'])) && ($_POST['form-addBook-insertRecord'] == "true")) {
	$books->insertBookRecord($_POST['form-addBook-book-title'], $_POST['form-addBook-book-author'],
	  $_POST['form-addBook-book-publisher']);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>StoneLibrary - Add Media</title>
<link type="text/css" href="css/main.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
<script type="text/javascript">
// switch from Movie form to Music or Book form
function switchMovie() {
  $(document).ready(function() {
	$("#addMovie").css("display","none");
		var x=document.getElementById("form-addMovie-media-type").selectedIndex;
		var y=document.getElementById("form-addMovie-media-type").options;
		if (y[x].text == "Music") {
			$("#addMusic").slideDown("fast");
			$('select[name=form-addMusic-media-type] option[value=music]').attr('selected', true);
		} else if (y[x].text == "Book") {
			$("#addBook").slideDown("fast");
			$('select[name=form-addBook-media-type] option[value=book]').attr('selected', true);
		}
});
}

// switch from Music form to Movie or Book form
function switchMusic() {
  $(document).ready(function() {
	$("#addMusic").css("display","none");
		var x=document.getElementById("form-addMusic-media-type").selectedIndex;
		var y=document.getElementById("form-addMusic-media-type").options;
		if (y[x].text == "Movie") {
			$("#addMovie").slideDown("fast");
			$('select[name=form-addMovie-media-type] option[value=movie]').attr('selected', true);
		} else if (y[x].text == "Book") {
			$("#addBook").slideDown("fast");
			$('select[name=form-addBook-media-type] option[value=book]').attr('selected', true);
		}
});
}

// switch from Book form to Movie or Music form
function switchBook() {
  $(document).ready(function() {
	$("#addBook").css("display","none");
		var x=document.getElementById("form-addBook-media-type").selectedIndex;
		var y=document.getElementById("form-addBook-media-type").options;
		if (y[x].text == "Movie") {
			$("#addMovie").slideDown("fast");
			$('select[name=form-addMovie-media-type] option[value=movie]').attr('selected', true);
		} else if (y[x].text == "Music") {
			$("#addMusic").slideDown("fast");
			$('select[name=form-addMusic-media-type] option[value=music]').attr('selected', true);
		}
});
}

$(document).ready(function() {
	// validate addMovie form on keyup and submit
	var validator = $("#form-addMovie").validate({ 
		rules: {
			"form-addMovie-movie-title": "required",
			"form-addMovie-movie-year": {
				digits: true,
				minlength: 4,
				maxlength: 4
			},
			"form-addMovie-movie-runtime": {
				digits: true,
				maxlength: 3
			}
		},
		messages: {
			"form-addMovie-movie-title": "Please enter a title",
			"form-addMovie-movie-year": {
				digits: "Please enter only numbers",
				minlength: "Please enter in YYYY format",
				maxlength: "Please enter in YYYY format"
			},
			"form-addMovie-movie-runtime": {
				digits: "Please enter only numbers",
				maxlength: "Please enter only numbers"
			}
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.appendTo( element.parent("td").next("td") );
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
});

$(document).ready(function() {
	// validate addMusic form on keyup and submit
	var validator = $("#form-addMusic").validate({ 
		rules: {
			"form-addMusic-music-title": "required",
			"form-addMusic-music-year": {
				digits: true,
				minlength: 4,
				maxlength: 4
			}
		},
		messages: {
			"form-addMusic-music-title": "Please enter an album title",
			"form-addMusic-music-year": {
				digits: "Please enter only numbers",
				minlength: "Please enter in YYYY format",
				maxlength: "Please enter in YYYY format"
			}
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.appendTo( element.parent("td").next("td") );
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
});

$(document).ready(function() {
	// validate addBook form on keyup and submit
	var validator = $("#form-addBook").validate({ 
		rules: {
			"form-addBook-book-title": "required"
		},
		messages: {
			"form-addBook-book-title": "Please enter a title"
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.appendTo( element.parent("td").next("td") );
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
});
</script>

<style type="text/css">
<!--
.sidebar1 {
	float: left;
	width: 180px;
	padding-bottom: 10px;
	background-color: #ABBEDC;
	height: 601px;
}
.content ul, .content ol {
	padding: 0 15px 15px 40px;
}
#formbox-addMedia {
	margin-left: 100px;
	width: 350px;
}
#formbox-addMedia table {
	border-spacing: 0;
	border-collapse: collapse;
	empty-cells: show;
}
#formbox-addMedia .label {
	padding-top: 2px;
	padding-right: 8px;
	vertical-align: top;
	text-align: right;
	width: 125px;
	white-space: nowrap;
}
#formbox-addMedia .field {
	padding-bottom: 10px;
	white-space: nowrap;
}
#formbox-addMedia .status {
	padding-top: 2px;
	padding-left: 8px;
	vertical-align: top;
	width: 246px;
	white-space: nowrap;
}
#formbox-addMedia label.error {
	background:url("images/unchecked.gif") no-repeat 0px 0px;
	padding-left: 16px;
	padding-bottom: 2px;
	font-weight: bold;
	color: #F00;
}
#formbox-addMedia label.checked {
	background:url("images/checked.gif") no-repeat 0px 0px;
}
#formbox-addMedia fieldset {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
#formbox-addMedia .success_msg {
	font-weight: bold;
	color: #0060BF;
	margin-left: 19px;
}
-->
</style>
</head>

<body>
<div class="container">
  <div class="sidebar1">
	<?php echo $movies->buildNavBar("addmedia", $logoutAction); ?>
  </div>
  <div class="content">
	<h1>add media</h1>
	<section id="formbox-addMedia">
	  <div id="addMovie">
		<form id="form-addMovie" name="form-addMovie" method="post" action="<?php echo $insertRecordAction; ?>">
		  <fieldset>
			<input id="form-addMovie-insertRecord" name="form-addMovie-insertRecord" type="hidden" value="true" />
			<table>
			  <tbody>
				<tr>
				  <td class="label"><label for="form-addMovie-media-type" form="form-addMovie">Media Type</label></td>
				  <td class="field"><select id="form-addMovie-media-type" name="form-addMovie-media-type" onChange="switchMovie()">
					<option id="form-addMovie-mt-movie" value="movie" selected="selected">Movie</option>
					<option id="form-addMovie-mt-music" value="music">Music</option>
					<option id="form-addMovie-mt-book" value="book">Book</option>
				  </select></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-title" form="form-addMovie">Title</label></td>
				  <td class="field"><input id="form-addMovie-movie-title" name="form-addMovie-movie-title" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-year" form="form-addMovie">Year Released</label></td>
				  <td class="field"><input id="form-addMovie-movie-year" name="form-addMovie-movie-year" type="text" maxlength="4" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-rating" form="form-addMovie">Rating</label></td>
				  <td class="field"><input id="form-addMovie-movie-rating" name="form-addMovie-movie-rating" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-aspect-ratio" form="form-addMovie">Aspect Ratio</label></td>
				  <td class="field"><select id="form-addMovie-movie-aspect-ratio" name="form-addMovie-movie-aspect-ratio">
					<option id="form-addMovie-ar-select" selected="selected">Select...</option>
					<option id="form-addMovie-ar-ws" value="Widescreen">Widescreen (16:9)</option>
					<option id="form-addMovie-ar-fs" value="Fullscreen">Fullscreen (4:3)</option>
				  </select></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-director" form="form-addMovie">Director(s)</label></td>
				  <td class="field"><input id="form-addMovie-movie-director" name="form-addMovie-movie-director" type="text" /><span style="font-size: small;"> (separate by commas)</span></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-runtime" form="form-addMovie">Runtime</label></td>
				  <td class="field"><input id="form-addMovie-movie-runtime" name="form-addMovie-movie-runtime" type="text" maxlength="3" /><span style="font-size: small;"> min.</span></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-movie-language" form="form-addMovie">Language</label></td>
				  <td class="field"><input id="form-addMovie-movie-language" name="form-addMovie-movie-language" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMovie-add-movie-button" form="form-addMovie" style="visibility: hidden;">Add Movie</label></td>
				  <td colspan="2" class="field">
					<input id="form-addMovie-add-movie-button" name="form-addMovie-add-movie-button" type="submit" value="Add Movie" />&nbsp;
					<input id="form-addMovie-clear-button" name="form-addMovie-clear-button" type="reset" value="Clear" />
				  </td>
				</tr>
			  </tbody>
			</table>
		  </fieldset>
		</form>
	  </div>

	  <div id="addMusic" style="display: none;">
		<form id="form-addMusic" name="form-addMusic" method="post" action="<?php echo $insertRecordAction; ?>">
		  <fieldset>
			<input id="form-addMusic-insertRecord" name="form-addMusic-insertRecord" type="hidden" value="true" />
			<table>
			  <tbody>
				<tr>
				  <td class="label"><label for="form-addMusic-media-type" form="form-addMusic">Media Type</label></td>
				  <td class="field"><select id="form-addMusic-media-type" name="form-addMusic-media-type" onChange="switchMusic()">
					<option id="form-addMusic-mt-movie" value="movie">Movie</option>
					<option id="form-addMusic-mt-music" value="music" selected="selected">Music</option>
					<option id="form-addMusic-mt-book" value="book">Book</option>
				  </select></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMusic-music-title" form="form-addMusic">Album Title</label></td>
				  <td class="field"><input id="form-addMusic-music-title" name="form-addMusic-music-title" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMusic-music-artist" form="form-addMusic">Artist</label></td>
				  <td class="field"><input id="form-addMusic-music-artist" name="form-addMusic-music-artist" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMusic-music-year" form="form-addMusic">Year</label></td>
				  <td class="field"><input id="form-addMusic-music-year" name="form-addMusic-music-year" type="text" maxlength="4" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addMusic-add-music-button" form="form-addMusic" style="visibility: hidden;">Add Music</label></td>
				  <td colspan="2" class="field">
					<input id="form-addMusic-add-music-button" name="form-addMusic-add-music-button" type="submit" value="Add Music" />&nbsp;
					<input id="form-addMusic-clear-button" name="form-addMusic-clear-button" type="reset" value="Clear" />
				  </td>
				</tr>
			  </tbody>
			</table>
		  </fieldset>
		</form>
	  </div>

	  <div id="addBook" style="display: none;">
		<form id="form-addBook" name="form-addBook" method="post" action="<?php echo $insertRecordAction; ?>">
		  <fieldset>
			<input id="form-addBook-insertRecord" name="form-addBook-insertRecord" type="hidden" value="true" />
			<table>
			  <tbody>
				<tr>
				  <td class="label"><label for="form-addBook-media-type" form="form-addBook">Media Type</label></td>
				  <td class="field"><select id="form-addBook-media-type" name="form-addBook-media-type" onChange="switchBook()">
					<option id="form-addBook-mt-movie" value="movie">Movie</option>
					<option id="form-addBook-mt-music" value="music">Music</option>
					<option id="form-addBook-mt-book" value="book" selected="selected">Book</option>
				  </select></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addBook-book-title" form="form-addBook">Title</label></td>
				  <td class="field"><input id="form-addBook-book-title" name="form-addBook-book-title" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addBook-book-author" form="form-addBook">Author(s)</label></td>
				  <td class="field"><input id="form-addBook-book-author" name="form-addBook-book-author" type="text" /><span style="font-size: small;"> (separate by commas)</span></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addBook-book-publisher" form="form-addBook">Publisher</label></td>
				  <td class="field"><input id="form-addBook-book-publisher" name="form-addBook-book-publisher" type="text" /></td>
				  <td class="status"></td>
				</tr>
				<tr>
				  <td class="label"><label for="form-addBook-add-book-button" form="form-addBook" style="visibility: hidden;">Add Book</label></td>
				  <td colspan="2" class="field">
					<input id="form-addBook-add-book-button" name="form-addBook-add-book-button" type="submit" value="Add Book" />&nbsp;
					<input id="form-addBook-clear-button" name="form-addBook-clear-button" type="reset" value="Clear" />
				  </td>
				</tr>
			  </tbody>
			</table>
		  </fieldset>
		</form>
	  </div>
	</section>
  </div>
  <div class="clearfloat"></div>
</div>
</body>
</html>