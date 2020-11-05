<?php 
$_POST = json_decode(file_get_contents('php://input'), true);
class sqlserver {
	protected static $consql = "";
	public static function sqlcon(){
		try { 
			sqlserver::$consql = new PDO("sqlsrv:server=CLSI\SQLEXPRESS","sa","clsi2016");
			sqlserver::$consql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
				echo "Connection failed: ".$e->getMessage();
		}
			return sqlserver::$consql;
	}

}


?>