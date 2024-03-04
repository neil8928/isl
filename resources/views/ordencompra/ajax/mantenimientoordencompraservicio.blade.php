  <div class="panel panel-contrast">
    <div class="panel-heading panel-heading-contrast">
          <strong class='glosa'>{{$ordencompra['NombreProveedor']}}</strong>
          <span class="panel-subtitle fecha_estado">
          {{$ordencompra['EstadoOrden']}} / {{date_format(date_create($ordencompra['FechaOrden']), 'd-m-Y')}}
          </span>                          
          <span class="mdi mdi-close-circle mdi-close-cliente"></span>



          <form method="POST" action="{{ url('/aprobar-orden-compra-servicios/'.$idopcion) }}" class="form-horizontal group-border-dashed form-pedido">
            {{ csrf_field() }}

            <input type="hidden" name="orden_compra_id" id='orden_compra_id' value="{{$ordencompra['Id']}}">
            <input type="hidden" name="orden_compra_nroorden" id='orden_compra_nroorden' value="{{$ordencompra['NroOrden']}}">
            <input type="hidden" name="moneda_id" id='moneda_id' value="{{$ordencompra['IdMoneda']}}">
            <input type="hidden" name="trabajador_id" id='trabajador_id' value="{{$ordencompra['IdTrabajador']}}">
            <input type="hidden" name="trabajador_aprobacion_id" id='trabajador_aprobacion_id' value="{{$ordencompra['IdTrabajadorAprobacion']}}">
            <input type="hidden" name="proveedor_id" id='proveedor_id' value="{{$ordencompra['IdProveedor']}}">
            <input type="hidden" name="subtotal" id='subtotal' value="{{$ordencompra['SubTotal']}}">
            <input type="hidden" name="impuesto" id='impuesto' value="{{$ordencompra['Impuesto']}}">
            <input type="hidden" name="total" id='total' value="{{$ordencompra['Total']}}">
            <input type="hidden" name="fechaorden" id='fechaorden' value="{{$ordencompra['FechaOrden']}}">
            <input type="hidden" name="fechaentrega" id='fechaentrega' value="{{$ordencompra['FechaEntrega']}}">
            <input type="hidden" name="fechapago" id='fechapago' value="{{$ordencompra['FechaPago']}}">
            <input type="hidden" name="glosa" id='glosa' value="{{$ordencompra['Glosa']}}">
            <input type="hidden" name="notas" id='notas' value="{{$ordencompra['Notas']}}">
            <input type="hidden" name="centro_id" id='centro_id' value="{{$ordencompra['IdCentro']}}">
            <input type="hidden" name="tipo_pago_id" id='tipo_pago_id' value="{{$ordencompra['IdTipoPago']}}">
            <input type="hidden" name="xml_productos" id='xml_productos' value="">
            <button type="submit" class="btn btn-space btn-success btn-big btn-guardar btn-guardar-ocs">
              <i class="icon mdi mdi-check"></i> Guardar
            </button>

          </form>

    </div>
  </div>
  <div class="panel-body">
      
    <div class="scroll_text_q">


        @while ($row = $listaordencompraservicio->fetch())
        <div  class='col-sm-12 productoseleccion col-mobil-top'
              data_id_oc_servicio                 = "{{$row['Id']}}"
              data_id_servicio                    = "{{$row['IdServicio']}}"
              data_cantidad                       = "{{$row['Cantidad']}}"
              data_precio_unitario                = "{{$row['PrecioUnitario']}}"
              data_valor_venta                    = "{{$row['ValorVenta']}}"
              data_ind_igv                        = "{{$row['IndicadorIgv']}}"
              data_centro_costo                   = "{{$row['IdCentroCosto']}}"
              data_glosa                          = "{{$row['Glosa']}}"
              data_notas                          = "{{$row['Notas']}}"
              data_area                           = "{{$row['IdArea']}}"
              data_requerimiento_servicio         = "{{$row['IdRequerimientoServicio']}}"
              data_id_equipo                      = "{{$row['IdEquipo']}}"
              data_id_gasto_funcion               = "{{$row['IdGastoFuncion']}}"
              data_id_placa                       = "{{$row['IdPlaca']}}"
              data_id_viaje                       = "{{$row['IdViaje']}}"
              data_id_flujo_caja                  = "{{$row['IdFlujoCaja']}}"
              >

             <div class='panel panel-default panel-contrast'>
                  <div class='panel-heading cell-detail'>
                    {{$row['Servicio']}}
                    <span class='panel-subtitle cell-detail-producto'>Codigo : {{$row['CodigoServicio']}}</span>
                    <span class='panel-subtitle cell-detail-producto'>Cantidad : {{$row['Cantidad']}}</span>

                    <span class='panel-subtitle cell-detail-producto'>PU S/Imp : {{$row['PrecioUnitario']}}</span>
                    <span class='panel-subtitle cell-detail-producto'>Importe : 
                      {{number_format($row['Cantidad'] * $row['PrecioUnitario'], 4, '.', ',')}}
                    </span>

                  </div>
             </div>
        </div>
        @endwhile



    </div>

  </div>

<script type="text/javascript">

  $(document).ready(function(){
    //initialize the javascript
    $('.importe').inputmask({ 'alias': 'numeric', 
    'groupSeparator': ',', 'autoGroup': true, 'digits': 4, 
    'digitsOptional': false, 
    'prefix': '', 
    'placeholder': '0'});

  });
</script>
