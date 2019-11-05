<?php
class DbUtil{
	public static $loginUser = "jp6ud";
	public static $loginPass = "";
	public static $host = "cs4750.cs.virginia.edu"; // DB Host
	public static $schema = "jp6ud_nadabases"; // DB Schema

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
