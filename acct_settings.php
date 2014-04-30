<?php
/************************************************
* Module: acct_settings.php						*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Displays account settings page and   *
*		   allows user to update their account	*
*		   settings								*
*************************************************/

require_once("Auth.inc.php");
require_once("Main.inc.php");
$pagename = basename($_SERVER['PHP_SELF']);
$auth = new Auth();
$main = new Main($pagename);
$main->setPageTitle("account settings");

// Initialize the session
if (!isset($_SESSION)) {
	session_start();
}

// Delete record
if ((isset($_GET['delete'])) && (is_numeric($_GET['delete']))) {
	$movies->deleteRecord($_GET['delete'], "movies");
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

$editAcctSettings = $_SERVER['PHP_SELF'];
// Update record in database
if (isset($_POST['form-chPasswd-editRecord']) && ($_POST['form-chPasswd-editRecord'] == "true")) {
	if ($auth->changePasswd($_POST['form-chPasswd-current'], $_POST['form-chPasswd-new'])) {
		$status = "Settings Saved";
	} else {
		$status = "Current Password Wrong. Try Again.";
	}
}
if (isset($_POST['form-chEmail-editRecord']) && ($_POST['form-chEmail-editRecord'] == "true")) {
	if ($auth->changeEmail($_POST['form-chEmail-email'], $_POST['form-chEmail-current-password'])) {
		$status = "Settings Saved";
	} else {
		$status = "Current Password Wrong. Try Again.";
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>StoneLibrary - Account Settings</title>
<link type="text/css" href="css/main.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="http://jquery.bassistance.de/validate/lib/jquery.js"></script>
<script type="text/javascript" src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	// validate chPasswd form on keyup and submit
	var validator = $("#form-chPasswd").validate({ 
		rules: {
			"form-chPasswd-current": {
				required: true,
				minlength: 4
			},
			"form-chPasswd-new": {
				required: true,
				minlength: 4
			},
			"form-chPasswd-retype-new": {
				required: true,
				minlength: 4,
				equalTo: "#form-chPasswd-new"
			}
		},
		messages: {
			"form-chPasswd-current": {
				required: "Please provide a password",
				minlength: jQuery.format("Please enter at least {0} characters")
			},
			"form-chPasswd-new": {
				required: "Please provide a password",
				minlength: jQuery.format("Please enter at least {0} characters")
			},
			"form-chPasswd-retype-new": {
				required: "Please repeat your password",
				minlength: jQuery.format("Please enter at least {0} characters"),
				equalTo: "Please enter the same password as above"
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
	// validate chEmail form on keyup and submit
	var validator = $("#form-chEmail").validate({ 
		rules: {
			"form-chEmail-email": {
				required: true,
				email: true
			},
			"form-chEmail-retype-email": {
				required: true,
				email: true,
				equalTo: "#form-chEmail-email"
			},
			"form-chEmail-current-password": {
				required: true,
				minlength: 4
			}
		},
		messages: {
			"form-chEmail-email": {
				required: "Please enter a valid email address",
				email: "Please enter a valid email address",
			},
			"form-chEmail-retype-email": {
				required: "Please enter a valid email address",
				email: "Please enter a valid email address",
				equalTo: "Please enter the same email as above"
			},
			"form-chEmail-current-password": {
				required: "Please provide a password",
				minlength: jQuery.format("Please enter at least {0} characters")
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
</script>
<style type="text/css">
<!--
.sidebar1 {
	float: left;
	width: 180px;
	padding-bottom: 10px;
	background-color: #ABBEDC;
	height: 607px;
}
.content ul, .content ol {
	padding: 0 15px 15px 40px;
}
#formbox-editAcct {
	margin-left: 100px;
	width: 742px;
}
#formbox-editAcct table {
	border-spacing: 0;
	border-collapse: collapse;
	empty-cells: show;
}
#formbox-editAcct .label {
	padding-top: 2px;
	padding-right: 8px;
	vertical-align: top;
	text-align: right;
	width: 125px;
	white-space: nowrap;
}
#formbox-editAcct .field {
	padding-bottom: 10px;
	white-space: nowrap;
}
#formbox-editAcct .status {
	padding-top: 2px;
	padding-left: 8px;
	vertical-align: top;
	width: 246px;
	white-space: nowrap;
}
#formbox-editAcct label.error {
	background:url("images/unchecked.gif") no-repeat 0px 0px;
	padding-left: 16px;
	padding-bottom: 2px;
	font-weight: bold;
	color: #F00;
}
#formbox-editAcct label.checked {
	background:url("images/checked.gif") no-repeat 0px 0px;
	padding-left: 16px;
}
#formbox-editAcct .success_msg {
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
	<?php echo $main->buildNavBar("acctsettings", $logoutAction); ?>
  </div>
  <div class="content">
	<h1>account settings</h1>
	<?php if (isset($status)){print "<h6>".$status."</h6>";} ?>
	<section id="formbox-editAcct">
	  <form id="form-chPasswd" name="form-chPasswd" method="post" action="<?php echo $editAcctSettings; ?>">
		<fieldset>
		  <legend>Password</legend>
		  <input id="form-chPasswd-editRecord" name="form-chPasswd-editRecord" type="hidden" value="true" />
		  <table>
			<tbody>
			  <tr>
				<td class="label"><label for="form-chPasswd-current" form="form-chPasswd">Current Password</label></td>
				<td class="field"><input id="form-chPasswd-current" name="form-chPasswd-current" type="password" autocomplete="off" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-chPasswd-new" form="form-chPasswd">New Password</label></td>
				<td class="field"><input id="form-chPasswd-new" name="form-chPasswd-new" type="password" autocomplete="off" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-chPasswd-retype-new" form="form-chPasswd">Re-type<br />New Password</label></td>
				<td class="field"><input id="form-chPasswd-retype-new" name="form-chPasswd-retype-new" type="password" autocomplete="off" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-chPasswd-save-changes-button" form="form-chPasswd" style="visibility: hidden;">Save Changes</label></td>
				<td colspan="2" class="field">
				  <input id="form-chPasswd-save-changes-button" name="form-chPasswd-save-changes-button" type="submit" value="Save Changes" />&nbsp;
				  <input id="form-chPasswd-cancel-button" name="form-chPasswd-cancel-button" type="reset" value="Cancel" />
				</td>
			  </tr>
			</tbody>
		  </table>
		</fieldset>
	  </form>
	  <br />
	  <br />
	  <br />
	  <form id="form-chEmail" name="form-chEmail" method="post" action="<?php echo $editAcctSettings; ?>">
		<fieldset>
		  <legend>Email</legend>
		  <input id="form-chEmail-editRecord" name="form-chEmail-editRecord" type="hidden" value="true" />
		  <table>
			<tbody>
			  <tr>
				<td class="label"><label for="form-chEmail-email" form="form-chEmail">Change Email</label></td>
				<td class="field"><input id="form-chEmail-email" name="form-chEmail-email" type="email" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-chEmail-retype-email" form="form-chEmail">Re-type Email</label></td>
				<td class="field"><input id="form-chEmail-retype-email" name="form-chEmail-retype-email" type="email" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-chEmail-current-password" form="form-chEmail">Current Password</label></td>
				<td class="field"><input id="form-chEmail-current-password" name="form-chEmail-current-password" type="password" autocomplete="off" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-chEmail-save-changes-button" form="form-chEmail" style="visibility: hidden;">Save Changes</label></td>
				<td colspan="2" class="field">
				  <input id="form-chEmail-save-changes-button" name="form-chEmail-save-changes-button" type="submit" value="Save Changes" />&nbsp;
				  <input id="form-chEmail-cancel-button" name="form-chEmail-cancel-button" type="reset" value="Cancel" />
				</td>
			  </tr>
			</tbody>
		  </table>
		</fieldset>
	  </form>
	</section>
  </div>
  <div class="clearfloat"></div>
</div>
</body>
</html>