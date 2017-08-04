<?php

require_once("connect_db.php");

$event_id=$_POST['id_event'];
$event_title=$_POST['new_event_title'];
$event_duree =$_POST['new_event_duree'];
$event_statut=$_POST['new_event_statut'];
$chef_projet=$_POST['new_event_chef_projet'];
$event_date=$_POST['new_event_date'];
$event_moment=$_POST['new_event_moment'];
$event_desc=$_POST['new_event_desc'];
$event_ticket=$_POST['new_event_ticket'];

if (!empty($_POST['id_event']))
{
	$sql=  "UPDATE evenement SET evenement_title = '$event_title', 
								 evenement_duree = '$event_duree', 
								 evenement_statut = '$event_statut', 
								 evenement_date = '$event_date', 
								 evenement_moment = '$event_moment', 
								 chef_projet = '$chef_projet', 
								 evenement_desc = '$event_desc',
								 evenement_ticket = '$event_ticket'
		      WHERE  evenement_id = $event_id";

	$resultat  =  qdb($sql);
	echo json_encode($_POST);

}

