<?php

class DbConnector {
	public static $strHost = 'cpmy0021.servidorwebfacil.com';
	public static $strUser = 'urlforce_admin';
	public static $strPass = '$up0rtE';
	public static $strDatabase = 'urlforce_data';
	
	public static function connectDB() {		
		$conn = mysql_connect(self::$strHost, self::$strUser, self::$strPass) or die("erro");
		mysql_select_db(self::$strDatabase, $conn);
	}
	
	public static function getServidores() {
		$strQuery = 'SELECT * FROM servers WHERE COD_STATUS = 1';
		
		$objResult = mysql_query($strQuery);
		
		while ($row = mysql_fetch_array($objResult)) {
			$arrServidores[] = $row['DSC_URL'];
		}
		
		return $arrServidores;
	}
	
	public static function getEncurtadores() {
		$strQuery = 'SELECT * FROM urlhidden WHERE COD_STATUS = 1';
		
		$objResult = mysql_query($strQuery);
		
		while ($row = mysql_fetch_array($objResult)) {
			$arrEncurtadores[] = $row['DSC_URL'];
		}
		
		return $arrEncurtadores;
	}
	
}
?>