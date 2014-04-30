<?php
/************************************************
* Module: login.php								*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Allows user to login using their		*
*		   credentials							*
*************************************************/

require_once("Auth.inc.php");
$auth = new Auth();
$auth->setRedirectLoginSuccess("movie_library.php");
$auth->setRedirectLoginFailed("login.php?loginfailed=1");

// Validate request to login to this site
if (!isset($_SESSION)) {
	session_start();
}

// If login failed, run this
function loginFailed() {
	print "<script type=\"text/javascript\">\n";
	print "$(document).ready(function() {\n";
	print "  $(\"td#loginFailedError\").html(\"\");\n";
	print "  var error = \"<label class='error'>Login Failed. Please check your email address and password.</label>\";\n";
	print "  $(\"td#loginFailedError\").html(error);\n";
	print "});\n";
	print "</script>\n";
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST['form-login-authenticate']) && ($_POST['form-login-authenticate'] == "true")) {
	$auth->processLogin($_POST['form-login-username'], $_POST['form-login-password']);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>StoneLibrary</title>
<link type="text/css" href="css/main.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="http://jquery.bassistance.de/validate/lib/jquery.js"></script>
<script type="text/javascript" src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	// validate login form on keyup and submit
	var validator = $("#form-login").validate({ 
		rules: {
			"form-login-username": {
				required: true,
				email: true
			},
			"form-login-password": {
				required: true,
				minlength: 4
			}
		},
		messages: {
			"form-login-username": {
				required: "Please enter a valid email address",
				email: "Please enter a valid email address"
			},
			"form-login-password": {
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
<?php
if (isset($_GET['loginfailed']) && $_GET['loginfailed'] == 1) {
	loginFailed();
}
?>
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
#formbox-login {
	margin-left: 100px;
	width: 700px;
}
#formbox-login table {
	border-spacing: 0;
	border-collapse: collapse;
	empty-cells: show;
}
#formbox-login .label {
	padding-top: 2px;
	padding-right: 8px;
	vertical-align: top;
	text-align: right;
	width: 125px;
	white-space: nowrap;
}
#formbox-login .field {
	padding-bottom: 10px;
	white-space: nowrap;
}
#formbox-login .status {
	padding-top: 2px;
	padding-left: 8px;
	vertical-align: top;
	width: 246px;
	white-space: nowrap;
}
#formbox-login label.error {
	background:url("images/unchecked.gif") no-repeat 0px 0px;
	padding-left: 16px;
	padding-bottom: 2px;
	font-weight: bold;
	color: #F00;
}
#formbox-login label.checked {
	background:url("images/checked.gif") no-repeat 0px 0px;
}
#formbox-login fieldset {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
#formbox-login .success_msg {
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
	<nav>
	  <ul>
		<li class="selected">Home</li>
		<li><a href="movie_library.php">My Library</a></li>
	  </ul>
	</nav>
  </div>
  <div class="content">
	<h1>welcome to stonelibrary</h1>
	<?php if (isset($_GET['status'])){print "<h6>".$_GET['status']."</h6>";} ?>
	<section id="formbox-login">
	  <form id="form-login" name="form-login" method="post" action="<?php echo $loginFormAction; ?>">
		<fieldset>
		  <input id="form-login-authenticate" name="form-login-authenticate" type="hidden" value="true" />
		  <table>
			<tbody>
			  <tr>
				<td style="padding-bottom: 25px" colspan="3" class="status" id="loginFailedError">&nbsp;</td>
              </tr>
			  <tr>
				<td class="label"><label for="form-login-username" form="form-login">Email Address</label></td>
				<td class="field"><input id="form-login-username" name="form-login-username" type="email" autofocus="autofocus" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-login-password" form="form-login">Password</label></td>
				<td class="field"><input id="form-login-password" name="form-login-password" type="password" autocomplete="off" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-login-submit-button" form="form-login" style="visibility: hidden;">Login</label></td>
				<td colspan="2" class="field">
				  <input id="form-login-submit-button" name="form-login-submit-button" type="submit" value="Login" />
				  <input id="form-login-clear-button" name="form-login-clear-button" type="reset" value="Clear" />
				</td>
			  </tr>
			</tbody>
		  </table>
		</fieldset>
	  </form>
	  <span>Don't have an account? <a href="create_account.php">Create an Account</a></span>
	</section>
  </div>
  <div class="clearfloat"></div>
</div>
</body>
</html>