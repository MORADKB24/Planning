<?php

require_once("connect_db.php");

$event_id=$_POST['id_event'];

if (
	!empty($_POST['id_event'])
	)
{
	$sql=  "DELETE FROM evenement WHERE  evenement_id = $event_id ";

	$resultat  =  qdb($sql);

	echo json_encode($resultat);
}
?>
