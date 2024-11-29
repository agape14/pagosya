<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProgramacionPagoController;
use App\Http\Controllers\TorreController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\TipoConceptoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\InteresBancarioController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\VideoController;

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

/* Route::get('/', function () {
    return view('welcome');
}); */



//Route::get('/', 'App\Http\Controllers\ChrevadminController@dashboard_1');
Route::get('/', function () {
    return redirect()->route('showlogin');
});
Route::get('/login', function () {
    return redirect()->route('showlogin');
});
/*Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
*/

// Definir la ruta para el procesamiento del formulario de inicio de sesión
/*Route::get('/login', [LoginControlle::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
*/
Route::get('/showlogin', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('showlogin');
Route::post('/iniciarsesion', 'App\Http\Controllers\Auth\LoginController@iniciarsesion')->name('iniciarsesion');
Route::get('/cerrarsesion', 'App\Http\Controllers\Auth\LoginController@cerrarsesion')->name('cerrarsesion');

// Agrupar las rutas protegidas con el middleware 'auth'
Route::middleware(['auth'])->group(function () {
    // Rutas protegidas
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/index', 'App\Http\Controllers\ChrevadminController@dashboard_1');
    Route::get('/coin-details', 'App\Http\Controllers\ChrevadminController@coin_details');
    Route::get('/market-capital', 'App\Http\Controllers\ChrevadminController@market_capital');
    Route::get('/my-wallet', 'App\Http\Controllers\ChrevadminController@my_wallet');
    Route::get('/portfolio', 'App\Http\Controllers\ChrevadminController@portfolio');
    Route::get('/transactions', 'App\Http\Controllers\ChrevadminController@transactions');
    Route::get('/app-calender', 'App\Http\Controllers\ChrevadminController@app_calender');
    Route::get('/app-profile', 'App\Http\Controllers\ChrevadminController@app_profile');
    Route::get('/chart-chartist', 'App\Http\Controllers\ChrevadminController@chart_chartist');
    Route::get('/chart-chartjs', 'App\Http\Controllers\ChrevadminController@chart_chartjs');
    Route::get('/chart-flot', 'App\Http\Controllers\ChrevadminController@chart_flot');
    Route::get('/chart-morris', 'App\Http\Controllers\ChrevadminController@chart_morris');
    Route::get('/chart-peity', 'App\Http\Controllers\ChrevadminController@chart_peity');
    Route::get('/chart-sparkline', 'App\Http\Controllers\ChrevadminController@chart_sparkline');
    Route::get('/ecom-checkout', 'App\Http\Controllers\ChrevadminController@ecom_checkout');
    Route::get('/ecom-customers', 'App\Http\Controllers\ChrevadminController@ecom_customers');
    Route::get('/ecom-invoice', 'App\Http\Controllers\ChrevadminController@ecom_invoice');
    Route::get('/ecom-product-detail', 'App\Http\Controllers\ChrevadminController@ecom_product_detail');
    Route::get('/ecom-product-grid', 'App\Http\Controllers\ChrevadminController@ecom_product_grid');
    Route::get('/ecom-product-list', 'App\Http\Controllers\ChrevadminController@ecom_product_list');
    Route::get('/ecom-product-order', 'App\Http\Controllers\ChrevadminController@ecom_product_order');
    Route::get('/email-compose', 'App\Http\Controllers\ChrevadminController@email_compose');
    Route::get('/email-inbox', 'App\Http\Controllers\ChrevadminController@email_inbox');
    Route::get('/email-read', 'App\Http\Controllers\ChrevadminController@email_read');
    Route::get('/form-editor-summernote', 'App\Http\Controllers\ChrevadminController@form_editor_summernote');
    Route::get('/form-element', 'App\Http\Controllers\ChrevadminController@form_element');
    Route::get('/form-pickers', 'App\Http\Controllers\ChrevadminController@form_pickers');
    Route::get('/form-validation-jquery', 'App\Http\Controllers\ChrevadminController@form_validation_jquery');
    Route::get('/form-wizard', 'App\Http\Controllers\ChrevadminController@form_wizard');
    Route::get('/map-jqvmap', 'App\Http\Controllers\ChrevadminController@map_jqvmap');
    Route::get('/page-error-400', 'App\Http\Controllers\ChrevadminController@page_error_400');
    Route::get('/page-error-403', 'App\Http\Controllers\ChrevadminController@page_error_403');
    Route::get('/page-error-404', 'App\Http\Controllers\ChrevadminController@page_error_404');
    Route::get('/page-error-500', 'App\Http\Controllers\ChrevadminController@page_error_500');
    Route::get('/page-error-503', 'App\Http\Controllers\ChrevadminController@page_error_503');
    Route::get('/page-forgot-password', 'App\Http\Controllers\ChrevadminController@page_forgot_password');
    Route::get('/page-lock-screen', 'App\Http\Controllers\ChrevadminController@page_lock_screen');

    Route::get('/page-register', 'App\Http\Controllers\ChrevadminController@page_register');
    Route::get('/table-bootstrap-basic', 'App\Http\Controllers\ChrevadminController@table_bootstrap_basic');
    Route::get('/table-datatable-basic', 'App\Http\Controllers\ChrevadminController@table_datatable_basic');
    Route::get('/uc-nestable', 'App\Http\Controllers\ChrevadminController@uc_nestable');
    Route::get('/uc-noui-slider', 'App\Http\Controllers\ChrevadminController@uc_noui_slider');
    Route::get('/uc-select2', 'App\Http\Controllers\ChrevadminController@uc_select2');
    Route::get('/uc-sweetalert', 'App\Http\Controllers\ChrevadminController@uc_sweetalert');
    Route::get('/uc-toastr', 'App\Http\Controllers\ChrevadminController@uc_toastr');
    Route::get('/ui-accordion', 'App\Http\Controllers\ChrevadminController@ui_accordion');
    Route::get('/ui-alert', 'App\Http\Controllers\ChrevadminController@ui_alert');
    Route::get('/ui-badge', 'App\Http\Controllers\ChrevadminController@ui_badge');
    Route::get('/ui-button', 'App\Http\Controllers\ChrevadminController@ui_button');
    Route::get('/ui-button-group', 'App\Http\Controllers\ChrevadminController@ui_button_group');
    Route::get('/ui-card', 'App\Http\Controllers\ChrevadminController@ui_card');
    Route::get('/ui-carousel', 'App\Http\Controllers\ChrevadminController@ui_carousel');
    Route::get('/ui-dropdown', 'App\Http\Controllers\ChrevadminController@ui_dropdown');
    Route::get('/ui-grid', 'App\Http\Controllers\ChrevadminController@ui_grid');
    Route::get('/ui-list-group', 'App\Http\Controllers\ChrevadminController@ui_list_group');
    Route::get('/ui-media-object', 'App\Http\Controllers\ChrevadminController@ui_media_object');
    Route::get('/ui-modal', 'App\Http\Controllers\ChrevadminController@ui_modal');
    Route::get('/ui-pagination', 'App\Http\Controllers\ChrevadminController@ui_pagination');
    Route::get('/ui-popover', 'App\Http\Controllers\ChrevadminController@ui_popover');
    Route::get('/ui-progressbar', 'App\Http\Controllers\ChrevadminController@ui_progressbar');
    Route::get('/ui-tab', 'App\Http\Controllers\ChrevadminController@ui_tab');
    Route::get('/ui-typography', 'App\Http\Controllers\ChrevadminController@ui_typography');
    Route::get('/widget-basic', 'App\Http\Controllers\ChrevadminController@widget_basic');


    Route::get('/noticias', [NoticiasController::class, 'noticias_index'])->name('noticias');
    Route::post('/acumuladores/actualizartotales', [NoticiasController::class, 'actualizarTotales'])->name('acumuladores.actualizarTotales');

    Route::get('/intbancario', [InteresBancarioController::class, 'intbancario_index'])->name('intbancario');
    Route::get('/intbancario/get', [InteresBancarioController::class, 'getIntbancarios'])->name('intbancario_get');
    Route::post('/addintbancario', [InteresBancarioController::class, 'store'])->name('addintbancario');
    Route::get('/intbancario/get/{id}', [InteresBancarioController::class, 'getIntbancario'])->name('intbancario_get_id');
    Route::put('/editintbancario/{id}', [InteresBancarioController::class, 'update'])->name('intbancario_edit');
    Route::delete('/intbancario/delete/{id}', [InteresBancarioController::class, 'destroy'])->name('intbancario_delete_id');

    Route::get('/torres', [TorreController::class, 'torres_index'])->name('torres');
    Route::get('/torres/get', [TorreController::class, 'getTorres'])->name('torres_get');
    Route::post('/addtorre', [TorreController::class, 'store'])->name('addtorre');
    Route::get('/torres/get/{id}', [TorreController::class, 'getTorre'])->name('torres_get_id');
    Route::put('/edittorre/{id}', [TorreController::class, 'update'])->name('torres_edit');
    Route::delete('/torres/delete/{id}', [TorreController::class, 'destroy'])->name('torres_delete_id');

    Route::get('/tipoconceptos', [TipoConceptoController::class, 'tipoconceptos_index'])->name('tipoconceptos');
    Route::get('/tipoconceptos/get', [TipoConceptoController::class, 'getTipoConceptos'])->name('tipoconceptos_get');

    Route::get('/conceptos', [ConceptoController::class, 'conceptos_index'])->name('conceptos');
    Route::get('/conceptos/get', [ConceptoController::class, 'getConceptos'])->name('conceptos_get');
    Route::post('/addconcepto', [ConceptoController::class, 'store'])->name('addconcepto');
    Route::get('/conceptos/get/{id}', [ConceptoController::class, 'getConcepto'])->name('conceptos_get_id');
    Route::put('/editconcepto/{id}', [ConceptoController::class, 'update'])->name('conceptos_edit');
    Route::delete('/conceptos/delete/{id}', [ConceptoController::class, 'destroy'])->name('conceptos_delete_id');

    Route::get('/propietarios', [PropietarioController::class, 'propietarios_index'])->name('propietarios');
    Route::get('/propietarios/get', [PropietarioController::class, 'getPropietarios'])->name('propietarios_get');
    Route::post('/addpropietario', [PropietarioController::class, 'store'])->name('addPropietario');
    Route::get('/propietarios/get/{id}', [PropietarioController::class, 'getPropietario'])->name('propietarios_get_id');
    Route::put('/editpropietario/{id}', [PropietarioController::class, 'update'])->name('propietarios_edit');
    Route::get('/notificarusuarios', [PropietarioController::class, 'enviarNotificaciones'])->name('notificar.propietarios');

    //Route::delete('/propietarios/delete/{id}', [PropietarioController::class, 'destroy'])->name('propietarios_delete_id');

    Route::get('propietarios/{id}/sub', [PropietarioController::class, 'getSubPropietarios'])->name('propietarios.sub');
    Route::get('propietarios/sub/{id}', [PropietarioController::class, 'getEditSubPropietarios'])->name('propietarios.subedit');
    Route::get('/subpropietarios/get/{id}', [PropietarioController::class, 'getTblSubPropietarios'])->name('subpropietarios_get');
    Route::post('sub_propietarios', [PropietarioController::class, 'storeSubPropietario'])->name('sub_propietarios.store');
    Route::get('tipos_sub_propietarios', [PropietarioController::class, 'getTiposSubPropietarios'])->name('tipos_sub_propietarios');
    Route::delete('/propietarios/delete/{id}', [PropietarioController::class, 'destroy'])->name('propietarios_delete_id');

    Route::get('/programacion', [ProgramacionPagoController::class, 'programacion_index'])->name('programacion');
    Route::get('/programacion/data', [ProgramacionPagoController::class, 'getData'])->name('programacion.data');
    Route::post('programacion', [ProgramacionPagoController::class, 'storeProgramacion'])->name('programacion.store');
    Route::get('/programacion/get/{id}', [ProgramacionPagoController::class, 'show'])->name('programacion_get_id');
    Route::delete('/programacion/delete/{id}', [ProgramacionPagoController::class, 'destroy'])->name('programacion_delete_id');


    Route::get('/pagos', [PagoController::class, 'pagos_index'])->name('pagos');
    Route::get('/pagos/data', [PagoController::class, 'gettblPagos'])->name('pagos.data');
    Route::get('/pagos/getpogramacion/{id}', [PagoController::class, 'showprogramacion'])->name('pagos_getpogramacion_id');
    Route::get('/pagos/getpagopartes/{id}', [PagoController::class, 'pagoPartes'])->name('pagopartes_get_id');
    Route::get('/pagos/get/{id}', [PagoController::class, 'show'])->name('pagos_get_id');
    Route::post('/guardar-evidencia', [PagoController::class, 'guardarEvidencia'])->name('guardar.evidencia');
    Route::post('/confirmar-evidencia', [PagoController::class, 'confirmarEvidencia'])->name('confirmar.evidencia');
    Route::get('/pagos/{id}/pdf', [PagoController::class, 'generatePDF'])->name('pagos.pdf');
    Route::post('/guardar-evidenciamultiple', [PagoController::class, 'guardarEvidenciaMultiple'])->name('guardar.evidenciamultiple');

    Route::get('/gastos', [GastoController::class, 'gastos_index'])->name('gastos');
    Route::get('/gastos/data', [GastoController::class, 'getTblGastos'])->name('gastos.data');
    Route::post('/gastos-evidencia', [GastoController::class, 'store'])->name('gastos.evidencia');
    Route::get('/gastos/get/{id}', [GastoController::class, 'show'])->name('gastos_get_id');
    Route::delete('/gastos/delete/{id}', [GastoController::class, 'destroy'])->name('gastos_delete_id');
    Route::get('/gastosverpdf/{id}', [GastoController::class, 'showverpdf'])->name('gastosverpdf.show');

    Route::get('/ingresos', [IngresoController::class, 'ingresos_index'])->name('ingresos');
    Route::get('/ingresos/data', [IngresoController::class, 'getTblIngresos'])->name('ingresos.data');
    Route::post('/ingresos-evidencia', [IngresoController::class, 'store'])->name('ingresos.evidencia');
    Route::get('/ingresos/get/{id}', [IngresoController::class, 'show'])->name('ingresos_get_id');
    Route::delete('/ingresos/delete/{id}', [IngresoController::class, 'destroy'])->name('ingresos_delete_id');
    Route::get('/ingresosverpdf/{id}', [IngresoController::class, 'showverpdf'])->name('ingresosverpdf.show');


    Route::get('/panel', [PanelController::class, 'panel_index'])->name('panel');
    Route::get('/gesestadospanel/{concepto_id}', [PanelController::class, 'obtenerEstado'])->name('gesestadospanel');
    Route::get('/getdatosxconcepto', [PanelController::class, 'obtenerDatosPorConcepto'])->name('obtenerDatosPorConcepto');
    Route::get('/getdatosporcentconcepto', [PanelController::class, 'obtenerDatosPorcentajeConcepto'])->name('obtenerDatosPorcentajeConcepto');
    Route::get('/resumen-gastos-ingresos', [PanelController::class, 'obtenerResumenGastosIngresos'])->name('obtenerResumenGastosIngresos');


    Route::get('/usuarios', [PanelController::class, 'panel_index'])->name('panel');
    Route::get('/cuenta', [ConfiguracionController::class, 'perfil_index'])->name('cuenta');
    Route::get('/permisos', [PanelController::class, 'panel_index'])->name('panel');

    Route::get('/usuarios', [ConfiguracionController::class, 'usuarios_index'])->name('usuarios');
    Route::get('/usuarios/data', [ConfiguracionController::class, 'getUsuarios'])->name('usuarios.data');
    Route::get('/usuarios/get/{id}', [ConfiguracionController::class, 'usuarioshow'])->name('usuarios_get_id');
    Route::post('/addusuario', [ConfiguracionController::class, 'usuariostore'])->name('addusuario');
    Route::put('/editusuario/{id}', [ConfiguracionController::class, 'usuariostore'])->name('usuario_edit');
    Route::delete('/usuarios/delete/{id}', [ConfiguracionController::class, 'destroyuser'])->name('usuarios_delete_id');
    Route::delete('/usuarios/active/{id}', [ConfiguracionController::class, 'activeuser'])->name('usuarios_active_id');
    Route::post('/addusuariomultiple/{cantidadPropietario}', [ConfiguracionController::class, 'crearUsuarioMultiplePropietarios'])->name('addusuariomultiple');

    Route::get('/perfiles', [ConfiguracionController::class, 'perfiles_index'])->name('perfiles');
    Route::get('/permisos', [ConfiguracionController::class, 'permisos_index'])->name('permisos');
    Route::get('/getpermisos/{usuario}', [ConfiguracionController::class, 'obtenerPermisosUsuario'])->name('getpermisosxuser');
    Route::post('/addpermisos', [ConfiguracionController::class, 'agregarPermiso'])->name('addpermisos');
    Route::put('/updusuario/{id}', [ConfiguracionController::class, 'update'])->name('updusuario');
    Route::post('/habilitapopup', [ConfiguracionController::class, 'habilitaPopup'])->name('habilita_popup');
    Route::post('/habilitanotifuser', [ConfiguracionController::class, 'habilitaNotifUser'])->name('habilitanotifuser');
    Route::get('/verificarparametro', [ConfiguracionController::class, 'verificarParametro'])->name('verificar_parametro');
    //Route::resource('gastos', PagoController::class); activeuser

    //Notificacion por whatsapp
    Route::get('/pagodescargar-recibo/{id}', [PagoController::class, 'descargarRecibo'])->name('descargar.recibo');
    Route::get('/enviar-confirmacion-pago/{idpago}', [PagoController::class, 'enviarConfirmacionPago'])->name('notificar.recibo');

/*  programacion.storedestroyuser
    Route::get('/tipoconceptos/get/{id}', [TorreController::class, 'getTipoConcepto'])->name('tipoconceptos_get_id');
    Route::delete('/tipoconceptos/delete/{id}', [TorreController::class, 'destroy'])->name('tipoconceptos_delete_id');
    Route::post('/addtipoconcepto', [TorreController::class, 'store'])->name('addtipoconcepto');
    Route::put('/edittipoconcepto/{id}', [TorreController::class, 'update'])->name('tipoconceptos_edit');

    Route::resource('propietarios', PropietarioController::class);
    Route::resource('tipos_concepto', TipoConceptoController::class);*/

    Route::post('/corregir-pagos', [PagoController::class, 'corregirPagos'])->name('corregir.pagos');
});
Route::get('/videos', [VideoController::class, 'index_video'])->name('videos.index');
