
<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Sistemas web">
  <meta name="author" content="Jorge Francelli Saldaña Reyes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('public/img/icono/faviind.ico') }}">
  <title>Induamerica - Sistema web</title>


  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/material-design-icons/css/material-design-iconic-font.min.css') }} " />
  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/scroll/css/scroll.css') }} " />

  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/dataTables.bootstrap.min.css') }} "/>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datatables/css/responsive.dataTables.min.css') }} "/>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }} "/>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/select2/css/select2.min.css') }} "/>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/bootstrap-slider/css/bootstrap-slider.css') }} "/>
  <link rel="stylesheet" type="text/css" href="{{ asset('public/css/style.css?v='.$version) }} " />
  <link rel="stylesheet" type="text/css" href="{{ asset('public/css/oryza.css?v='.$version) }} " />

</head>

<body>


  <div class=" listapedidoosiris">
    <div class="main-content container-fluid main-content-mobile">
          <div class="row">
            <div class="col-sm-12 col-mobil">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Lista Encuestas
                </div>
                <div class="panel-body">
                <div class="col-sm-12">
                  <div class="panel panel-default">
                    <div class='listatablasolicitudes listajax'>

                      <div class='listasolicitudes'>
                        <table id="tablesolicitud" class="table table table-hover table-fw-widget dt-responsive nowrap" style='width: 100%;'>
                          <thead>
                            <tr> 
                              <th>Encuesta</th>
                              <th>Nombre y DNI</th>
                              <th>Fecha encuesta</th>
                              <th>Edad</th>
                              <th>Area</th>
                              <th>Doctor</th>
                              <th>Enfermera</th>                              
                              <th>Opciones</th>
                            </tr>
                          </thead>

                          <tbody>
                           @foreach($listaencuestas as $item)
                              <tr>



                                <td>{{$item->codigo}}</td>
            
                                <td>{{$item->trabajador->NombreCompleto}}<br>
                                    {{$item->trabajador->Dni}}
                                </td>
                                <td>{{date_format(date_create($item->fecha_crea), 'd-m-Y H:m:s')}}</td>
                                <td>{{$funcion->calculaedad($item->trabajador->FechaNacimiento)}}</td>
                                <td>{{$item->trabajador->Area}}</td>
                                <td>{{$item->cantidad_doctor}}</td>
                                <td>{{$item->cantidad_enfermera}}</td>                                
                                <td>
                                  <a href="{{ url('/detalle-encuesta-trabajador/'.Hashids::encode(substr($item->id, -8)))}}"  target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
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
            </div>
          </div>
    </div>
  </div>



  <script src="{{ asset('public/lib/jquery/jquery-2.1.3.min.js') }}" type="text/javascript"></script>
  
  <script src="{{ asset('public/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
 
  
  <script src="{{ asset('public/js/main.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/scroll/js/jquery.mousewheel.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/scroll/js/jquery-scrollpanel-0.7.0.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/scroll/js/scroll.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/js/general/general.js?v='.$version) }}" type="text/javascript"></script>

 
  <script src="{{ asset('public/js/general/inputmask/inputmask.js') }}" type="text/javascript"></script> 
  <script src="{{ asset('public/js/general/inputmask/inputmask.extensions.js') }}" type="text/javascript"></script> 
  <script src="{{ asset('public/js/general/inputmask/inputmask.numeric.extensions.js') }}" type="text/javascript"></script> 
  <script src="{{ asset('public/js/general/inputmask/inputmask.date.extensions.js') }}" type="text/javascript"></script> 
  <script src="{{ asset('public/js/general/inputmask/jquery.inputmask.js') }}" type="text/javascript"></script>

  <script src="{{ asset('public/lib/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/plugins/buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datatables/js/responsive.bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/js/app-tables-datatables.js?v='.$version) }}" type="text/javascript"></script>

  <script src="{{ asset('public/lib/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/jquery.nestable/jquery.nestable.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/moment.js/min/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/select2/js/select2.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/bootstrap-slider/js/bootstrap-slider.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/js/app-form-elements.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/lib/parsley/parsley.js') }}" type="text/javascript"></script>

  <script src="{{ asset('public/lib/jquery.niftymodals/dist/jquery.niftymodals.js') }}" type="text/javascript"></script>



  <script type="text/javascript">
    $(document).ready(function(){
      //initialize the javascript
      App.init();
      App.formElements();
      App.dataTables();
    });
  </script> 



 
</body>

</html>
