<table id="table1" class="table table-striped table-striped dt-responsive nowrap listatabla" style='width: 100%;'>

  <thead>
    <tr>
      <th>
        <div class="text-center be-checkbox be-checkbox-sm">
          <input  type="checkbox"
                  class="todo"
                  id="todo"
          >
          <label  for="todo"
                  data-atr = "todas"
                  class = "checkbox"                    
                  name="todo"
            ></label>
        </div>
      </th>
      <th>Id</th>
      <th>Tipo Documento</th>
      <th>Serie-Correlativo</th>

      <th>Fecha Venta</th>
      <th>DNI/RUC</th>
      <th>Cliente</th>
      <th>Moneda</th>
      <th>Importe</th>



    </tr>
  </thead>
  <tbody>

    @foreach($listadocumento as $index => $item)
      <tr>
        <td>  
          <div class="text-center be-checkbox be-checkbox-sm">
            <input  type="checkbox"
              class="{{Hashids::encode($item->id)}}"
              id="{{Hashids::encode($item->id)}}" 
              data-serie = "{{$item->nroSerie}}"
              data-nro = "{{$item->nroFactura}}"
              data-ruc = "{{$item->nroRucEmisor}}"
              data-fecha = "{{$item->fechaEmisionDesc}}"
              data-rz = "{{$item->nroRucEmisorDesc}}"
              data-moneda = "{{$item->codigoMonedaDesc}}"
              data-importe = "{{$item->importeTotalDesc}}"
              >

            <label  for="{{Hashids::encode($item->id)}}"
                  data-atr = "ver"
                  class = "checkbox"                    
                  name="{{Hashids::encode($item->id)}}"
            ></label>
          </div>
        </td>
        <td>{{$index+1}}</td>
        <td>Factura</td>
        <td>{{$item->nroSerie}}-{{$funcion->funciones->getcodigofactura($item->nroFactura)}}</td>

        <td>{{$item->fechaEmisionDesc}}</td>

        <td>{{$item->nroRucEmisor}}</td> 
        <td>{{substr(str_replace($item->nroRucEmisor." - ","",$item->nroRucEmisorDesc),0,50)}}</td>
        <td>{{$item->codigoMonedaDesc}}</td>
        <td>{{$item->importeTotalDesc}}</td>


      </tr>                    
    @endforeach

  </tbody>
</table>


@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
       App.dataTables();
    });
  </script> 
@endif