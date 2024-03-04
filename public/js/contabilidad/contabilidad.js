$(document).ready(function(){


	var carpeta = $("#carpeta").val();


    $('#buscarfactura').on('click', function(event){

    	event.preventDefault();
    	var finicio 	= $('#finicio').val();
    	var ffin 		= $('#ffin').val();
    	var _token 		= $('#token').val();
		$(".listatablafactura").html("");
		abrircargando();

		
		$(".menu-roles li").removeClass( "active" )
		$(this).parents('li').addClass("active");

        $.ajax({
            type	: 	"POST",
            url		: 	carpeta+"/ajax-listado-de-facturas-entrefechas",
            data	: 	{
            				_token	: _token,
            				finicio : finicio,
            				ffin 	: ffin
            	 		},
            success: function (data) {
            	cerrarcargando();
            	$(".listatablafactura").html(data);

            },
            error: function (data) {
            	cerrarcargando();
                console.log('Error:', data);
            }
        });

    });	


    $('#enviarfactura').on('click', function(event){
    	event.preventDefault();

    	$('#fechainicio').val($('#finicio').val());
    	$('#fechafin').val($('#ffin').val());

    	data = dataenviar();
    	if(data.length<=0){alerterrorajax('Seleccione por lo menos una factura'); return false;}
    	var datastring = JSON.stringify(data);
    	$('#factura').val(datastring);
    	abrircargando();
    	$( "#formfactura" ).submit();
    });




	$(".listatablafactura").on('click','label', function() {

		var input 	= $(this).siblings('input');
		var accion	= $(this).attr('data-atr');

		var name	= $(this).attr('name');
		var check 	= -1;
		var estado 	= -1;
		

		if($(input).is(':checked')){

			check 	= 0;
			estado 	= false;

		}else{

			check 	= 1;
			estado 	= true;

		}

		validarrelleno(accion,name,estado,check);
		
	});


	function dataenviar(){
    	var data = [];
	    $(".listatabla tr").each(function(){
	    	check 	= $(this).find('input');
	    	nombre 	= $(this).find('input').attr('id');
	    	if(nombre != 'todo'){
		    	if($(check).is(':checked')){
				    data.push({
				    	serie: $(check).attr("data-serie"),
				    	nro: $(check).attr("data-nro"),
				    	ruc: $(check).attr("data-ruc"),
				    	fecha: $(check).attr("data-fecha"),
				    	rz: $(check).attr("data-rz"),
				    	moneda: $(check).attr("data-moneda"),
				    	importe: $(check).attr("data-importe")
				    });
		    	}	    		
			}
    	});
    	return data;
	}






	function validarrelleno(accion,name,estado,check,token){

		if (accion=='todas') {

        	var table = $('#tfactura').DataTable();
		    $(".listatabla tr").each(function(){
		    	nombre = $(this).find('input').attr('id');
		    	if(nombre != 'todo'){
		    		$(this).find('input').prop("checked", estado);
		    	}
	    	});
		}else{
			sw = 0;
			if(estado){
				$(".listatabla tr").each(function(){

			    	nombre = $(this).find('input').attr('id');
			    	if(nombre != 'todo'){
						if(!($(this).find('input').is(':checked'))){
							sw = sw + 1;
						}
			    	}

	    		});
	    		if(sw==1){
	    			$("#todo").prop("checked", estado);
	    		}
			}else{
				$("#todo").prop("checked", estado);
			}			
		}
	}




});