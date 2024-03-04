<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistemas multiplataforma">
    <meta name="author" content="Jorge Francelli Saldaña Reyes">
    <link rel="icon" href="{{ asset('public/img/icono/faviind.ico') }}">
    <title>INDUAMERICA - BINGO</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/material-design-icons/css/material-design-iconic-font.min.css') }} "/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}" type="text/css"/>


  </head>
  <body class="be-splash-screen">

    <div class="be-wrapper be-login">
      <div class="be-content">
        <div class="main-content container-fluid">
          <div class="splash-container">
            <div class="panel panel-default panel-border-color panel-border-color-primary">
              <div class="panel-heading">

              <img src="{{ asset('public/img/empresa/logologin.png')}}" alt="logo" width="100" height="100" class="logo-img">
              <span class="splash-description">Por favor ingrese su dni y descargue su bingo.</span>
              </div>
              <div class="panel-body">

                <form method="POST" action="{{ url('descargarbingo') }}">
                  {{ csrf_field() }}

                  <div class="form-group">
                    
                    <input  id="dni" 
                            name='dni' 
                            type="number" 
                            required = "" 
                            value="{{ old('dni') }}"  
                            placeholder="DNI" 
                            autocomplete="off" 
                            class="form-control" 
                            data-aw="1"/>

                    @include('error.erroresvalidate', [ 'id' => $errors->has('dni')  , 
                                                        'error' => $errors->first('dni', ':message') , 
                                                        'data' => '1'])

                    @include('error.erroresbd', [ 'id' => Session::get('errorbd')  , 
                                                  'error' => Session::get('errorbd') , 
                                                  'data' => '1'])

                  </div>
                    @if (Session::get('descargar_bingo'))
                      <a href="{{ asset('bingos/'.Session::get('descargar_bingo').'.jpg') }}">Ver bingo</a>
                    @endif
                  <div class="form-group login-submit">
                    <button data-dismiss="modal" type="submit"  class="btn btn-primary btn-xl">Buscar</button>
                  </div>

                </form>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('public/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/lib/parsley/parsley.js') }}" type="text/javascript"></script>


    <script type="text/javascript">
      $(document).ready(function(){
      	App.init();
        $('form').parsley();
      });
    </script>
  </body>
</html>