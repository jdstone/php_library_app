<?php
/************************************************
* Module: movie_library.php						*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Displays movie library to user		*
*************************************************/

require_once("Auth.inc.php");
require_once("Movies.inc.php");
$pagename = basename($_SERVER['PHP_SELF']);
$auth = new Auth();
$movies = new Movies($pagename);
$movies->setPageTitle("my movies");

// Initialize the session
if (!isset($_SESSION)) {
	session_start();
}

// Delete record
if ((isset($_GET['delete'])) && (is_numeric($_GET['delete']))) {
	$movies->deleteMoviesRecord($_GET['delete']);
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>StoneLibrary - My Movies</title>
<link type="text/css" href="css/main.css" rel="stylesheet" media="screen">
<style type="text/css">
<!--
a {
	text-decoration: none;
}
img {
	border: 0;
}
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
#library {
	background-image: url(images/rays_wood_1680_1_by_yc.jpg);
}
#library ul {
	list-style-type: none;
	margin: 0px;
	list-style-position: outside;
}
#library ul li {
	display: table-header-group;
}
#library fieldset {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
.library_item {
	position: relative;
	width: 85px;
	height: 100px;
	float: left;
	overflow: hidden;
	padding: 8px;
	margin: 5px 0 5px 10px;
}
.library_item img {
	padding-right: 10px;
	padding-left: 10px;
	position: relative;
	top: -22px;
}
.overlay_delete_item img {
	position: relative;
	z-index: 1;
	top: -9px;
	left: 76px;
	margin: 0px;
	padding: 0px;
}
-->
</style>
</head>

<body>
<div class="container">
  <div class="sidebar1">
	<?php echo $movies->buildNavBar("movies", $logoutAction); ?>
  </div>
  <div class="content">
	<!-- **future feature** When library is empty, offer to 'add media' -->
	<?php $movies->buildLibrary("movies"); ?>
  </div>
  <div class="clearfloat"></div>
</div>
</body>
</html>