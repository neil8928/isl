<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistemas multiplataforma">
    <meta name="author" content="Jorge Francelli Saldaña Reyes">
    <link rel="icon" href="{{ asset('public/img/icono/faviind.ico') }}">


    <title>DETALLE ENCUESTA {{$persona->NombreCompleto}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/material-design-icons/css/material-design-iconic-font.min.css') }} "/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}" type="text/css"/>

  	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/oryza.css?v='.$version) }} " />
    <link rel="stylesheet" href="{{ asset('public/css/encuesta.css?v='.$version) }}" type="text/css"/>


  </head>
  <body class="be-splash-screen containerencuesta">

    <div class="be-wrapper be-login">
      <div class="be-content">
		<div class="container" style="margin-top:10px;">

			@include('success.ajax-alert-flotante')
			@if(count($persona)<=0 or count($encuesta)<0)

	            	<div class="col-xs-12 col-md-12" style="margin-top: 15px;">
		            	<div class='titulo-preguntas'>
		            		AUN NO REALIZA SU ENCUESTA  Ó NO EXISTE EL TRABAJADOR
		            	</div>
	            	</div>

			@else
				<div class='titulo-encuesta col-xs-offset-0 col-xs-12 col-md-offset-2 col-md-8  col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6 '>

			        	<h4>
			        		<b>{{$persona->NombreCompleto}}</b><br>
			        		<small>Resultado de la encuesta para el control del contagio del COVID-19</small> 
			        		
			        	</h4>
				</div>
				<div class='encuesta col-xs-offset-0 col-xs-12 col-md-offset-2 col-md-8  col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6 '
					style="margin-bottom: 20px;">


				  	<div class="panel panel-primary" style="margin-top:25px;">
				        @php 
				          $idPregunta 			= "";
				          $contador 			= 0;
				          $contadorItem 		= 1;
				          $contadorUnico 		= 0;
				          $contadorMultiple 	= 0;
				          $contadorText 		= 0;
				          $contrecomendacion 	= 0;
				          $titulopreguntauno 	= 0;
				          $titulopreguntados 	= 0;
				        @endphp 
				        @foreach($listapregunta as $index=>$item)
				          
				          	@php $contrecomendacion = $contrecomendacion + 1;  @endphp 
				          	@php $checked = '';  @endphp 
				          	@php $marco = $funcion->pregunta_marco_encuesta($item->IdPreguntaRespuesta,$encuesta->id);  @endphp 
		                   	@if($marco=='1')
		                   		@php $checked = 'checked';  @endphp 
		                  	@endif
				            @if($item->numero == 1 and $titulopreguntauno  == 0)
				            	<div class="col-xs-12 col-md-12">
					            	<div class='titulo-preguntas'>
					            		ANTECEDENTES (RESPONDA LO SIGUIENTE)
					            	</div>
				            	</div>
				            	@php $titulopreguntauno = 1;  @endphp 
							@endif

				            @if($item->numero == 7 and $titulopreguntados  == 0)

				            	<div class="col-xs-12 col-md-12" style="margin-top: 15px;">
					            	<div class='titulo-preguntas'>
					            		¿HOY UD. PRESENTA LOS SIGUIENTES SINTOMAS?
					            	</div>
				            	</div>
				            	@php $titulopreguntados = 1;  @endphp 
							@endif

				            @if($item->id!=$idPregunta)
				            	<div class="col-xs-12 col-md-12">
				              		<div class="preguntas pregunta{{$contadorItem}}">

					                	<div class="numero"><p>{{$contadorItem}}</p></div>
						                	<div class="pregunta">
						                  		<p><b>{{$item->descripcion}}</b></p>  
						                	</div>
									    	<div class="funkyradio @if($item->DescripcionTipo=='Multiple') check @endif">
							                @if($item->DescripcionTipo=='Multiple') 
							                  @php $contadorMultiple = $contadorMultiple + 1;  @endphp 
							                @else
							                  @if($item->DescripcionTipo=='Unica')
							                    @php $contadorUnico = $contadorUnico + 1;  @endphp
							                  @else
							                    @php $contadorText = $contadorText + 1;  @endphp
							                  @endif
							                @endif
							                @php 
							                  $idPregunta = $item->id;
							                  $contadorItem = $contadorItem + 1;
							                @endphp
				            @endif
				          
				            @if($item->DescripcionTipo=='Multiple') 
				                    <div class="funkyradio-success">
				                        <input type="checkbox" name="checkbox{{$contadorMultiple}}" id="checkbox{{$contador}}" value="{{$item->IdPreguntaRespuesta}}" />
				                        <label for="checkbox{{$contador}}">{{$item->DescripcionResp}}</label>
				                    </div>
				            @else
				              @if($item->DescripcionTipo=='Unica')

				                  	<div class="funkyradio-success">
				                        <input type="radio" 
				                        name="radio{{$contadorUnico}}" 
				                        id="radio{{$contador}}" 
				                        value="{{$item->IdPreguntaRespuesta}}" 
				                        disabled
				                        {{$checked}}
				                        />
				                        <label for="radio{{$contador}}">
				                        	{{$item->DescripcionResp}}
				                        </label>
				                    </div>

				              @else
				                	<textarea class="form-control textarea" id="txtespecificar" class='txtespecificar' rows="6">{{$encuesta->descripcion}}</textarea>
				              @endif
				            @endif

					        @if(!isset($listapregunta[$contador+1]->id))
					                  	</div>
					              	</div>
					            </div>
					        @else
					                @if($listapregunta[$contador+1]->id != $idPregunta)
					                    </div>
					              	</div>
					            </div>
					                @endif
					        @endif
					        @php $contador = $contador + 1  @endphp
				        @endforeach
				        <br><br>
				    </div>
				    </div> 
		
				    <br>
			@endif
 
		</div> <!--container-->
      </div>
    </div>

    <script src="{{ asset('public/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/parsley/parsley.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/general/general.js?v='.$version) }}" type="text/javascript"></script>

    <script type="text/javascript">
      $(document).ready(function(){
      	App.init();
        $('form').parsley();
      });
    </script>
    <script src="{{ asset('public/js/encuesta/encuesta.js?v='.$version) }}" type="text/javascript"></script>
  </body>
</html>