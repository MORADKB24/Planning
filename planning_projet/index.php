<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
  <title>Planning</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <link rel="stylesheet" href="css/themes/default.min.css"/>
  <link rel="stylesheet" href="css/themes/semantic.min.css"/>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/alertify.min.css"/>
  <link rel="stylesheet" href="css/planning_jquery.css">
  <link rel="stylesheet" href="css/styles.css"/>

</head>
<body>
<?php
require_once("controllers/connect_db.php");

$users = array(
    6 => array('name' => '<a href="index.php?user=6">User 1</a>', 'group' => 1),
    98 => array('name' => '<a href="index.php?user=98">User 2</a>', 'group' => 2),
    97 => array('name' => '<a href="index.php?user=97">User 3</a>', 'group' => 2),
    58 => array('name' => '<a href="index.php?user=58">User 4</a>', 'group' => 3),
    5 => array('name' => '<a href="index.php?user=5">User 5</a>', 'group' => 3),
    121 => array('name' => '<a href="index.php?user=121">User 6</a>', 'group' => 4),
    7 => array('name' => '<a href="index.php?user=7">User 7</a>', 'group' => 4),
    114 => array('name' => '<a href="index.php?user=114">User 8</a>', 'group' => 4)
);

if ($_GET['user']) {
    $users = array($users[$_GET['user']]);
}

$projects_managers = array('M1' => 'manager1', 'M2' => 'manager2', 'M3' => 'manager2');
$events = array();
$query = "SELECT evenement_id, evenement_title, evenement_duree, evenement_statut, evenement_moment, chef_projet, evenement_desc, utilisateur, DATE_FORMAT(evenement_date, '%Y-%m-%d') AS evenement_date FROM evenement";
$resultat = @mysql_query($query);

while ($row = @mysql_fetch_assoc($resultat)) {
    $events[$row['evenement_id']] = array(
        "user" => $row['utilisateur'],
        "date" => $row['evenement_date'],
        "moment" => $row['evenement_moment'],
        "titre" => $row['evenement_title'],
        "duree" => $row['evenement_duree'],
        "statut" => $row['evenement_statut'],
        "chef_projet" => $row['chef_projet'],
        "ticket" => $row['evenement_ticket'],
        "description" => $row['evenement_desc']
    );
}
?>
<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div id="content">
        <div id="gen_new_content" title="Nouvel événement">
          <form action="controllers/event.php" method="post">
            <input type="hidden" id="next_event_id">
            <input type="hidden" name="user_id" id="user_id" value=""/>
            <input type="hidden" name="new_event_date" id="new_event_date" value=""/>
            <input type="hidden" name="new_event_moment" id="new_event_moment" value=""/>

            <label class="label_evenement" for="new_event_title">Objet *: </label>
            <input type="text" class="lab" name="new_event_title" id="new_event_title">
            <br/><br/>
            <label class="label_evenement" for="new_event_duree">Durée *: </label>
            <input class="lab" name="new_event_duree" id="new_event_duree" type="number" step="0.125" value="0" min="0" max="0.5">
            <br/>
            <div id="errmsg"></div>
            <br/>
            <label class="label_evenement" for="new_event_chef_projet">Responsable *: </label>
            <select class="lab" name="new_event_chef_projet" id="new_event_chef_projet">
              <option selected="selected" value="0"></option>
                <?php
                foreach ($projects_managers as $initiales => $prenom_nom) {
                    ?>
                  <option value="<?php echo $prenom_nom; ?>"><?php echo $prenom_nom; ?></option>

                    <?php
                }
                ?>
            </select>
            <br/><br/>
            <label class="label_evenement" for="new_event_statut">Statut *: </label>
            <select class="lab" name="new_event_statut" id="new_event_statut">
              <option placeholder="..." selected="selected" value="0"></option>
              <option>Non Commencée</option>
              <option>En Retard</option>
              <option>En Cours</option>
              <option>Retour Client</option>
              <option>Retour CP</option>
              <option>Terminée</option>
            </select>
            <br/><br/>
            <label>Description de la t&acirc;che *:</label><br/>
            <textarea name="new_event_desc" id="new_event_desc" rows="4" cols="30"></textarea>
            <br><br>
            <label class="label_evenement" for="new_event_ticket">Ticket Redmine : </label>
            <input type="text" class="lab" name="new_event_ticket" id="new_event_ticket">
          </form>
        </div>

        <div class="logo"></div>

        <div id="ajax_load" class="info_activation_module"></div>

        <div id="create_event"></div>

        <div id="dialog" title="Modification / Suppression"></div>

        <div id="date">
            <?php

            if (isset($_GET['year'])) {
                $year = (int)$_GET['year'];
            } else {
                $nowDate = new DateTime('NOW');
                $year = $nowDate->format("Y");
            }

            if (isset($_GET['week'])) {
                $week = $_GET['week'];
            } else {
                $nowDate = new DateTime('NOW');
                $week = $nowDate->format("W");
            }

            $week_start = new DateTime();
            $week_start->setISODate($year, $week);

            $week_end = new DateTime();
            $week_end->setISODate($year, $week);
            $week_end->modify('+4 day');

            $from = $week_start->format("d-m-Y");   //Returns the date of monday in week
            $to = $week_end->format("d-m-Y");       //Returns the date of friday in week

            $date = $from;

            ?>

          <a class="info" href="index.php?week=<?php echo($week - 1) ?>">
            <img src="img/bef_week.png" alt="before"/>
          </a>

          <span class="semaine">Semaine du <?php echo $from; ?> au <?php echo $to; ?></span>
          <a class="info" href="index.php?week=<?php echo($week + 1) ?>">
            <img src="img/next_week.png" alt="next"/>
          </a>
        </div>

        <div class="semaine_en_cours">
          <a href="index.php" class="btn"></a>
        </div>


        <div id="calendrier">
          <table id="calendar_table">
            <thead>
            <tr>
              <th>Equipe</th>
              <th>Moment de la journée</th>
                <?php
                for ($i = 0; $i < 5; $i++) {
                    echo '<th>' . $date . '</th>';
                    $date = date('d-m-Y', strtotime($date . ' + 1 days'));
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $user_id => $user) {

                if ($user['group'] == 1) {
                    echo "<tr style='background-color:#e0d9d9;'>";
                } elseif ($user['group'] == 2) {
                    echo "<tr style='background-color:#f2a979;'>";
                } elseif ($user['group'] == 3) {
                    echo "<tr style='background-color:#577F92;'>";
                } elseif ($user['group'] == 4) {
                    echo "<tr style='background-color:#A2B9B2;'>";
                }
                ?>
              <td class='noms' align='center' rowspan='2'>
                <div class='noms_content'><?php echo $user['name']; ?><!--<br/><a href="-->
                    <?php /*print $redmine_tma_url*/ ?><!--" target="_blank">TMA</a>--></div>
              </td>
              <td class='info_horaires'>
                <div class='info_horaires_content'>AM</div>
              </td>
                <?php
                $week_start = new DateTime();
                $week_start->setISODate($year, $week);
                $date = $week_start;

                for ($i = 0; $i < 5; $i++) {
                    ?>
                  <td class='calendar_td dropper' id='<?php echo $date->format("Ymd"); ?>_am_<?php echo $user_id; ?>'>
                      <?php
                      foreach ($events as $event_id => $event) {
                          if ($user_id == $event["user"]) {
                              if ($date->format("Y-m-d") == $event["date"]) {
                                  if ($event["moment"] == "am") {
                                      $words = preg_split("/[\s,_-]+/", $event['chef_projet']);
                                      $acronym = "";
                                      foreach ($words as $w) {
                                          $acronym .= $w[0];
                                      }

                                      $css_class = "";

                                      switch ($event["statut"]) {

                                          case "Non Commencée":
                                              $css_class = "to_do";
                                              break;

                                          case "En Retard":
                                              $css_class = "late";
                                              break;

                                          case "En Cours":
                                              $css_class = "in_progress";
                                              break;

                                          case "Retour Client":
                                              $css_class = "pending_return_cl";
                                              break;

                                          case "Retour CP":
                                              $css_class = "pending_return_cp";
                                              break;

                                          case "Terminée":
                                              $css_class = "finished";
                                              break;
                                      }
                                      echo "<div class='cell_am draggable " . $css_class . "' id='" . $event_id . "'>" . $event["titre"] . ' | <span class="duree"> ' . $event["duree"] . '</span> | ' . strtoupper($acronym) . '</div>';
                                  }
                              }
                          }
                      }
                      ?>
                  </td>
                    <?php
                    $date->add(new DateInterval('P1D'));
                }
                ?>
              </tr>
                <?php
                if ($user['group'] == 1) {
                    echo "<tr style='background-color:#e0d9d9;'>";
                } elseif ($user['group'] == 2) {
                    echo "<tr style='background-color:#f2a979;'>";
                } elseif ($user['group'] == 3) {
                    echo "<tr style='background-color:#577F92;'>";
                } elseif ($user['group'] == 4) {
                    echo "<tr style='background-color:#A2B9B2;'>";
                }
                ?>
              <td class='info_horaires'>
                <div class='info_horaires_content'>PM</div>
              </td>
                <?php

                $week_start = new DateTime();
                $week_start->setISODate($year, $week);
                $date = $week_start;

                for ($i = 0; $i < 5; $i++) {
                    ?>
                  <td class='calendar_td dropper' id='<?php echo $date->format("Ymd"); ?>_pm_<?php echo $user_id; ?>'>
                      <?php
                      foreach ($events as $event_id => $event) {
                          if ($user_id == $event["user"]) {
                              if ($date->format("Y-m-d") == $event["date"]) {
                                  if ($event["moment"] == "pm") {
                                      $words = preg_split("/[\s,_-]+/", $event['chef_projet']);
                                      $acronym = "";
                                      foreach ($words as $w) {
                                          $acronym .= $w[0];
                                      }

                                      $css_class = "";

                                      switch ($event["statut"]) {

                                          case "Non Commencée":
                                              $css_class = "";
                                              break;

                                          case "En Retard":
                                              $css_class = "late";
                                              break;

                                          case "En Cours":
                                              $css_class = "in_progress";
                                              break;

                                          case "Retour Client":
                                              $css_class = "pending_return_cl";
                                              break;

                                          case "Retour CP":
                                              $css_class = "pending_return_cp";
                                              break;

                                          case "Terminée":
                                              $css_class = "finished";
                                              break;
                                      }

                                      echo "<div class='cell_pm draggable " . $css_class . "' id='" . $event_id . "'>" . $event["titre"] . ' | <span class="duree"> ' . $event["duree"] . '</span> | ' . strtoupper($acronym) . '</div>';
                                  }
                              }
                          }
                      }
                      ?>
                  </td>
                    <?php
                    $date->add(new DateInterval('P1D'));
                }
                ?>
              </tr>
                <?php
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/draggable.js"></script>
<script type="text/javascript" src="js/planning_script.js"></script>
<script type="text/javascript" src="js/alertify.min.js"></script>

</body>
</html>
