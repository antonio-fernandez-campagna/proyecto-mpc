function process_data(a,e){var t="";return a.forEach(function(a){t+="<div class='card mb-5 col-3 ml-5 card-medicines'><a data-toggle='modal' href='#modall' style='width:70%'><img class='card-img-top' src='"+a.url+"'></a><div class='card-block'><h4 class='card-title'>"+a.nombre+"</h4><div class='meta'><a data-toggle='modal' href='#modal'>"+a.categoria+"</a></div><div class='card-text'>"+a.efecto+"</div><form action='index.php?action=addPresciption&id="+a.id+"' method='post'><div class='collapse mt-2' id='collapseExample"+a.id+"'><div class='card card-body'><div class='meta'><span class='mt-2'>Efecto Secundario</span></div>"+a.efecto_secundario+"</div><div class='row mt-4'><div class='col-6'><div class='form-group'><input type='number' class='form-control quantityValue'  placeholder='Cantidad'  min='1' value='1' name='quantity'></div></div><div class='col-6'><div class='form-check mt-2'><input type='checkbox' class='form-check-input quantity' value='y' name='chronic'><label class='form-check-label' for='exampleCheck1'>Medicamento crónico</label></div></div></div><div class='form-group'><label for='comment'>Observación</label><textarea class='form-control' rows='5' id='comment' name='observation'></textarea></div><div class='form-group'><label for='usr'>Número de chip:</label><input type='text' class='form-control'  id='nChip' name='chip' value='"+e+"' ></div><button name='id' type='submit' class='btn btn-outline-danger float-right btn-sm' data-toggle='collapse' data-target='#collapseExample'>Confirmar receta</button></div></form></div><div class='card-footer mb-2'><button name='id' value='' class='btn btn-outline-success float-right btn-sm' data-toggle='collapse' data-target='#collapseExample"+a.id+"'>Recetar</button></div></div>"}),t}jQuery(document).ready(function(){function t(){jQuery(".accordion .accordion-section-title").removeClass("active"),jQuery(".accordion .accordion-section-content").slideUp(300).removeClass("open")}$(".quantity").click(function(){1==$(this).prop("checked")&&$(".quantityValue").val(1)}),$(".recetar").click(function(){$(this).hasClass("activated")?($(this).removeClass("activated"),$(this).html("recetar")):($(this).addClass("activated"),$(this).html("cerrar"))}),$(".filterProduct").change(function(){$(this).hasClass("activated")?$(this).removeClass("activated"):$(this).addClass("activated"),$category=[],$(".activated").each(function(){$category.push($(this).attr("id"))}),$(".card").each(function(){$category.length&&-1===jQuery.inArray($(this).find(".categoria").attr("id"),$category)?$(this).hide():$(this).show()})}),jQuery(".accordion-section-title").click(function(a){var e=jQuery(this).attr("href");jQuery(a.target).is(".active")?t():(t(),jQuery(this).addClass("active"),jQuery(".accordion "+e).slideDown(300).addClass("open")),a.preventDefault()});var c="";$("#efectSearch").keyup(function(){var a=$("#efectSearch").val();return chip=document.getElementById("nChip"),c||null==chip||(c=chip.value),$.ajax({type:"POST",url:"controllers/searchEfect_controller.php",data:{word:a.toString()},success:function(a){medicines=$.parseJSON(a),$(".card-medicines").remove();var e=process_data(medicines,c);$(".medicine-container").append(e)}}),!1});var e,a=(e=null,$.ajax({async:!1,type:"GET",global:!1,url:"controllers/autocomplete_controller.php",success:function(a){e=$.parseJSON(a)}}),e);$("#autocomplete").autocomplete({lookup:a})});