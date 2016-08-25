<?php
session_start();
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::any('/', 'Actividadcontroller@show');

Route::any('uno', function () {                
    return 'welcome';
});

 


Route::get('/personas', '\Idrd\Usuarios\Controllers\PersonaController@index');
Route::get('/personas/service/obtener/{id}', '\Idrd\Usuarios\Controllers\PersonaController@obtener');
Route::get('/personas/service/buscar/{key}', '\Idrd\Usuarios\Controllers\PersonaController@buscar');
Route::get('/personas/service/ciudad/{id_pais}', '\Idrd\Usuarios\Controllers\LocalizacionController@buscarCiudades');
Route::post('/personas/service/procesar/', '\Idrd\Usuarios\Controllers\PersonaController@procesar');


Route::get('/asignarLocalidad', 'ConfiguracionActividadController@asignarLocalidad');
Route::get('/CrearActividad', 'Actividadcontroller@index');
Route::get('/Modificar', 'Actividadcontroller@index');
Route::get('/MisProgramaciones', 'Actividadcontroller@MiActividad');
Route::get('/MisActividades', 'mis_actividades_promotores@Mis_Actividad');
Route::get('/ActividadesAprobar', 'aprobacion_actividades@Mis_Actividad');


Route::get('/actividad/service/obtener/{id_actividad}', 'Actividadcontroller@obtenerActividad');
Route::post('/actividad/service/crearActividad/', 'ConfiguracionActividadController@procesarValidacion');
Route::get('/actividad/service/tematica/{id_eje}', 'ConfiguracionActividadController@buscarTematicas');
Route::get('/actividad/service/actividad/{id_tematica}', 'ConfiguracionActividadController@buscarActividades');
Route::get('/actividad/service/persona_tipo/{id_tipo}', '\Idrd\Usuarios\Controllers\PersonaController@buscarPersonaTipo');
Route::get('/actividad/MisProgramaciones', 'Actividadcontroller@MiActividad2');
Route::get('/actividad/service/Eje/{id_eje}', 'ConfiguracionActividadController@buscarEje');
Route::get('/actividad/service/Tematica/{id_tematica}', 'ConfiguracionActividadController@buscarTematica');
Route::get('/actividad/service/Actividad/{id_actividad}', 'ConfiguracionActividadController@buscarActividad');
Route::post('/PersonaLocalidad/service/validacionPersonaLocalidad/', 'ConfiguracionActividadController@procesarValidacionPersonaLocalidad');
Route::post('/PersonaLocalidad/service/eliminaPersonaLocalidad/', 'ConfiguracionActividadController@eliminaPersonaLocalidad');
Route::post('/PersonaLocalidad/service/verPersonaLocalidad/', 'ConfiguracionActividadController@verPersonaLocalidad');
Route::post('/actividad/service/ModificarActividad/', 'ConfiguracionActividadController@procesarModificacionValidacion');


Route::post('/gestores/service/misActividadesGestor/', 'mis_actividades_promotores@procesarValidacionGestor');
Route::get('/gestores/service/obtener/{id_actividad}', 'mis_actividades_promotores@obtenerActividad');
Route::post('/gestores/service/datos_actividades/', 'mis_actividades_promotores@procesarValidacionDatosEjecucion');
Route::post('/gestores/service/datos_novedades/', 'mis_actividades_promotores@procesarValidacionDatosNovedades');
Route::post('/gestores/service/registro_ejecucion/', 'mis_actividades_promotores@procesarValidacionRegistroEjecucion');


Route::post('/aprobar/service/misActividadesGestor/', 'aprobacion_actividades@procesarValidacionGestor');
Route::get('/aprobar/service/obtener/{id_actividad}', 'aprobacion_actividades@obtenerEjecucion');
Route::post('/aprobar/service/ModificarActividad/', 'aprobacion_actividades@procesarModificacionValidacion');
Route::get('/aprobar/service/activar/{id_actividad}', 'aprobacion_actividades@activarProgramacion');
Route::get('/aprobar/service/aprobarEjecucion/{id_actividad}', 'aprobacion_actividades@aprobarEjecucion');
Route::get('/aprobar/service/cancelar/{id_actividad}', 'aprobacion_actividades@cancelarProgramacion');

Route::group(['middleware' => ['web']], function () {

    
});
