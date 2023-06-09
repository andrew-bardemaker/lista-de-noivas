<?php
//para mostar TODOS os avisos
error_reporting(E_ALL);
error_reporting(E_ALL ^ E_DEPRECATED);
header('Content-Type: text/html; charset=utf-8');

//conex�o com o banco de dados
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	define('HOSTDB', 'localhost');
	define('USERDB', 'root');
	define('PASSDB', '');
	define('BASEDB', 'con555');
}
else {
	define('HOSTDB','mysql.dedstudio.poa.br');
	define('USERDB','dedstudio13');
	define('PASSDB','p4ineld3d');
	define('BASEDB','dedstudio13');
}


include_once('class.DbAdmin.php');
$dba = new DbAdmin('mysql');
$dba->connect(HOSTDB, USERDB, PASSDB, BASEDB);

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

?>