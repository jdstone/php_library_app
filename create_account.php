<?php
/************************************************
* Module: create_account.php					*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Allows the user to create an account	*
*************************************************/

require_once("Auth.inc.php");
$auth = new Auth();

// Create an account
$createAccountFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST['form-signup-createAccount']) && ($_POST['form-signup-createAccount'] == "true")) {
	if ($auth->createAccount($_POST['form-signup-firstname'], $_POST['form-signup-email'], $_POST['form-signup-password'], $_POST['form-signup-email'])) {
		$status = "Account Successfully Created";
		header("Location: login.php?status=".urlencode($status));
	} else {
		$status = "Something Happened...Account NOT Created";
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>StoneLibrary - Create Account</title>
<link type="text/css" href="css/main.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="http://jquery.bassistance.de/validate/lib/jquery.js"></script>
<script type="text/javascript" src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	// validate signup form on keyup and submit
	var validator = $("#form-signup").validate({ 
		rules: {
			"form-signup-firstname": "required",
			"form-signup-email": {
				required: true,
				email: true
			},
			"form-signup-email-confirm": {
				required: true,
				email: true,
				equalTo: "#form-signup-email"
			},
			"form-signup-password": {
				required: true,
				minlength: 4
			},
			"form-signup-password-confirm": {
				required: true,
				minlength: 4,
				equalTo: "#form-signup-password"
			}
		},
		messages: {
			"form-signup-firstname": "Please enter your firstname",
			"form-signup-email": {
				required: "Please enter a valid email address",
				email: "Please enter a valid email address"
			},
			"form-signup-email-confirm": {
				required: "Please enter a valid email address",
				email: "Please enter a valid email address",
				equalTo: "Please enter the same email as above"
			},
			"form-signup-password": {
				required: "Please provide a password",
				minlength: jQuery.format("Please enter at least {0} characters")
			},
			"form-signup-password-confirm": {
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
#formbox-signup {
	margin-left: 100px;
	width: 350px;
}
#formbox-signup table {
	border-spacing: 0;
	border-collapse: collapse;
	empty-cells: show;
}
#formbox-signup .label {
	padding-top: 2px;
	padding-right: 8px;
	vertical-align: top;
	text-align: right;
	width: 125px;
	white-space: nowrap;
}
#formbox-signup .field {
	padding-bottom: 10px;
	white-space: nowrap;
}
#formbox-signup .status {
	padding-top: 2px;
	padding-left: 8px;
	vertical-align: top;
	width: 246px;
	white-space: nowrap;
}
#formbox-signup label.error {
	background:url("images/unchecked.gif") no-repeat 0px 0px;
	padding-left: 16px;
	padding-bottom: 2px;
	font-weight: bold;
	color: #F00;
}
#formbox-signup label.checked {
	background:url("images/checked.gif") no-repeat 0px 0px;
}
#formbox-signup fieldset {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
#formbox-signup .success_msg {
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
		<li><a href="login.php">Home</a></li>
		<li><a href="movie_library.php">My Library</a></li>
	  </ul>
	</nav>
  </div>
  <div class="content">
	<h1>join stonelibrary</h1>
	<section id="formbox-signup">
	  <form id="form-signup" name="form-signup" method="post" action="<?php echo $createAccountFormAction; ?>">
		<fieldset>
		  <input id="form-signup-createAccount" name="form-signup-createAccount" type="hidden" value="true" />
		  <table>
			<tbody>
			  <tr>
				<td class="label"><label for="form-signup-firstname" form="form-signup">First Name</label></td>
				<td class="field"><input id="form-signup-firstname" name="form-signup-firstname" type="text" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-signup-email" form="form-signup">Email</label></td>
				<td class="field"><input id="form-signup-email" name="form-signup-email" type="email" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-signup-email-confirm" form="form-signup">Confirm Email</label></td>
				<td class="field"><input id="form-signup-email-confirm" name="form-signup-email-confirm" type="email" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-signup-password" form="form-signup">Password</label></td>
				<td class="field"><input id="form-signup-password" name="form-signup-password" type="password" autocomplete="off" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-signup-password-confirm" form="form-signup">Confirm Password</label></td>
				<td class="field"><input id="form-signup-password-confirm" name="form-signup-password-confirm" type="password" autocomplete="off" size="20" maxlength="20" /></td>
				<td class="status"></td>
			  </tr>
			  <tr>
				<td class="label"><label for="form-signup-submit-button" form="form-signup" style="visibility: hidden;">Create an account</label></td>
				<td colspan="2" class="field">
				  <input id="form-signup-submit-button" name="form-signup-submit-button" type="submit" value="Create an account" />
				  <input id="form-signup-clear-button" name="form-signup-clear-button" type="reset" value="Clear" />
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