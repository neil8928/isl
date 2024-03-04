<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	{!! Html::style('public/css/excel/excel.css') !!}


    <!-- titulo -->
    <table>


        <tr>
        	<th class= 'tabladp'>EMPRESA</th>
            <th class= 'tabladp'>NOMBRES Y APELLIDOS</th>
            <th class= 'tabladp'>DNI</th>
            <th class= 'tabladp'>FECHA NACIMIENTO</th>
            <th class= 'tabladp'>AREA</th>
            <th class= 'tabladp'>CARGO</th>
            <th class= 'tabladp'>TELEFONO</th>
            <th class= 'tabladp'>CANTIDAD ENCUESTA</th>
            <th class= 'tabladp'>NOTICACION ENFERMERA</th>
            <th class= 'tabladp'>NOTICACION DOCTOR</th>
        </tr>
        @foreach($lista_trabajadores as $index => $item) 
                <tr>
                    <td width="20"> {{$item->Empresa}}</td>
                    <td width="20"> {{$item->NombreCompleto}}</td>
                    <td width="20"> {{$item->Dni}}</td>
                    <td width="20"> {{$item->FechaNacimiento}}</td>
                    <td width="20"> {{$item->Area}}</td>
                    <td width="20"> {{$item->Cargo}}</td>
                    <td width="20"> {{$item->Telefono}}</td>
                    <td width="20"> {{$item->cantidad_encuesta}}</td>
                    <td width="20"> {{$item->notificacion_enfermera}}</td>
                    <td width="20"> {{$item->notificacion_doctor}}</td>                                            
                </tr>       
        @endforeach
    </table>



</html>
