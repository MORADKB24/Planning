<?php
$dbhost="localhost";
//$dbuser="root";
$dbuser="planning";
//$dbpassword="root";
$dbpassword="WQ5AurIJt7MpFvhB";
$dbname="planning";
$dblink=@mysql_connect($dbhost,$dbuser,$dbpassword);
$db=@mysql_select_db($dbname,$dblink);

@mysql_set_charset('utf8', $db);
@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

function qdb($sql)
{
	$resultat = @mysql_query($sql);
	if (mysql_errno())
	{
		echo "MySQL error ".mysql_errno()." : ".mysql_error()."\n<br>";
		echo "Requ&ecirc;te MySQL : ".$sql."\n<br>";
		die;
	}
	return $resultat;
}

?>
