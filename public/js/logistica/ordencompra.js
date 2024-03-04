
$(document).ready(function(){

    var carpeta = $("#carpeta").val();


    $('#buscarordencompraservicio').on('click', function(event){

        event.preventDefault();
        var finicio     = $('#finicio').val();
        var ffin        = $('#ffin').val();
        var idopcion    = $('#idopcion').val();
        var _token      = $('#token').val();
        
        $(".listajax").html("");
        abrircargando();

        $.ajax({
            type    :   "POST",
            url     :   carpeta+"/ajax-listado-de-orden-compra-servicio",
            data    :   {
                            _token  : _token,
                            idopcion  : idopcion,
                            finicio : finicio,
                            ffin    : ffin
                        },
            success: function (data) {
                cerrarcargando();
                $(".listajax").html(data);

            },
            error: function (data) {
                cerrarcargando();
                error500(data);
            }
        });

    });


    $(".listajax").on('click','.filaocs', function(e) {

        var data_ioc        =   $(this).attr('data_ioc');
        var data_proveedor  =   $(this).attr('data_proveedor');
        var _token          =   $('#token').val();
        var idopcion        =   $('#idopcion').val();


        $.ajax({
              type    :   "POST",
              url     :   carpeta+"/ajax-mantenimiento-orden-compra-servicios",
              data    :   {
                            _token                : _token,
                            data_ioc              : data_ioc,
                            data_proveedor        : data_proveedor,
                            idopcion              : idopcion,
                          },
            beforeSend: function() {
                $('.ajax_orden_compra_servicios_atender').html("<div class='row text-center'><div class='lds-roller'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>");
            },
            success: function (data) {
                $('.ajax_orden_compra_servicios_atender').html(data);
            },
            error: function (data) {
                error500(data);
                setTimeout(function(){ $('.listaclientes').toggle("slow");  $('.orden_compra_servicios_atender').toggle("slow");}, 2000);
            }
        });

        $('.listaordencompra').toggle("slow");
        $('.orden_compra_servicios_atender').toggle("slow");

    });




    $('#buscarordencompramaterial').on('click', function(event){

        event.preventDefault();
        var finicio     = $('#finicio').val();
        var ffin        = $('#ffin').val();
        var idopcion    = $('#idopcion').val();
        var _token      = $('#token').val();
        
        $(".listajax").html("");
        abrircargando();

        $.ajax({
            type    :   "POST",
            url     :   carpeta+"/ajax-listado-de-orden-compra-materiales",
            data    :   {
                            _token  : _token,
                            idopcion  : idopcion,
                            finicio : finicio,
                            ffin    : ffin
                        },
            success: function (data) {
                cerrarcargando();
                $(".listajax").html(data);

            },
            error: function (data) {
                cerrarcargando();
                error500(data);
            }
        });

    });


    $(".listajax").on('click','.filaocm', function(e) {

        var data_ioc        =   $(this).attr('data_ioc');
        var data_proveedor  =   $(this).attr('data_proveedor');
        var _token          =   $('#token').val();
        var idopcion        =   $('#idopcion').val();

        $.ajax({
              type    :   "POST",
              url     :   carpeta+"/ajax-mantenimiento-orden-compra-materiales",
              data    :   {
                            _token                : _token,
                            data_ioc              : data_ioc,
                            data_proveedor        : data_proveedor,
                            idopcion              : idopcion,
                          },
            beforeSend: function() {
                $('.ajax_orden_compra_materiales_atender').html("<div class='row text-center'><div class='lds-roller'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>");
            },
            success: function (data) {
                $('.ajax_orden_compra_materiales_atender').html(data);
            },
            error: function (data) {
                error500(data);
                setTimeout(function(){ $('.listaclientes').toggle("slow");  $('.orden_compra_materiales_atender').toggle("slow");}, 2000);
            }
        });

        $('.listaordencompra').toggle("slow");
        $('.orden_compra_materiales_atender').toggle("slow");

    });



    $(".listajax").on('click','.mdi-close-cliente', function(e) {

        $('.listaordencompra').toggle("slow");
        $('.orden_compra_materiales_atender').toggle("slow");
        $('.orden_compra_servicios_atender').toggle("slow");

    });


    //guardar pedido
    $(".listajax").on('click','.btn-guardar-ocm', function(e) {

        // validacion productos
        data = agregar_producto_hidden();
        if(data.length<=0){alertdangermobil("Tiene que ver por lo menos un material"); return false;}
        var datastring = JSON.stringify(data);
        $('#xml_productos').val(datastring);
        abrircargando();
        //abrircargando();
        return true;
    });


    //guardar pedido
    $(".listajax").on('click','.btn-guardar-ocs', function(e) {

        // validacion productos
        data = agregar_producto_hidden_s();
        if(data.length<=0){alertdangermobil("Tiene que ver por lo menos un producto"); return false;}
        var datastring = JSON.stringify(data);
        $('#xml_productos').val(datastring);
        abrircargando();
        //abrircargando();
        return true;
    });

});



function agregar_producto_hidden_s(){

    var data = [];
    $(".listajax .productoseleccion").each(function(){


        var data_id_oc_servicio                     = $(this).attr('data_id_oc_servicio');
        var data_id_servicio                        = $(this).attr('data_id_servicio');
        var data_cantidad                           = $(this).attr('data_cantidad');
        var data_precio_unitario                    = $(this).attr('data_precio_unitario');
        var data_valor_venta                        = $(this).attr('data_valor_venta');
        var data_ind_igv                            = $(this).attr('data_ind_igv');
        var data_centro_costo                       = $(this).attr('data_centro_costo');
        var data_glosa                              = $(this).attr('data_glosa');
        var data_notas                              = $(this).attr('data_notas');
        var data_area                               = $(this).attr('data_area');
        var data_requerimiento_servicio             = $(this).attr('data_requerimiento_servicio');
        var data_id_equipo                          = $(this).attr('data_id_equipo');
        var data_id_gasto_funcion                   = $(this).attr('data_id_gasto_funcion');
        var data_id_placa                           = $(this).attr('data_id_placa');
        var data_id_viaje                           = $(this).attr('data_id_viaje');
        var data_id_flujo_caja                      = $(this).attr('data_id_flujo_caja');


        data.push({
            data_id_oc_servicio                 : data_id_oc_servicio,
            data_id_servicio                    : data_id_servicio,
            data_cantidad                       : data_cantidad,
            data_precio_unitario                : data_precio_unitario,
            data_valor_venta                    : data_valor_venta,
            data_ind_igv                        : data_ind_igv,
            data_centro_costo                   : data_centro_costo,
            data_glosa                          : data_glosa,
            data_notas                          : data_notas,
            data_area                           : data_area,
            data_requerimiento_servicio         : data_requerimiento_servicio,
            data_id_equipo                      : data_id_equipo,
            data_id_gasto_funcion               : data_id_gasto_funcion,
            data_id_placa                       : data_id_placa,
            data_id_viaje                       : data_id_viaje,
            data_id_flujo_caja                  : data_id_flujo_caja,
        });



    });


    return data;
}

function agregar_producto_hidden(){

    var data = [];
    $(".listajax .productoseleccion").each(function(){


        var data_id_oc_material                     = $(this).attr('data_id_oc_material');
        var data_id_material                        = $(this).attr('data_id_material');
        var data_cantidad_material                  = $(this).attr('data_cantidad_material');
        var data_costo_unitario                     = $(this).attr('data_costo_unitario');
        var data_precio_total                       = $(this).attr('data_precio_total');
        var data_id_sub_almacen                     = $(this).attr('data_id_sub_almacen');
        var data_cantidad_material_pendiente        = $(this).attr('data_cantidad_material_pendiente');
        var data_ind_igv                            = $(this).attr('data_ind_igv');
        var data_id_unidad_medida                   = $(this).attr('data_id_unidad_medida');
        var data_centro_costo                       = $(this).attr('data_centro_costo');
        var data_requerimiento_material             = $(this).attr('data_requerimiento_material');
        var data_glosa                              = $(this).attr('data_glosa');
        var data_notas                              = $(this).attr('data_notas');
        var data_area                               = $(this).attr('data_area');

        data.push({
            data_id_oc_material                 : data_id_oc_material,
            data_id_material                    : data_id_material,
            data_cantidad_material              : data_cantidad_material,
            data_costo_unitario                 : data_costo_unitario,
            data_precio_total                   : data_precio_total,
            data_id_sub_almacen                 : data_id_sub_almacen,
            data_cantidad_material_pendiente    : data_cantidad_material_pendiente,
            data_ind_igv                        : data_ind_igv,
            data_id_unidad_medida               : data_id_unidad_medida,
            data_centro_costo                   : data_centro_costo,
            data_requerimiento_material         : data_requerimiento_material,
            data_glosa                          : data_glosa,
            data_notas                          : data_notas,
            data_area                           : data_area,
        });



    });


    return data;
}
