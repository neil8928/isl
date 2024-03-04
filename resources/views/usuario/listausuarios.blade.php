@extends('template')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
@stop
@section('section')


	<div class="be-content">
		<div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Lista de Usuarios
                </div>
                <div class="panel-body">
                  <table id="table1" class="table table-striped table-hover table-fw-widget">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Perfil</th>
                        <th>Usuario Mobil</th>
                        <th>Opción</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach($listausuarios as $item)
                        <tr>
                          <td>{{$item->NombrePersona}}</td>
                          <td>{{$item->ApellidoPaterno}} {{$item->ApellidoMaterno}}</td>
                          <td>{{$funcion->funciones->que_rol_que_tiene_usuario($item->IdUsuariaIsl)}}</td>
                          <td> 
                            @if( is_null($item->rol_id) or $item->rol_id == '')  
                              <span class="icon mdi mdi-close"></span> 
                            @else 
                              <span class="icon mdi mdi-check"></span>
                            @endif
                          </td>
                          <td class="rigth">
                            <div class="btn-group btn-hspace">
                              <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                              <ul role="menu" class="dropdown-menu pull-right">
                                <li>
                                  <a href="{{ url('/modificar-usuario/'.$idopcion.'/'.$item->IdUsuariaIsl) }}">
                                    Modificar
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>                    
                      @endforeach

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
		</div>
	</div>

@stop

@section('script')


	<script src="{{ asset('public/lib/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.html5.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.flash.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.print.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.colVis.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/lib/datatables/plugins/buttons/js/buttons.bootstrap.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/js/app-tables-datatables.js?v='.$version) }}" type="text/javascript"></script>


    <script type="text/javascript">
      $(document).ready(function(){
        //initialize the javascript
        App.init();
        App.dataTables();
        $('[data-toggle="tooltip"]').tooltip(); 
      });
    </script> 
@stop