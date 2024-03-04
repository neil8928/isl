<div class='listaordencompra'>
  <table id="tablesolicitud" class="table table table-hover table-fw-widget dt-responsive nowrap" style='width: 100%;'>
    <thead>
      <tr> 
        <th></th>
        <th>Proveedor</th>
        <th>N° O. Orden</th>
        <th>Fecha</th>
        <th>Moneda</th>
        <th>Sub total</th>
        <th>Impuesto</th>
        <th>Total</th>
        <th>Tipo Pago</th>
        <th>Estado</th>


      </tr>
    </thead>
    <tbody>
      @while ($row = $listaordencompra->fetch())

        <tr>
          <td></td>
          <td
          class='filaocm'
          data_ioc="{{$row['Id']}}"
          data_proveedor="{{$row['IdProveedor']}}"
          >
            <?php echo wordwrap($row['NombreProveedor'],25,"<br>\n"); ?>
          </td>

          <td>{{$row['NroOrden']}}</td>
          <td>{{date_format(date_create($row['FechaOrden']), 'd-m-Y')}}</td>
          <td>{{$row['Moneda']}}</td>
          <td>{{$row['SubTotal']}}</td>
          <td>{{$row['Impuesto']}}</td>
          <td>{{$row['Total']}}</td>
          <td>
              {{$funcion->funciones->data_tipo_pago($row['IdTipoPago'])->Nombre}}
          </td>
          <td>{{$row['EstadoOrden']}}</td>
        </tr>                    
        @endwhile
    </tbody>
  </table>
</div>


<div class="row orden_compra_materiales_atender modal_mobil">
  <div class="col-sm-12 col-mobil-top ajax_orden_compra_materiales_atender">

      <div class="panel panel-contrast">
        <div class="panel-heading panel-heading-contrast">
              <strong class='nombre_area'>Nombre cliente</strong>
              <span class="panel-subtitle c_documento-cuenta">documento - cuenta</span>                          
              <span class="mdi mdi-close-circle mdi-close-cliente"></span>
              <span class="mdi mdi-check-circle mdi-check-cliente"
                data_icl=''
                data_pcl=''
              ></span>
        </div>
      </div>
      <div class="panel-body">
          
      </div>

  </div>
</div>


@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
       App.dataTables();
    });
  </script> 
@endif
