<?php
/*
THIS IS A TEMPLATE FOR HOW YOUR 'db-settings.php' FILE SHOULD
BE SET UP. 

1. RENAME THIS FILE 'db-settings.php'
2. FILL IN YOUR MYSQL SERVER INFO ON LINES 13-16
3. ENJOY
*/


//Database Information
$db_host = "localhost"; //Host address (most likely localhost)
$db_name = "checkout"; //Name of Database
$db_user = "root"; //Name of database user
$db_pass = "root"; //Password for database user

$db_table_prefix = "uc_";

GLOBAL $errors;
GLOBAL $successes;

$errors = array();
$successes = array();

/* Create a new mysqli object with database connection parameters */
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
GLOBAL $mysqli;

if(mysqli_connect_errno()) {
	echo "Connection Failed: " . mysqli_connect_errno();
	exit();
}

//Direct to install directory, if it exists
if(is_dir("install/"))
{
	header("Location: install/");
	die();

}

?>
