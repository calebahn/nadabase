<?php
class DbUtil{
	public static $loginUser = "cha4yw_a"; //prelogged in user
	public static $loginUser2 = "cha4yw_b"; //logged in standard user
	public static $loginUserAdmin = "cha4yw"; //admin
	public static $loginPass = "yo2ohXee";
	public static $loginPassAdmin = "password";

	public static $host = "cs4750.cs.virginia.edu"; // DB Host
	public static $schema = "cha4yw"; // DB Schema


	public static function notLoggedIn(){
			$db = new mysqli(DbUtil::$host, DbUtil::$loginUser, DbUtil::$loginPass, DbUtil::$schema);

			if($db->connect_errno){
					echo("Could not connect to db");
					$db->close();
					exit();
			}

			return $db;
	}

	public function logInUserB(){
		$db = new mysqli(DbUtil::$host, DbUtil::$loginUser2, DbUtil::$loginPass, DbUtil::$schema);

			if($db->connect_errno){
					echo("Could not connect to db");
					$db->close();
					exit();
			}

			return $db;
	}

	public function logInAdmin(){
		$db = new mysqli(DbUtil::$host, DbUtil::$loginUserAdmin, DbUtil::$loginPassAdmin, DbUtil::$schema);

			if($db->connect_errno){
					echo("Could not connect to db");
					$db->close();
					exit();
			}

			return $db;
	}	

}
?>
