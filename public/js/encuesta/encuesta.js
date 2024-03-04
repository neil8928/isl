
$(document).ready(function(){

    var carpeta = $("#carpeta").val();

    $(".containerencuesta").on('click','#guardarencuesta', function(e) {


        var xml="";
        var contador=0;
        var xmlt="";
        var alertaMensajeGlobal="";
        var idopcion = $("#idopcion").html();
        var listapregunta = '';
        var _token      = $('#token').val();
         

        $('.preguntas').css("border", "1px solid #ccc");
        for (i=1; i<=$("#contadorUnico").html(); i++)
        {
            if($('input:radio[name=radio'+i+']').is(':checked')) {
                contr = i+1; 
                xml = xml + ($('input:radio[name=radio'+i+']:checked').val()) + '***';
                contador=contador+1;
            }else{
                $('.pregunta'+i).css("border", "2px solid #a94442");
                listapregunta = listapregunta + i + '-';
            }
        }

        for (i=1; i<=$("#contadorMultiple").html(); i++)
        {
            $('input:checkbox[name=checkbox'+i+']:checked').each(   
            function() {
                xml=xml+$(this).val()+'*';
                contador=contador+1;
            }
            );
        }

        var contadorunico       =   parseInt($("#contadorUnico").html());

        if(contador<contadorunico){
            $('html, body').animate({scrollTop:0}, 'slow'); 
            alerterrorajaxflotante('Complete la encuesta faltan contestar algunas ('+listapregunta+')');
            return false;
        }

        var persona_id              =   $("#persona_id").val();
        var dni                     =   $("#dni").val();
        var txtespecificar          =   $("#txtespecificar").val();

        abrircargando();

        $.ajax({
            type    :   "POST",
            url     :   carpeta+"/ajax-guardar-encuesta-trabajador",
            data    :   {
                            _token              : _token,
                            xml                 : xml,
                            txtespecificar      : txtespecificar,
                            persona_id          : persona_id,
                        },
            success: function (data) {

                if(data == '1'){
                    window.location.href = carpeta+"/realizar-encuesta/"+dni;
                    
                }else{
                    window.location.href = carpeta+"/termino-encuesta/"+dni;
                }
                
            },
            error: function (data) {
                cerrarcargando();
                error500(data);
            }
        });



    });

});





