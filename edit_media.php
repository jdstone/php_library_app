<?php
/************************************************
* Module: edit_media.php						*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Displays the edit media page. Allows	*
*		   user to edit the title & other		*
*		   attributes of their books, movies, &	*
*		   music.								*
*************************************************/

require_once("Auth.inc.php");
require_once("Movies.inc.php");
require_once("Music.inc.php");
require_once("Books.inc.php");
$pagename = basename($_SERVER['PHP_SELF']);
$auth = new Auth();
$movies = new Movies($pagename);
$music = new Music($pagename);
$books = new Books($pagename);

// Initialize the session
if (!isset($_SESSION)) {
	session_start();
}

// Edit record
$editRecordAction = $_SERVER['PHP_SELF'];
if ((isset($_POST['form-dialog-editMovie-editRecord'])) && ($_POST['form-dialog-editMovie-editRecord'] == "true")) {
	$movies->editMovieRecord($_GET['edit'], $_POST['form-dialog-editMedia-movie-title'], $_POST['form-dialog-editMedia-movie-year'],
	  $_POST['form-dialog-editMedia-movie-rating'], $_POST['form-dialog-editMedia-movie-aspect-ratio'],
	  $_POST['form-dialog-editMedia-movie-director'], $_POST['form-dialog-editMedia-movie-runtime'],
	  $_POST['form-dialog-editMedia-movie-language']);
}
if ((isset($_POST['form-dialog-editMusic-editRecord'])) && ($_POST['form-dialog-editMusic-editRecord'] == "true")) {
	$music->editMusicRecord($_GET['edit'], $_POST['form-dialog-editMedia-music-title'], $_POST['form-dialog-editMedia-music-artist'],
	  $_POST['form-dialog-editMedia-music-year']);
}
if ((isset($_POST['form-dialog-editBook-editRecord'])) && ($_POST['form-dialog-editBook-editRecord'] == "true")) {
	$books->editBookRecord($_GET['edit'], $_POST['form-dialog-editMedia-book-title'], $_POST['form-dialog-editMedia-book-author'],
	  $_POST['form-dialog-editMedia-book-publisher']);
}

// Delete record
if ((isset($_GET['delete'])) && (is_numeric($_GET['delete']))) {
	$movies->deleteRecord($_GET['delete'], "movies");
}
if ((isset($_GET['delete'])) && (is_numeric($_GET['delete']))) {
	$music->deleteRecord($_GET['delete'], "music");
}
if ((isset($_GET['delete'])) && (is_numeric($_GET['delete']))) {
	$books->deleteRecord($_GET['delete'], "books");
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
$authorizedUsers = "1,3";
$donotCheckaccess = "false";
$auth->checkAccess($authorizedUsers);
?>
<!DOCTYPE HTML>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>StoneLibrary - Edit Media</title>
<link type="text/css" href="css/main.css" rel="stylesheet" media="screen">
<link type="text/css" href="css/cupertino/jquery-ui-1.8.14.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$('#formbox-dialog-editMovies').dialog({
		autoOpen: false,
		height: 640,
		width: 543,
		modal: true,
		buttons: {
			"Update": function() {
				$(this).trigger('submit');
			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}
	});

	$('#formbox-dialog-editMusic').dialog({
		autoOpen: false,
		height: 640,
		width: 500,
		modal: true,
		buttons: {
			"Update": function() {
				$(this).trigger('submit');
			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}
	});

	$("#formbox-dialog-editBooks").dialog({
		autoOpen: false,
		height: 640,
		width: 500,
		modal: true,
		buttons: {
			"Update": function() {
				$(this).trigger('submit');
			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}
	});

	$('#formbox-dialog-editMovies').bind( "submit", function() {
		var editRecord	= $('#form-dialog-editMovie-editRecord').attr('value'),
			mtitle		= $('#form-dialog-editMedia-movie-title').attr('value'),
			myear		= $("#form-dialog-editMedia-movie-year").attr("value"),
			mrating		= $("#form-dialog-editMedia-movie-rating").attr("value"),
			maspectr	= $("#form-dialog-editMedia-movie-aspect-ratio").attr("value"),
			mdirector	= $("#form-dialog-editMedia-movie-director").attr("value"),
			mruntime	= $("#form-dialog-editMedia-movie-runtime").attr("value"),
			mlanguage	= $("#form-dialog-editMedia-movie-language").attr("value");

		$.ajax({
			type: "POST",
			url: "<?php $editRecordAction; ?>",
			data: "form-dialog-editMovie-editRecord="+true+"&form-dialog-editMedia-movie-title="+mtitle+"&form-dialog-editMedia-movie-year="+myear+"&form-dialog-editMedia-movie-rating="+mrating+"&form-dialog-editMedia-movie-aspect-ratio="+maspectr+"&form-dialog-editMedia-movie-director="+mdirector+"&form-dialog-editMedia-movie-runtime="+mruntime+"&form-dialog-editMedia-movie-language="+mlanguage,
			success: function(html) {
				$('#formbox-dialog-editMovies').dialog("close");
				$("body").load('<?php $_SERVER['PHP_SELF']; ?>');
			}
		});
	});

	$('#formbox-dialog-editMusic').bind( "submit", function() {
		var editRecord	= $('#form-dialog-editMusic-editRecord').attr('value'),
			mtitle		= $('#form-dialog-editMedia-music-title').attr('value'),
			martist		= $("#form-dialog-editMedia-music-artist").attr("value"),
			myear		= $("#form-dialog-editMedia-music-year").attr("value");

		$.ajax({
			type: "POST",
			url: "<?php $editRecordAction; ?>",
			data: "form-dialog-editMusic-editRecord="+true+"&form-dialog-editMedia-music-title="+mtitle+"&form-dialog-editMedia-music-artist="+martist+"&form-dialog-editMedia-music-year="+myear,
			success: function(html) {
				$('#formbox-dialog-editMusic').dialog("close");
				$("body").load('<?php $_SERVER['PHP_SELF']; ?>');
			}
		});
	});

	$('#formbox-dialog-editBooks').bind( "submit", function() {
		var editRecord	= $('#form-dialog-editBook-editRecord').attr('value'),
			btitle		= $('#form-dialog-editMedia-book-title').attr('value'),
			bauthor		= $("#form-dialog-editMedia-book-author").attr("value"),
			bpub		= $("#form-dialog-editMedia-book-publisher").attr("value");

		$.ajax({
			type: "POST",
			url: "<?php $editRecordAction; ?>",
			data: "form-dialog-editBook-editRecord="+true+"&form-dialog-editMedia-book-title="+btitle+"&form-dialog-editMedia-book-author="+bauthor+"&form-dialog-editMedia-book-publisher="+bpub,
			success: function(html) {
				$('#formbox-dialog-editBooks').dialog("close");
				$("body").load('<?php $_SERVER['PHP_SELF']; ?>');
			}
		});
	});

	$('#opener').click(function() {
		$('#formbox-dialog-editMovies').dialog('open');
		return false;
	});

	$('#opener').click(function() {
		$('#formbox-dialog-editMusic').dialog('open');
		return false;
	});

	$('#opener').click(function() {
		$('#formbox-dialog-editBooks').dialog('open');
		return false;
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
	height: 700px;
}
.content ul, .content ol {
	padding-top: 0;
	padding-right: 15px;
	padding-bottom: 0px;
	padding-left: 15px;
}
#media_item {
	float: left;
	overflow: auto;
	padding: 8px;
}
#media_item ul {
	list-style-type: none;
}
#media_item img {
	padding-right: 10px;
	padding-left: 10px;
	float: inherit;
}
.media_info_container {
	width: 408px;
	float: left;
}
.media_info_container ul span {
	word-wrap: break-word;
}
.media_info_container li {
	padding-top: inherit;
	padding-right: inherit;
	padding-bottom: inherit;
	padding-left: 52px;
	text-indent: -3.2em;
}
.media_overlay_item {
	z-index: 1;
	float: right;
}
.media_overlay_item button {
	-webkit-border-radius: 4px;
	border: 1px solid #CCC;
	background-color: #F6F6F6;
	font-weight: bold;
	color: #1C94C4;
	outline: none;
}
.media_overlay_item button:hover {
	-webkit-border-radius: 4px;
	border: 1px solid #CCC;
	background-color: #F6F6F6;
	font-weight: bold;
	color: #FBCB09;
	outline: none;
}
.media_overlay_delete_item {
	position: relative;
	z-index: 1;
	float: left;
	display: none;
	left: 177px;
	top: 30px;
}
.media_info {
	float: left;
}
.formbox ol {
	list-style-type: none;
}
-->
</style>
</head>

<body>
<div class="container">
  <div class="sidebar1">
	<?php echo $movies->buildNavBar(NULL, $logoutAction); ?>
  </div>
  <div class="content">
	<?php
		if ((isset($_GET['library'])) && ($_GET['library'] == "movies")) {
			// Display requested movie record
			echo $movies->retrieveMovieRecord($_GET['edit'], $editRecordAction);
		} else if ((isset($_GET['library'])) && ($_GET['library'] == "music")) {
			// Display requested music record
			echo $music->retrieveMusicRecord($_GET['edit'], $editRecordAction);
		} else if ((isset($_GET['library'])) && ($_GET['library'] == "books")) {
			// Display requested book record
			echo $books->retrieveBookRecord($_GET['edit'], $editRecordAction);
		} else {
			print "<h1>Something happened...</h1>\n";
			print "<p><a href=\"javascript:window.history.back()\">Back</a></p>\n";
		}
	?>
  </div>
  <div class="clearfloat"></div>
</div>
</body>
</html>