<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/********************** USUARIOS *************************/
Route::group(['middleware' => ['guestaw']], function () {

	Route::any('/', 'UserController@actionLogin');
	Route::any('/login', 'UserController@actionLogin');

});

Route::get('/cerrarsession', 'UserController@actionCerrarSesion');

Route::group(['middleware' => ['authaw']], function () {

	Route::get('/bienvenido', 'UserController@actionBienvenido');

	Route::any('/gestion-de-usuarios/{idopcion}', 'UserController@actionListarUsuarios');
	Route::any('/agregar-usuario/{idopcion}', 'UserController@actionAgregarUsuario');
	Route::any('/modificar-usuario/{idopcion}/{idusuario}', 'UserController@actionModificarUsuario');

	Route::any('/cambiar-clave', 'UserController@actionCambiarClave');

	Route::any('/gestion-de-roles/{idopcion}', 'UserController@actionListarRoles');
	Route::any('/agregar-rol/{idopcion}', 'UserController@actionAgregarRol');
	Route::any('/modificar-rol/{idopcion}/{idrol}', 'UserController@actionModificarRol');

	Route::any('/gestion-de-permisos/{idopcion}', 'UserController@actionListarPermisos');
	Route::any('/ajax-listado-de-opciones', 'UserController@actionAjaxListarOpciones');
	Route::any('/ajax-activar-permisos', 'UserController@actionAjaxActivarPermisos');
	
	Route::any('/gestion-de-solicitud-materiales/{idopcion}', 'SolicitudMaterialesController@actionListarSolicitudMateriales');
	Route::any('/ajax-listado-de-solicitudes-materiales', 'SolicitudMaterialesController@actionAjaxListarSolicitudMateriales');
	Route::any('/ajax-mantenimiento-solicitud-materiales', 'SolicitudMaterialesController@actionAjaxMantenimientoSolicitudMateriales');
	Route::any('/guardar-solicitud-materiales/{idopcion}', 'SolicitudMaterialesController@actionGuardarSolicitudMateriales');
	
	Route::any('/gestion-de-orden-compra-materiales/{idopcion}', 'OrdenCompraController@actionListarOrdenCompraMateriales');
	Route::any('/ajax-listado-de-orden-compra-materiales', 'OrdenCompraController@actionAjaxListarOrdenCompraMateriales');
	Route::any('/ajax-mantenimiento-orden-compra-materiales', 'OrdenCompraController@actionAjaxMantenimientoOrdenCompraMateriales');
	Route::any('/aprobar-orden-compra-materiales/{idopcion}', 'OrdenCompraController@actionAprobarOrdenCompraMateriales');
	
	Route::any('/gestion-de-orden-compra-servicios/{idopcion}', 'OrdenCompraController@actionListarOrdenCompraServicios');
	Route::any('/ajax-listado-de-orden-compra-servicio', 'OrdenCompraController@actionAjaxListarOrdenCompraServicios');
	Route::any('/ajax-mantenimiento-orden-compra-servicios', 'OrdenCompraController@actionAjaxMantenimientoOrdenCompraServicios');
	Route::any('/aprobar-orden-compra-servicios/{idopcion}', 'OrdenCompraController@actionAprobarOrdenCompraServicios');

	Route::any('/pb/{idopcion}', 'PowerBiController@actionListarPowerby');


	Route::any('/documento-proveedor-sunat/{idopcion}', 'CantabilidadController@actionListarDocumentoProveedorSunat');
	Route::any('/ajax-listado-de-facturas-entrefechas', 'CantabilidadController@actionAjaxListarFacturasEntreFechas');
	Route::any('/descargar-facturas/{idopcion}', 'CantabilidadController@actionDescargarFactura');


});

Route::any('/encuesta', 'EncuestaController@actionInicioEncuesta');
Route::any('/realizar-encuesta/{dni}', 'EncuestaController@actionRealizarEncuesta');
Route::any('/ajax-guardar-encuesta-trabajador', 'EncuestaController@actionGuardarEncuestaTrabajador');
Route::any('/termino-encuesta/{dni}', 'EncuestaController@actionTerminoEncuesta');
Route::any('/lista-encuesta', 'EncuestaController@actionListaEncuesta');
Route::any('/detalle-encuesta-trabajador/{dni}', 'EncuestaController@actionDetalleEncuestaTrabajador');

Route::any('/tamizaje-del-dia', 'EncuestaController@actionTamizajeDiario');
Route::any('/tamizaje-del-dia-rioja', 'EncuestaController@actionTamizajeDiarioRioja');
Route::any('/tamizaje-del-dia-bellavista', 'EncuestaController@actionTamizajeDiarioBellavista');
Route::any('/tamizaje-del-dia-isl', 'EncuestaController@actionTamizajeDiarioIsl');
Route::any('/descargarbingo', 'EncuestaController@actionBingo');
Route::any('/ver-revista-virtual', 'EncuestaController@actionVerRevistavirtual');


