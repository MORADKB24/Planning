<?php

require_once("connect_db.php");

$event_id=$_POST['id_event'];


if (
	!empty($_POST['id_event'])
	)
{
	$sql=  "SELECT * FROM evenement WHERE  evenement_id = $event_id ";

	$resultat  =  qdb($sql);

	$data = mysql_fetch_array($resultat);
	echo json_encode($data);


}
?>
