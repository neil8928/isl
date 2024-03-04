  <div class="panel panel-contrast">
    <div class="panel-heading panel-heading-contrast">
          <strong class='glosa'>{{$headers['Glosa']}}</strong>
          <span class="panel-subtitle fecha_estado">
          {{$headers['Estado']}} / {{date_format(date_create($headers['Fecha']), 'd-m-Y')}}
          </span>                          
          <span class="mdi mdi-close-circle mdi-close-cliente"></span>


        <form method="POST" action="{{ url('/guardar-solicitud-materiales/'.$idopcion) }}" class="form-horizontal group-border-dashed form-pedido">
          {{ csrf_field() }}

          <input type="hidden" name="orden_asignacion_id" id='orden_asignacion_id' value="{{$headers['Id']}}">
          <input type="hidden" name="orden_asignacion_fecha" id='orden_asignacion_fecha' value="{{$headers['Fecha']}}">
          <input type="hidden" name="orden_asignacion_nrooa" id='orden_asignacion_nrooa' value="{{$headers['NroOA']}}">
          <input type="hidden" name="orden_asignacion_glosa" id='orden_asignacion_glosa' value="{{$headers['Glosa']}}">
          <input type="hidden" name="ind_unidad_asignada" id='ind_unidad_asignada' value="{{$headers['IndUnidadAsignacion']}}">
          <input type="hidden" name="id_unidad_asignacion" id='id_unidad_asignacion' value="{{$headers['IdUnidadAsignacion']}}">
          <input type="hidden" name="xml_productos" id='xml_productos' value="">
          <input type="hidden" name="unidad_asignada" id='unidad_asignada' value="{{$headers['UnidadAsignada']}}">



          <button type="submit" class="btn btn-space btn-success btn-big btn-guardar">
            <i class="icon mdi mdi-check"></i> Guardar
          </button>

        </form>


    </div>
  </div>
  <div class="panel-body">
      
    <div class="scroll_text_q">
        @while ($row = $detalle->fetch()) 
        <div  class='col-sm-12 productoseleccion col-mobil-top'
              data_id_oa_material                 = "{{$row['IdOA_Material']}}"
              data_id_material                    = "{{$row['IdMaterial']}}"
              data_id_oa                          = "{{$row['IdOrdenAsignacion']}}"
              data_ind_unidad_asignacion          = "{{$row['IndUnidadAsignacion']}}"
              data_id_unidad_asignacion           = "{{$row['IdUnidadAsignacion']}}"
              data_id_unidad_medida               = "{{$row['IdUnidadMedida']}}"
              data_cantidad_material_entregada    = "{{$row['CantidadMaterialEntregada']}}"
              data_cantidad_material_devuelto     = "{{$row['IdUnidadMedida']}}"
              data_costo_unitario                 = "{{$row['CostoUnitario']}}"
              data_id_sub_almacen                 = "{{$row['IdSubAlmacen']}}"
              data_id_almacen                     = "{{$row['IdAlmacen']}}"
              data_ind_descuento                  = "{{$row['IndDescuento']}}"
              data_monto_descuento                = "{{$row['MontoDescuento']}}"
              >

             <div class='panel panel-default panel-contrast'>
                  <div class='panel-heading cell-detail'>
                    {{$row['Material']}}
                    <span class='panel-subtitle cell-detail-producto'>Unidad Medida : 
                      @foreach($unidad as $item)
                        @if($row['IdUnidadMedida'] == $item->Id )
                            {{$item->Nombre}}
                        @endif
                      @endforeach
                    </span>

                    <span class='panel-subtitle cell-detail-producto'>Stock : {{$row['Stock']}} </span>
                    <span class='panel-subtitle cell-detail-producto'>Cantidad :
                      <input  type="tel"
                              id="cantidad" name='cantidad' 
                              value="{{$row['CantidadMaterial']}}" 
                              placeholder="Cantidad"
                              required = "" class="form-control input-sm importe" data-parsley-type="number"
                              autocomplete="off" data-aw="1"/>

                    </span>


                    <span class="mdi mdi-delete mdi-eliminar-material"></span>


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
