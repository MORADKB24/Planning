$(function () {
  var current_event_selected = null;
  var element_to_copy = null;
  var element_to_cut = null;
  var time = {left:0,current:0};

  $(".calendar_td").on('click', function () {
    var $this = $(this),
        $child = $this.children(),
        childNb = $child.length,
        timeLeft = 0,
        compteur = 0;

    if(childNb > 0){
      $child.each(function () {
        var $this = $(this);
        var duree = parseFloat($this.find('.duree').text().replace(/\s/g,''));
        compteur += duree;
      });
    }
    if(compteur < 0.5){
      timeLeft = 0.5 - compteur;
    }
    $('#new_event_duree').attr('max', timeLeft ).attr('value', 0);
    time.left = timeLeft;
  });

  $(".calendar_td div").on('click', function () {
    var $this = $(this),
      $child = $this.parent().children(),
      childNb = $child.length,
      timeLeft = 0,
      compteur = 0;

    if(childNb > 0){
      $child.each(function () {
        var $this = $(this);
        var duree = parseFloat($this.find('.duree').text().replace(/\s/g,''));
        compteur += duree;
      });
    }
    if(compteur < 0.5){
      timeLeft = 0.5 - compteur;
    }

    var duree = parseFloat($this.find('.duree').text().replace(/\s/g,''));
    time.current = duree;
    time.left = timeLeft;
  });


  $(".calendar_td").dblclick(function (e) {
    if(time.left == 0){
      alertify.alert("Vous ne pouvez pas dépasser 0.5 par demi-journée ! ", function(){
          alertify.error('Pas plus haut que le bord ! ');
        });
      return;
    }

    var position_choisie = e.pageY - $(this).position().top;
    var to_round = Math.round(position_choisie);
    var to_string = String(to_round);
    var str_length = to_string.length - 1;
    var to_substr = to_string.substr(0, str_length);
    var to_int = parseInt(to_substr);
    var event_id = $("#next_event_id").val();

    if(!to_int){
      to_int=0;
    }

    var str = $(this).attr("id");

    // Récupération de l'identifiant de l'utilisateur
    var user_id = str.substring(12);
    $("#user_id").val(user_id);

    // Récupération de la date
    var new_event_date = str.substring(0, 4) + '-' + str.substring(4, 6) + '-' + str.substring(6, 8);
    $("#new_event_date").val(new_event_date);

    // Récupération du moment
    var new_event_moment = str.substring(9, 11);
    $("#new_event_moment").val(new_event_moment);


    /*dialog de remplissage*/
    $("#gen_new_content").dialog({
      bgiframe: true,
      resizable: true,
      height: 430,
      width: 500,
      modal: true,
      beforeclose: function() {
        $("#new_event_title").val("");
        $("#new_event_duree").val("");
        $("#new_event_statut").val("");
        $("#new_event_date").val("");
        $("#new_event_moment").val("");
        $("#new_event_desc").val("");
        $("#new_event_ticket").val("");
        $(this).dialog('destroy');
      },
      buttons: {
        'Enregistrer': function () {
          var dataForm = $('#gen_new_content').find('form').serialize();
          var url = $('#gen_new_content').find('form').attr('action');
          if (
            ($("#new_event_title").val() == "") ||
            ($("#new_event_duree").val() == "") ||
            ($("#new_event_statut").val() == "") ||
            ($("#new_event_date").val() == "") ||
            ($("#new_event_moment").val() == "") ||
            ($("#new_event_desc").val() == "") ) {
            alertify
              .alert("Merci de saisir tous les champs obligatoires (*)", function(){
                alertify.error('Be careful !');
              });
            }
          else if( $("#new_event_duree").val() > 0.5){
            alertify.alert("Vous ne pouvez pas dépasser 0.5 par demi-journée ! ", function(){
              alertify.error('Pas plus haut que le bord !');
            });
          }
          else if ($("#new_event_duree").val() > time.left) {
            alertify.alert("Vous ne pouvez pas dépasser 0.5 par demi-journée ! ", function(){
              alertify.error('Pas plus haut que le bord !');
            });
          }
          else {
            $.ajax({
            url: url,
            type: 'POST',
            data: dataForm,
            success: function (data) {
              alertify.confirm("Votre événement à bien été crée ! :D ",
                function(){
                  alertify.success('évenement crée');
                  window.location.reload();

                },
                function(){
                  alertify.error('Cancel');
                });
              }
            });
            $("#gen_new_content").dialog('destroy');
            $("#next_event_id").val(event_id + 1);
          }
        },
        'Annuler': function () {
          $("#new_event_title").val("");
          $("#new_event_duree").val("");
          $("#new_event_statut").val("");
          $("#new_event_date").val("");
          $("#new_event_moment").val("");
          $("#new_event_desc").val("");
          $("#new_event_ticket").val("");
          $(this).dialog('destroy');
        }
      }
    });
  });

  //CONTENU cell_am, cell_pm
  $(".cell_am, .cell_pm").dblclick(function (e) {
	e.stopPropagation();
  var object_clicked = $(this);
  var id_event = object_clicked.attr("id");
    $.ajax({
      url: 'controllers/get_event.php',
      type: 'POST',
      data: {'id_event': id_event},
      dataType: 'json',
      success: function (data) {
        $("#dialog").dialog({
          bgiframe: true,
          resizable: true,
          height: 270,
          width: 500,
          modal: true,
          overlay: {
            backgroundColor: '#000',
            opacity: 0.5
          },
          beforeclose: function (event, ui) {
            $(this).dialog('destroy');
          },
          open: function (event, ui) {
            var contenu = "<p>";
            //contenu += "<b> ID Evénement: </b>" + id_event + " </p> ";
            //contenu += "<b> Utilisateur: </b>" + data.utilisateur + " </p> ";
            contenu += "<b> Sujet: </b>" + data.evenement_title + " </p> ";
            contenu += "<b> Durée: </b>" + data.evenement_duree + " </p> ";
            contenu += "<b> Chef de Projet: </b>" + data.chef_projet + " </p> ";
            contenu += "<b> Date: </b>" + data.evenement_date + " </p> ";
            contenu += "<b> Moment: </b>" + data.evenement_moment + " </p> ";
            contenu += "<b> Description de la tâche: </b>" + data.evenement_desc + " </p> ";
            contenu += "<b> Ticket Redmine: </b>"+"<a href='https://redmine.wnp.fr/issues/"+data.evenement_ticket+"' target='_blank'>https://redmine.wnp.fr/issues/" + data.evenement_ticket + "</a></p> ";
            contenu += "</p>";
            $("#ui-dialog-title").html(data.evenement_title);
            $("#dialog").html(contenu);
          },
          buttons: {
            'Supprimer': function () {
              $(this).html("Veuillez Confirmer la suppression");
              $(this).dialog('destroy');
              $("#dialog").dialog({
                bgiframe: true,
                resizable: true,
                height: 140,
                modal: true,
                beforeclose: function (event, ui) {
                  $(this).dialog('destroy');
                },
                buttons: {
                  'Supprimer': function () {
                    $.ajax({
                      url: 'controllers/delete_event.php',
                      type: 'POST',
                      data: {'id_event': id_event},
                      dataType: 'json',
                      success: function () {
                        console.log(data);
                        $("#dialog").dialog('destroy');
                        $("#ajax_load").html("");
                        $("#" + id_event).hide("highlight", {
                          direction: "vertical",
                          color: "#A60000"
                        }, 1000);
                        window.location.reload();
                      }
                    });
                  },
                  'Annuler': function () {
                    $(this).dialog('destroy');
                    window.location.reload();
                  }
                }
              });
            },
            'Modifier': function () {
              $(this).dialog('destroy');
              $.ajax({
                url: 'controllers/get_event.php',
                type: 'POST',
                data: {'id_event': id_event},
                dataType: 'json',
                success: function (data) {
                  $('#gen_new_content form #new_event_title').val(data.evenement_title);
                  $('#gen_new_content form #new_event_duree').val(data.evenement_duree);
                  $('#gen_new_content form #new_event_chef_projet').val(data.chef_projet);
                  $('#gen_new_content form #new_event_statut').val(data.evenement_statut);
                  $('#gen_new_content form #new_event_date').val(data.evenement_date);
                  $('#gen_new_content form #new_event_moment').val(data.evenement_moment);
                  $('#gen_new_content form #user_id').val(data.utilisateur);
                  $('#gen_new_content form #new_event_desc').val(data.evenement_desc);
                  $('#gen_new_content form #new_event_ticket').val(data.evenement_ticket);
                  $("#new_event_duree").attr('max', time.left + time.current ).attr('value', 0);
                  $("#gen_new_content").dialog({
                    bgiframe: true,
                    resizable: true,
                    height: 400,
                    width: 500,
                    modal: true,
                    beforeclose: function (event, ui) {
                      $("#new_event_title").val("");
                      $("#new_event_duree").val("");
                      $("#new_event_duree").attr('max', time.left ).attr('value', 0.5);
                      $("#new_event_statut").val("");
                      $("#new_event_date").val("");
                      $("#new_event_moment").val("");
                      $("#user_id").val("");
                      $("#new_event_desc").val("");
                      $("#new_event_ticket").val("");
                    },
                    buttons: {
                      'Enregistrer': function () {
                        var dataForm = $('#gen_new_content').find('form').serialize() + "&id_event=" + id_event;
                        var url = $('#gen_new_content').find('form').attr('action');

                        if ($("#new_event_duree").val() > time.left + time.current) {
                          alertify.alert("Vous ne pouvez pas dépasser 0.5 par demi-journée ! ", function(){
                            alertify.error('Pas plus haut que le bord ! ');
                            console.log('bug');
                          });
                          return;
                        }

                        else {
                          $.ajax({
                            url: 'controllers/edit_event.php',
                            type: 'POST',
                            data: dataForm,
                            dataType: 'json',
                            success: function (data) {
                              window.location.reload();
                            }
                          });
                        }
                        $(this).dialog('destroy');
                      },
                      'Annuler': function () {
                        $("#new_event_title").val("");
                        $("#new_event_duree").val("");
                        $("#new_event_statut").val("");
                        $("#new_event_date").val("");
                        $("#new_event_moment").val("");
                        $("#new_event_desc").val("");
                        $("#new_event_ticket").val("");
                        $(this).dialog('destroy');
                      }
                    }
                  });
                }
              });
            },
            'Annuler': function () {
              $(this).dialog('destroy');
            }
          }
        })
      }
    });
  });

  $(".calendar_td").click(function() {
	var object_clicked = $(this);
    current_event_selected = object_clicked;
  });
  
  $(".cell_am, .cell_pm").click(function(evt) {
	evt.stopPropagation();
	var object_clicked = $(this);
    current_event_selected = object_clicked;
  });

  document.onkeydown = function(evt) {	
    evt = evt || window.event;
    if (evt.ctrlKey && evt.keyCode == 67) {
		// CTRL+C
		element_to_copy = current_event_selected;
    }
	
	if (evt.ctrlKey && evt.keyCode == 88) {
		// CTRL+X
		element_to_cut = current_event_selected;
    }
	
	if (evt.ctrlKey && evt.keyCode == 86) {
		// CTRL+V
		var element_planning = current_event_selected.attr("id");

		// COPY / PASTE
		if ( (element_planning.length > 11) && element_to_copy != null) {

			var id_event_dup = element_to_copy.attr("id");
			var utilisateur = element_planning.substring(12);
			var event_date = element_planning.substring(0, 8);
			var event_moment = element_planning.substring(9, 11);
			
			var data = "utilisateur="+utilisateur+"&event_date="+event_date+"&event_moment="+event_moment+"&id_event_dup=" + id_event_dup;
			
			$.ajax({
			  url: 'controllers/duplicate_event.php',
			  type: 'POST',
			  data: data,
			  success: function (data) {
				window.location.reload();
        }
			});
			
			current_event_selected = null;
			element_to_copy = null;
		}
		// CUT / PASTE
		else if ( (element_planning.length > 11) && element_to_cut != null) {
			
			var id_event = element_to_cut.attr("id");
			var utilisateur = element_planning.substring(12);
			var event_date = element_planning.substring(0, 8);
			var event_moment = element_planning.substring(9, 11);
			
			var data = "utilisateur="+utilisateur+"&event_date="+event_date+"&event_moment="+event_moment+"&id_event=" + id_event;
			
			$.ajax({
			  url: 'controllers/move_event.php',
			  type: 'POST',
			  data: data,
			  success: function (data) {
				window.location.reload();				
			  }
			});

			current_event_selected = null;
			element_to_cut = null;
		  }
    }
  };
});
