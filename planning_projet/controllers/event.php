<?php

require_once("connect_db.php");

$user_id=(int)$_POST['user_id'];
$title=$_POST['new_event_title'];
$duree =$_POST['new_event_duree'];
$statut=$_POST['new_event_statut'];
$chef_projet=$_POST['new_event_chef_projet'];
$date=$_POST['new_event_date'];
$moment=$_POST['new_event_moment'];
$desc=$_POST['new_event_desc'];
$ticket=(int)$_POST['new_event_ticket'];

if (!empty($_POST['new_event_title']) && !empty($_POST['new_event_duree']) && !empty($_POST['new_event_statut']) &&  !empty($_POST['new_event_date']) &&  !empty($_POST['new_event_moment']) && !empty($_POST['new_event_chef_projet']) && !empty($_POST['user_id']) && !empty($_POST['new_event_desc']) )
{
	$sql=  "INSERT  INTO  evenement  (evenement_id, evenement_title, evenement_duree, evenement_statut, evenement_date, evenement_moment, chef_projet, evenement_ticket, evenement_desc, utilisateur)
	VALUES  ('', '$title', '$duree', '$statut', '$date', '$moment', '$chef_projet', '$ticket', '$desc', '$user_id')";

	$resultat  =  qdb($sql);
}
?>
