<?php
/************************************************
* Module: Auth.inc.php							*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Authentication Libraries. Provides	*
*		   various authentication related		*
*		   helper methods.						*
*************************************************/

require_once("MySQL.inc.php");

class Auth extends MySQL {
	// Declare variables
	private $redirectLoginSuccess;
	private $redirectLoginFailed;
	private $logoutGoTo;

	public function __construct() {
		// "In Main (BaseClass) constructor"
		parent::__construct();
		$this->logoutGoTo = "login.php";
	}

	// 'set' Methods
	public function setRedirectLoginSuccess($link) {
		$this->redirectLoginSuccess = $link;
	}

	public function setRedirectLoginFailed($link) {
		$this->redirectLoginFailed = $link;
	}

	public function setLogoutGoTo($link) {
		$this->logoutGoTo = $link;
	}

	// 'get' Methods
	public function getRedirectLoginSuccess() {
		return $this->redirectLoginSuccess;
	}

	public function getRedirectLoginFailed() {
		return $this->redirectLoginFailed;
	}

	public function getLogoutGoTo() {
		return $this->logoutGoTo;
	}

	public function changePasswd($currpasswd, $newpasswd) {
		$this->dbConnect();
		$currpasswd = $this->dblink->real_escape_string($currpasswd);
		$currpasswd = md5($currpasswd);
		$newpasswd = $this->dblink->real_escape_string($newpasswd);
		$newpasswd = md5($newpasswd);
		$result = $this->doQuery("SELECT passwd FROM users WHERE U_Id = '".$_SESSION['uid']."'");
		$dbarray = $result->fetch_assoc();
		if ($currpasswd == $dbarray['passwd']) {
			$result = $this->doQuery("UPDATE `users` SET `passwd`='$newpasswd' WHERE `U_Id`='".$_SESSION['uid']."'");
			return $result;
		} else {
			return false;
		}
		$this->dblink->close();
	}

	public function changeEmail($newemail, $currpasswd) {
		$this->dbConnect();
		$newemail = $this->dblink->real_escape_string($newemail);
		$currpasswd = $this->dblink->real_escape_string($currpasswd);
		$currpasswd = md5($currpasswd);
		$result = $this->doQuery("SELECT passwd, email FROM users WHERE U_Id = '".$_SESSION['uid']."'");
		$dbarray = $result->fetch_assoc();
		if ($currpasswd == $dbarray['passwd']) {
			$result = $this->doQuery("UPDATE `users` SET `username`='$newemail', `email`='$newemail' WHERE `U_Id`='".$_SESSION['uid']."'");
			return $result;
		} else {
			return false;
		}
		$this->dblink->close();
	}

	// Authenticate and log user in (see below)
	public function processLogin($username, $password) {
		$this->dbConnect();
		$username = $this->dblink->real_escape_string($username);
		$password = $this->dblink->real_escape_string($password);
		$password = md5($password);
		$result = $this->doQuery("SELECT U_Id, passwd, acslvl FROM users WHERE username = '".$username."'");
		$dbarray = $result->fetch_assoc();
		if ($password == $dbarray['passwd']) {
			$uid = $dbarray['U_Id'];
			// Check to see what type of user they are (admin, user, etc.)
			$usergroup = $dbarray['acslvl'];
			$this->createSession($uid, $usergroup);
			header("Location: ".$this->redirectLoginSuccess);
		} else {
			header("Location: ".$this->redirectLoginFailed);
		}
		$this->dblink->close();
	}

	public function createSession($uid, $usergroup) {
		session_regenerate_id(true);
		$_SESSION['uid'] = $uid;
		$_SESSION['usergroup'] = $usergroup;
	}

	public function doLogout() {
		// To fully log out a visitor we need to clear the session varialbles
		$_SESSION['uid'] = NULL;
		$_SESSION['usergroup'] = NULL;
		unset($_SESSION['uid']);
		unset($_SESSION['usergroup']);

		if ($this->logoutGoTo) {
			header("Location: ".$this->logoutGoTo);
			exit;
		}
	}

	public function createAccount($firstname, $username, $passwd, $email) {
		$this->dbConnect();
		$firstname = $this->dblink->real_escape_string($firstname);
		$username = $this->dblink->real_escape_string($username);
		$passwd = md5($passwd);
		$email = $this->dblink->real_escape_string($email);
		$result = $this->doQuery("INSERT INTO `users`(`firstname`, `username`, `passwd`, `email`) VALUES('$firstname', '$username', '$passwd', '$email')");
		$this->dblink->close();
		return $result;
	}

	// Restrict Access To Page: Grant or deny access to specified page
	private function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {
		// For security, start by assuming the visitor is NOT authorized
		$isValid = False;

		// When a visitor has logged into this site, the Session variable username is set equal to their username.
		// Therefore, we know that a user is NOT logged in if that Session variable is blank.
		if (!empty($UserName)) {
			// Besides being logged in, restrict access to only certain users based on an ID established when they login.
			// Parse the strings into arrays.
			$arrUsers = Explode(",", $strUsers);
			$arrGroups = Explode(",", $strGroups);
			if (in_array($UserName, $arrUsers)) {
				$isValid = true;
			}
			// Or, restrict access to only certain users based on their username
			if (in_array($UserGroup, $arrGroups)) {
				$isValid = true;
			}
			if (($strUsers == "") && false) {
				$isValid = true;
			}
		}
		return $isValid;
	}

	// Restrict Access To Page: Grant or deny access to specified page
	public function checkAccess($authorizedUsers) {
		$restrictGoTo = "denied.html";
		if (!((isset($_SESSION['uid'])) && ($this->isAuthorized("", $authorizedUsers, $_SESSION['uid'], $_SESSION['usergroup'])))) {
			$qsChar = "?";
			$authReferrer = $_SERVER['PHP_SELF'];
			if (strpos($restrictGoTo, "?")) {
				$qsChar = "&";
			}
			if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) {
				$authReferrer .= "?".$_SERVER['QUERY_STRING'];
			}
			$restrictGoTo = $restrictGoTo.$qsChar."accesscheck=".urlencode($authReferrer);
			header("Location: ".$restrictGoTo);
			exit;
		}
	}
}
?>