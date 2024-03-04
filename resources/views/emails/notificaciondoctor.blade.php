<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />

        <style>

            body{

            }
            .banner{
                margin: 0 auto;
                text-align: center;
                width: 700px;               
            }
            p{
                margin-bottom: 0px;
                margin-top: 0px;
                font-style: italic;
                text-align: left;
            }
            .titulo{
                margin-bottom: 3px;
                margin-top: 3px;
                font-weight: bold;
            }
            .titulo a{
                color: #000000;
                font-size: 1em;
            }
            .jefatura{
                margin-bottom: 6px;
                margin-top: 3px;               
            }
            .subtitulo{
                margin-top: 3px;
                font-weight: bold;
                font-size: 1em;
            }
            h1{
                text-decoration:underline;
                margin-bottom: 8px;
                font-size: 15px;
            }


            .panelcontainer{
                width: 50%;
                background: #fff;
                margin: 0 auto;


            }
            .panelhead{
                background: #eb6357;
                padding-top: 10px;
                padding-bottom: 10px;
                color: #fff;
                text-align: center;
                font-size: 1.2em;
            }
            .panelbody,.panelbodycodigo{
                padding-left: 15px;
                padding-right: 15px;
            }
            .panelbodycodigo h3 small{
                color: #08257C;
            }

            table, td, th {    
                border: 1px solid #ddd;
                text-align: left;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                padding: 15px;
                font-size: 14px;
            }


        </style>


    </head>


    <body>
        <section>
            <div class='banner'>
                <h1>NOTIFICACION DE ENCUESTA ({{$encuesta->codigo}})</h1>
                <h3>{{$encuesta->trabajador->NombreCompleto}} presenta sintomas de covid-19</h3>
                
                <p>Edad : {{$funcion->calculaedad($encuesta->trabajador->FechaNacimiento)}}</p>
                <p>Area : {{$encuesta->trabajador->Area}}</p>
                <p>Cargo : {{$encuesta->trabajador->Cargo}}</p>
                <p>Telefonos: {{$encuesta->trabajador->Telefono}}
                </p>
                <table  bgcolor="#f6f6f6" >
                    <tr>

                        <td width='700' colspan='2'>
                            <div class="panelhead">Preguntas que marco (SI)</div>
                            <div class='panelbody'>
                                    <table  class="table demo" >
                                        <tr>
                                            <th width='350'>
                                                Pregunta
                                            </th>
                                            <th width='350'>
                                                Respuesta
                                            </th>
                                       
                                        </tr>
                                        @foreach($preguntas as $index=>$pregunta)
                                        <tr>
                                                <td width='350'>{{$pregunta['descripcion']}}</td>
                                                <td width='350'>SI</td>
                                        </tr>
                                        @endforeach
                                    </table>
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td width='350'>
                            <p class='titulo'><a href="http://10.1.50.2:8080/isl//detalle-encuesta-trabajador/{{$encuesta_id}}">Ver encuesta (si esta dentro la empresa)</a></p>  
                        </td>
                        <td width='350'>
                            <p class='titulo'><a href="http://216.244.171.14:8080/isl//detalle-encuesta-trabajador/{{$encuesta_id}}">Ver encuesta (si esta fuera del trabajo)</a></p>  
                        </td>

                    </tr>
                </table>
            </div>            
        </section>
    </body>

</html>


