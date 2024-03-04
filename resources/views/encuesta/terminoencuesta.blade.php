<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistemas multiplataforma">
    <meta name="author" content="Jorge Francelli Saldaña Reyes">
    <link rel="icon" href="{{ asset('public/img/icono/faviind.ico') }}">


    <title>ENCUESTA</title>
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

            @if(count($persona)>0)
    				<div class='termino-encuesta col-xs-offset-0 col-xs-12 col-md-offset-2 col-md-8  col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6'>
    			        	<h4>
    			        		<b>{{$persona->NombreCompleto}}</b><br>
    			        		<p>MUCHAS GRACIAS POR LLENAR EL TAMIZAJE, segun tus resultados nos estaremos comunicando contigo.</p> 
    			        	</h4>
    				</div>
            @else
            <div class='termino-encuesta col-xs-offset-0 col-xs-12 col-md-offset-2 col-md-8  col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6'>
                <h4>
                  <b>TRABAJADOR NO EXISTE</b><br>
                </h4>
            </div>
            @endif

            
    		</div> <!--container-->

        <div style="text-align: center;margin-top: 20px;"><a href="{{ url('/encuesta') }}" class="btn btn-rounded btn-space btn-success">REALIZAR OTRA ENCUESTA</a></div>

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