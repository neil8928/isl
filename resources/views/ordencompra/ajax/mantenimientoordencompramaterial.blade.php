  <div class="panel panel-contrast">
    <div class="panel-heading panel-heading-contrast">
          <strong class='glosa'>{{$ordencompra['NombreProveedor']}}</strong>
          <span class="panel-subtitle fecha_estado">
          {{$ordencompra['EstadoOrden']}} / {{date_format(date_create($ordencompra['FechaOrden']), 'd-m-Y')}}
          </span>                          
          <span class="mdi mdi-close-circle mdi-close-cliente"></span>



          <form method="POST" action="{{ url('/aprobar-orden-compra-materiales/'.$idopcion) }}" class="form-horizontal group-border-dashed form-pedido">
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
            <button type="submit" class="btn btn-space btn-success btn-big btn-guardar btn-guardar-ocm">
              <i class="icon mdi mdi-check"></i> Guardar
            </button>

          </form>

    </div>
  </div>
  <div class="panel-body">
      
    <div class="scroll_text_q">


        @while ($row = $listaordencompramaterial->fetch())
        <div  class='col-sm-12 productoseleccion col-mobil-top'
              data_id_oc_material                 = "{{$row['Id']}}"
              data_id_material                    = "{{$row['IdMaterial']}}"
              data_cantidad_material              = "{{$row['CantidadMaterial']}}"
              data_costo_unitario                 = "{{$row['CostoUnitario']}}"
              data_precio_total                   = "{{$row['PrecioTotal']}}"
              data_id_sub_almacen                 = "{{$row['IdSubAlmacen']}}"
              data_cantidad_material_pendiente    = "{{$row['CantidadMaterialPendiente']}}"
              data_ind_igv                        = "{{$row['IndicadorIgv']}}"                            
              data_id_unidad_medida               = "{{$row['IdUnidadMedida']}}"
              data_centro_costo                   = "{{$row['IdCentroCosto']}}"
              data_requerimiento_material         = "{{$row['IdRequerimientoMaterial']}}"
              data_glosa                          = "{{$row['Glosa']}}"
              data_notas                          = "{{$row['Notas']}}"
              data_area                           = "{{$row['IdArea']}}"
              >

             <div class='panel panel-default panel-contrast'>
                  <div class='panel-heading cell-detail'>
                    {{$row['Material']}}
                    <span class='panel-subtitle cell-detail-producto'>Unidad Medida : {{$row['UnidadMedida']}}</span>


                    <span class='panel-subtitle cell-detail-producto'>Cantidad : {{$row['CantidadMaterial']}}</span>
                    <span class='panel-subtitle cell-detail-producto'>Costo : {{$row['CostoUnitario']}}</span>
                    <span class='panel-subtitle cell-detail-producto'>Precio : 
                    {{number_format($row['PrecioTotal']/$row['CantidadMaterial'], 4, '.', ',')}}
                  </span>
                    <span class='panel-subtitle cell-detail-producto'>Total : {{$row['PrecioTotal']}}</span>

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
