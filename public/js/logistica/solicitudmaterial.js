
$(document).ready(function(){

    var carpeta = $("#carpeta").val();

    $('#buscarsolicitudesmateriales').on('click', function(event){

        event.preventDefault();
        var finicio     = $('#finicio').val();
        var ffin        = $('#ffin').val();
        var idopcion    = $('#idopcion').val();
        var _token      = $('#token').val();
        $(".listatablasolicitudes").html("");
        abrircargando();

        $.ajax({
            type    :   "POST",
            url     :   carpeta+"/ajax-listado-de-solicitudes-materiales",
            data    :   {
                            _token  : _token,
                            idopcion  : idopcion,
                            finicio : finicio,
                            ffin    : ffin
                        },
            success: function (data) {
                cerrarcargando();
                $(".listatablasolicitudes").html(data);

            },
            error: function (data) {
                cerrarcargando();
                error500(data);
            }
        });

    });



    $(".listatablasolicitudes").on('click','.filaconformidad', function(e) {

        var data_ioa        =   $(this).attr('data_ioa');
        var _token          =   $('#token').val();
        var idopcion        =   $('#idopcion').val();

        $.ajax({
              type    :   "POST",
              url     :   carpeta+"/ajax-mantenimiento-solicitud-materiales",
              data    :   {
                            _token                : _token,
                            data_ioa              : data_ioa,
                            idopcion               : idopcion,
                          },
            beforeSend: function() {
                $('.ajax_conformidad_solicitud').html("<div class='row text-center'><div class='lds-roller'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>");
            },
            success: function (data) {
                $('.ajax_conformidad_solicitud').html(data);
            },
            error: function (data) {
                error500(data);
                setTimeout(function(){ $('.listaclientes').toggle("slow");  $('.conformidadsolicitud').toggle("slow");}, 2000);
            }
        });

        $('.listasolicitudes').toggle("slow");
        $('.conformidadsolicitud').toggle("slow");

    });


    $(".listatablasolicitudes").on('click','.mdi-close-cliente', function(e) {

        $('.listasolicitudes').toggle("slow");
        $('.conformidadsolicitud').toggle("slow");

    });

    $(".listatablasolicitudes").on('click','.mdi-eliminar-material', function(e) {
        var padre   =   $(this).parents('.productoseleccion ');
        padre.remove();
        alertmobil("Material eliminado");
    });




    //guardar pedido

    $(".listatablasolicitudes").on('click','.btn-guardar', function(e) {

        // validacion productos
        data = agregar_producto_hidden();
        if(data.length<=0){alertdangermobil("Tiene que ver por lo menos un material"); return false;}
        var datastring = JSON.stringify(data);
        $('#xml_productos').val(datastring);

        abrircargando();
        return true;
    });




});



function agregar_producto_hidden(){

    var data = [];
    $(".listatablasolicitudes .productoseleccion").each(function(){


        var data_id_oa_material                     = $(this).attr('data_id_oa_material');
        var data_id_material                        = $(this).attr('data_id_material');
        var data_id_oa                              = $(this).attr('data_id_oa');
        var data_ind_unidad_asignacion              = $(this).attr('data_ind_unidad_asignacion');
        var data_id_unidad_asignacion               = $(this).attr('data_id_unidad_asignacion');
        var data_id_unidad_medida                   = $(this).attr('data_id_unidad_medida');
        var data_cantidad_material_entregada        = $(this).attr('data_cantidad_material_entregada');
        var data_cantidad_material_devuelto         = $(this).attr('data_cantidad_material_devuelto');
        var data_costo_unitario                     = $(this).attr('data_costo_unitario');
        var data_id_sub_almacen                     = $(this).attr('data_id_sub_almacen');
        var data_ind_descuento                      = $(this).attr('data_ind_descuento');
        var data_monto_descuento                    = $(this).attr('data_monto_descuento');
        var data_cantidad                           = $(this).find('#cantidad').val();
        var data_id_almacen                         = $(this).attr('data_id_almacen');


        data.push({
            data_id_oa_material                 : data_id_oa_material,
            data_id_material                    : data_id_material,
            data_id_oa                          : data_id_oa,
            data_ind_unidad_asignacion          : data_ind_unidad_asignacion,
            data_id_unidad_asignacion           : data_id_unidad_asignacion,
            data_id_unidad_medida               : data_id_unidad_medida,
            data_cantidad_material_entregada    : data_cantidad_material_entregada,
            data_cantidad_material_devuelto     : data_cantidad_material_devuelto,
            data_costo_unitario                 : data_costo_unitario,
            data_id_sub_almacen                 : data_id_sub_almacen,
            data_ind_descuento                  : data_ind_descuento,
            data_monto_descuento                : data_monto_descuento,
            data_cantidad                       : data_cantidad,
            data_id_almacen                     : data_id_almacen,
        });



    });


    return data;
}