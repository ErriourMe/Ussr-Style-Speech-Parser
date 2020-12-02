<?php 

class DB
{
	public static $dsn = 'mysql:dbname=ussr_style_speech;host=localhost';
	public static $user = 'root';
	public static $pass = 'root';
 
	/**
	 * Объект PDO
	 */
	public static $dbh = null;
 
	/**
	 * Statement Handle
	 */
	public static $sth = null;
 
	/**
	* Подключение к БД
	*/
	public static function getDbh()
	{	
		if (!Self::$dbh) {
			try {
				Self::$dbh = new PDO(
					Self::$dsn, 
					Self::$user, 
					Self::$pass, 
					[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
				);
				Self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			} catch (PDOException $e) {
				die('Error connecting to database: ' . $e->getMessage());
			}
		}
 
		return Self::$dbh; 
	}
	
	/**
	* Выполнение запроса
	*/
	public static function query(String $query, Array $param = [])
	{
		Self::$sth = Self::getDbh()->prepare($query);
		return Self::$sth->execute((array) $param);
    }
}