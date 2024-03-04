
<nav class="navbar navbar-default navbar-fixed-top be-top-header">
  <div class="container-fluid">
    <div class="navbar-header"></div>
    <div class="be-right-navbar">
      <ul class="nav navbar-nav navbar-right be-user-nav">

        <li class="dropdown">
          <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><img src="{{ asset('public/img/avatar1.png') }}" alt="Avatar"><span class="user-name">{{Session::get('usuario')->NombrePersona}} {{Session::get('usuario')->ApellidoPaterno}} {{Session::get('usuario')->ApellidoMaterno}}</span></a>
          <ul role="menu" class="dropdown-menu">
            <li>
              <div class="user-info">
                <div class="user-name">{{Session::get('usuario')->NombrePersona}} {{Session::get('usuario')->ApellidoPaterno}} {{Session::get('usuario')->ApellidoMaterno}}</div>
                <div class="user-position online">disponible</div>
              </div>
            </li>
            <li><a href="{{ url('/cambiar-clave') }}"><span class="icon mdi mdi-key"></span> Cambiar clave</a></li>
            <li><a href="{{ url('/cerrarsession') }}"><span class="icon mdi mdi-power"></span> Cerrar sesión</a></li>
          </ul>
        </li>
      </ul>


    </div><a href="#" data-toggle="collapse" data-target="#be-navbar-collapse" class="be-toggle-top-header-menu collapsed">Opciones</a>
    <div id="be-navbar-collapse" class="navbar-collapse collapse">
      
            <ul class="nav navbar-nav">
            <li class="active"><a href="{{ url('/bienvenido') }}"><i class="icon mdi mdi-home"></i><span>&nbsp;Inicio</span></a></li>
           
            @foreach(Session::get('listamenu') as $grupo)

        
                <li  class="dropdown active"  ><a href="#" @click="menu='{{$grupo->id}}'" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><i class="icon mdi {{$grupo->icono}}"></i><span>&nbsp;{{$grupo->nombre}}</span></a>
                  <ul role="menu" class="dropdown-menu">
                    @foreach($grupo->opcion as $opcion)
                      @if(in_array($opcion->id, Session::get('listaopciones')))
                        <li>
                          <a href="{{ url('/'.$opcion->pagina.'/'.Hashids::encode(substr($opcion->id, -8))) }}">{{$opcion->nombre}}</a>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                </li>
            @endforeach
            </ul>
    </div>
  </div>
</nav>