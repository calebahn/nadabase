<?php
class DbUtil{
	public static $loginUser = "eyc4xd"; 
	public static $loginPass = "q_JvM.5pasdf";
	public static $host = "cs4750.cs.virginia.edu"; // DB Host
	public static $schema = "eyc4xd"; // DB Schema

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
