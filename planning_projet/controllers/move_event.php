<?php

require_once("connect_db.php");

$event_id=$_POST['id_event'];
$event_date=$_POST['event_date'];
$event_moment=$_POST['event_moment'];
$event_utilisateur=$_POST['utilisateur'];


if (!empty($_POST['id_event']) && !empty($_POST['utilisateur']) && !empty($_POST['event_date']) && !empty($_POST['event_moment']))
{
	$sql=  "UPDATE evenement SET evenement_date = '$event_date', 
								 evenement_moment = '$event_moment', 
								 utilisateur = '$event_utilisateur'
		      WHERE  evenement_id = $event_id";

	$resultat  =  qdb($sql);
}

