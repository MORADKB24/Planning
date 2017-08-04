<?php

require_once("connect_db.php");

$id_event_dup=$_POST['id_event_dup'];
$event_date=$_POST['event_date'];
$event_moment=$_POST['event_moment'];
$event_utilisateur=$_POST['utilisateur'];


if (!empty($_POST['id_event_dup']) && !empty($_POST['utilisateur']) && !empty($_POST['event_date']) && !empty($_POST['event_moment']))
{
	$sql=  "INSERT  INTO  evenement  (evenement_id, evenement_title, evenement_duree, evenement_statut, evenement_date, evenement_moment, chef_projet, evenement_ticket, evenement_desc, utilisateur)
	
		SELECT evenement_title, evenement_title, evenement_duree, evenement_statut, '$event_date', '$event_moment', chef_projet, evenement_ticket, evenement_desc, '$event_utilisateur'
		FROM  evenement
		WHERE evenement_id = $id_event_dup";
	
	$resultat  =  qdb($sql);
}

