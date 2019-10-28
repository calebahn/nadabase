<?php
class DbUtil{
	public static $loginUser = "tcr4nd"; 
	public static $loginPass = "";
	public static $host = "cs4750.cs.virginia.edu"; // DB Host
	public static $schema = "tcr4nd_project"; // DB Schema

	public static function loginConnection(){
			$db = new mysqli(DbUtil::$host, DbUtil::$loginUser, DbUtil::$loginPass, DbUtil::$schema);

			if($db->connect_errno){
					echo("Could not connect to db");
					$db->close();
					exit();
			}

			return $db;
	}

}
?>
