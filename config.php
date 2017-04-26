<?php

// Connecting to the MySQL database
$user = 'sipplee1';
$password = 'qTLDqMuc';
$database = new PDO('mysql:host=csweb.hh.nku.edu;dbname=db_spring17_sipplee1', $user, $password);

// autoload classes
function my_autoloader($class) {
		include 'classes/class.' . $class . '.php';
	}
	spl_autoload_register('my_autoloader');

// Start the session
session_start();

$current_url = basename($_SERVER['REQUEST_URI']);

// if username is not set in the session and current URL not login.php redirect to login page
if (!isset($_SESSION["userID"]) && $current_url != 'login.php') {
    header("Location: login.php");
}

// Else if session key username is set get $user from the database
elseif (isset($_SESSION["userID"])) {
	// new User
    $user = new User($_SESSION["userID"], $database);
}
