<?
class DB
{
	var  $result;
	var  $conn;
	
	var $objConfig;

	var $dbHost;
	var $dbUser;
	var $dbPass;
	var $dbDatabase;

	function DB()
	{
		$this->dbHost      = "cpmy0021.servidorwebfacil.com";
		$this->dbUser      = "wallnet_admin";
		$this->dbPass      = '$up0rtE';
		$dbDatabase        = "wallnet_sales";
					
		if(!$this->conn) 
			$this->conectarDB();
		$this->result = 0;
	}

	function conectarDB()
	{
		$this->conn = mysql_connect($this->dbHost,$this->dbUser,$this->dbPass) or die("erro");
		mysql_select_db('wallnet_sales' ,$this->conn);
		return $this->conn;
	}
	
	function desconectarDB()
	{
		mysql_close($this->conn);
	}

	function executarSQL($sql, $insert = false)	{
		//debug::x($sql);
		$this->conectarDB();
		$this->result = mysql_query($sql) or die(mysql_error());
		if($insert)		
			$this->result = mysql_insert_id($this->conn);	
		return $this->result;
	}
	
	function count($result=0) {
		$this->result = $result;			
		if($this->result)
			return mysql_num_rows($this->result);
		return 0;			
	}
	
	function getTupla($result=0)
	{
		$this->result = $result;			
		if($this->result)
			return @mysql_fetch_array($this->result);		
		return 0;
	}

	function escape($strValor) {
		if($strValor == 'NULL')
			return 'NULL';
		
		$strValor = str_replace("\\", "\\\\", $strValor);
		$strValor = str_replace("\n", "\\n", $strValor);
		$strValor = str_replace("\r", "\\r", $strValor);
		$strValor = str_replace("\0", "\\0", $strValor);
		$strValor = str_replace("\"", "\\\"", $strValor);	
					
		return '"' . $strValor . '"';
	}
}
class debug {
	public static function x($strValor, $boolExit = 1) {
		echo '<pre>';
		print_r($strValor);
		if($boolExit)
			exit();
		echo '</pre>';
	}
}

class format {
	public static function removeAcentos($strTexto) {
		$array1 = array( "Ã¡", "Ã ", "Ã¢", "Ã£", "Ã¤", "Ã©", "Ã¨", "Ãª", "Ã«", "Ã­", "Ã¬", "Ã®", "Ã¯", "Ã³", "Ã²", "Ã´", "Ãµ", "Ã¶", "Ãº", "Ã¹", "Ã»", "Ã¼", "Ã§" 
					, "Ã�", "Ã€", "Ã‚", "Ãƒ", "Ã„", "Ã‰", "Ãˆ", "ÃŠ", "Ã‹", "Ã�", "ÃŒ", "ÃŽ", "Ã�", "Ã“", "Ã’", "Ã”", "Ã•", "Ã–", "Ãš", "Ã™", "Ã›", "Ãœ", "Ã‡", " " ); 
		$array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
					, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", "" ); 
		return str_replace($array1, $array2, utf8_encode($strTexto));			
	}
}

class form {
	public static function addMessage($strTexto) {
		$_SESSION['mensagem'] = $strTexto;			
	}
	public static function showMessage() {
		$strTexto = '';
		if(isset($_SESSION['mensagem'])) {
			$strTexto = '<span class="message"><img src="imagens/alert.png" style="vertical-align:middle;padding:10px;"/>'.$_SESSION['mensagem'].'</span>';
			unset($_SESSION['mensagem']);
		}
		
		return $strTexto;
	}
}

?>