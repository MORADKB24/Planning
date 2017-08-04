(function() {
        
        var dndHandler = {
            
            draggedElement: null, // Propri�t� pointant vers l'�l�ment en cours de d�placement
            
            applyDragEvents: function(element) {
                
                element.draggable = true;

                var dndHandler = this; // Cette variable est n�cessaire pour que l'�v�nement "dragstart" ci-dessous acc�de facilement au namespace "dndHandler"
                
                element.addEventListener('dragstart', function(e) {
                    dndHandler.draggedElement = e.target; // On sauvegarde l'�l�ment en cours de d�placement
                    e.dataTransfer.setData('text/plain', ''); // N�cessaire pour Firefox
                }, false);
                
            },
     
            applyDropEvents: function(dropper) {
                
                dropper.addEventListener('dragover', function(e) {
                    e.preventDefault(); // On autorise le drop d'�l�ments
                    this.className = 'dropper drop_hover'; // Et on applique le design ad�quat � notre zone de drop quand un �l�ment la survole
                }, false);
                
                dropper.addEventListener('dragleave', function() {
                    this.className = 'dropper'; // On revient au design de base lorsque l'�l�ment quitte la zone de drop
					
                });
                
                var dndHandler = this; // Cette variable est n�cessaire pour que l'�v�nement "drop" ci-dessous acc�de facilement au namespace "dndHandler"

                dropper.addEventListener('drop', function(e) {

                    var target = e.target,
                        draggedElement = dndHandler.draggedElement, // R�cup�ration de l'�l�ment concern�
                        clonedElement = draggedElement.cloneNode(true); // On cr�� imm�diatement le clone de cet �l�ment
                    
                    while(target.className.indexOf('dropper') == -1) { // Cette boucle permet de remonter jusqu'� la zone de drop parente
                        target = target.parentNode;
                    }

                    target.className = 'dropper'; // Application du design par d�faut
                    
                    clonedElement = target.appendChild(clonedElement); // Ajout de l'�l�ment clon� � la zone de drop actuelle
                    dndHandler.applyDragEvents(clonedElement); // Nouvelle application des �v�nements qui ont �t� perdus lors du cloneNode()
                    
                    draggedElement.parentNode.removeChild(draggedElement); // Suppression de l'�l�ment d'origine
                    
					var element_planning = target.id;
					var utilisateur = element_planning.substring(12);
					var event_date = element_planning.substring(0, 8);
					var event_moment = element_planning.substring(9, 11);
					var id_event = draggedElement.id;
					
					
					var data = "utilisateur="+utilisateur+"&event_date="+event_date+"&event_moment="+event_moment+"&id_event=" + id_event;
					$.ajax({
					  url: 'controllers/move_event.php',
					  type: 'POST',
					  data: data,
					  dataType: 'json',
					  success: function (data) {
						window.location.reload();
					  }
					});
                });
                
            }
     
        };
        
        var elements = document.querySelectorAll('.draggable'),
            elementsLen = elements.length;
        
        for(var i = 0 ; i < elementsLen ; i++) {
			dndHandler.applyDragEvents(elements[i]); // Application des param�tres n�cessaires aux �l�ment d�pla�ables
        }
        
        var droppers = document.querySelectorAll('.dropper'),
            droppersLen = droppers.length;
        
        for(var i = 0 ; i < droppersLen ; i++) {
            dndHandler.applyDropEvents(droppers[i]); // Application des �v�nements n�cessaires aux zones de drop
        }

    })();