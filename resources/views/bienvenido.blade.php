@extends('template')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/jqvmap/jqvmap.min.css') }} "/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" />
@stop

@section('section')
  <div class="be-content">
    <div class="main-content container-fluid">
        <div class="row">

            <div class="col-xs-12 col-md-6 col-lg-4">


                <div class="widget widget-tile">
                  <div id="spark1" class="chart sparkline"></div>
                  <div class="data-info">
                    <div class="desc">Solicitud Materiales</div>
                    <div class="value">
                        <span class="indicator indicator-equal mdi mdi-chevron-right"></span>
                        <span data-toggle="counter" data-end="{{count($listasolicitudes)}}" class="number">{{count($listasolicitudes)}}</span>
                    </div>
                  </div>
                  <div class="data-info">
                    <div class="desc iropcion"> 
                        <a href="{{ url('/gestion-de-solicitud-materiales/mO') }}" class='cargando_href'>
                            <span class="mdi mdi-arrow-right"></span>
                        </a>
                        
                    </div>
                  </div>
                </div>

            </div>

            <div class="col-xs-12 col-md-6 col-lg-4">

                <div class="widget widget-tile">
                  <div id="spark2" class="chart sparkline"></div>
                  <div class="data-info">
                    <div class="desc">O.C. Materiales</div>
                    <div class="value">
                        <span class="indicator indicator-equal mdi mdi-chevron-right"></span>
                        <span data-toggle="counter" data-end="{{$cantidad_m}}" class="number">{{$cantidad_m}}</span>
                    </div>
                  </div>
                  <div class="data-info">
                    <div class="desc iropcion"> 
                        <a href="{{ url('/gestion-de-orden-compra-materiales/nR') }}" class='cargando_href'>
                            <span class="mdi mdi-arrow-right"></span>
                        </a>
                        
                    </div>
                  </div>
                </div>


            </div>

            <div class="col-xs-12 col-md-6 col-lg-4">

                <div class="widget widget-tile">
                  <div id="spark3" class="chart sparkline"></div>
                  <div class="data-info">
                    <div class="desc">O.C. Servicios</div>
                    <div class="value">
                        <span class="indicator indicator-equal mdi mdi-chevron-right"></span>
                        <span data-toggle="counter" data-end="{{$cantidad_s}}" class="number">{{$cantidad_s}}</span>
                    </div>
                  </div>
                  <div class="data-info">
                    <div class="desc iropcion"> 
                        <a href="{{ url('/gestion-de-orden-compra-servicios/oj') }}" class='cargando_href'>
                            <span class="mdi mdi-arrow-right"></span>
                        </a>
                        
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@stop 

