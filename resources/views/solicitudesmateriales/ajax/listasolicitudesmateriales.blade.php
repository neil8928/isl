<div class='listasolicitudes'>
  <table id="tablesolicitud" class="table table table-hover table-fw-widget dt-responsive nowrap" style='width: 100%;'>
    <thead>
      <tr> 
        <th></th>
        <th>Glosa</th>
        <th>Fecha</th>
        <th>N° O. Asignacion</th>
        <th>Unidad</th>
        <th>Asignado a</th>
        <th>Estado</th>

        <th>Usuario</th>
      </tr>
    </thead>
    <tbody>
     @foreach($listasolicitudes as $item)
        <tr>
          <td></td>
          <td
          class='filaconformidad'
          data_ioa='{{$item->Id}}'
          opcion='{{$item->Id}}'
          >
            @if($item->Glosa == '')
                -
            @else
                <?php echo wordwrap($item->Glosa,25,"<br>\n"); ?>   
            @endif


          </td>
          <td>{{date_format(date_create($item->Fecha), 'd-m-Y')}}</td>
          <td>{{$item->NroOA}}</td>
          <td>{{$item->IndUnidadAsignacion}}</td>
          <td>{{$item->UnidadAsignada}}</td>
          <td><span class="badge badge-default">{{$item->Estado}}</span></td>
          <td>{{$item->Usuario}}</td>
        </tr>                    
      @endforeach
    </tbody>
  </table>
</div>


<div class="row conformidadsolicitud modal_mobil">
  <div class="col-sm-12 col-mobil-top ajax_conformidad_solicitud">

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
