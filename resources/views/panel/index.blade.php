{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    @if (Auth::user()->id_perfil == 3 )
        <!-- PROPIETARIO DASHBOARD -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow-soft mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">Bienvenido, {{ Auth::user()->name }}</h4>
                            <p class="text-muted mb-0">Esta es tu información de pagos y deudas.</p>
                        </div>
                        <div>
                            <span class="badge badge-xl light badge-primary">{{ now()->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Estado de Cuenta</h4>
                    </div>
                    <div class="card-body">
                        @if ($contdeuda === 0)
                            <div class="alert alert-success solid alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <strong>¡Excelente!</strong> No tienes deudas pendientes.
                            </div>
                        @else
                            <div class="alert {{ $contdeuda >= 3 ? 'alert-danger' : 'alert-warning' }} solid alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <strong>Atención!</strong> Tienes {{ $contdeuda }} pago(s) pendiente(s).
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover" id="tblEstadoCuenta">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Concepto</th>
                                        <th>Periodo</th>
                                        <th class="text-right">Monto</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; $totalGeneral = 0; @endphp
                                    @foreach ($detdeudas_con_observacion as $detdeuda)
                                        <tr>
                                            <td><strong>{{ $contador }}</strong></td>
                                            <td>
                                                <span class="d-block font-w600">{{ $detdeuda->descripcion_concepto }}</span>
                                                <small class="text-muted">{{ $detdeuda->observacion ?? '' }}</small>
                                            </td>
                                            <td>{{ $detdeuda->nombremes ?? '--' }} {{ $detdeuda->anio }}</td>
                                            <td class="text-right text-primary font-w600">S/ {{ number_format($detdeuda->total, 2) }}</td>
                                            <td>
                                                @if($detdeuda->idestado == 1 || $detdeuda->idestado == 2 || $detdeuda->idestado == 4 || $detdeuda->idestado == 5)
                                                    <span class="badge badge-rounded badge-danger">Pendiente</span>
                                                @elseif($detdeuda->idestado == 3)
                                                    <span class="badge badge-rounded badge-success">Pagado</span>
                                                @else
                                                    <span class="badge badge-rounded badge-warning">{{ $detdeuda->estado }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($detdeuda->idestado == 1 || $detdeuda->idestado == 4 || $detdeuda->idestado == 5) <!-- Pendiente/Vencido -->
                                                    <a href="javascript:void(0)"
                                                        data-id="{{ $detdeuda->id }}"
                                                        data-idestado="{{ $detdeuda->idestado }}"
                                                        data-departamento="{{ $detdeuda->departamento }}"
                                                        data-concepto="{{ $detdeuda->descripcion_concepto }}"
                                                        data-total="{{ $detdeuda->total }}"
                                                        class="btn btn-primary btn-sm px-4 addPago shadow">
                                                        <i class="fa fa-upload mr-2"></i> Pagar
                                                    </a>
                                                @endif
                                                @if($detdeuda->idestado == 3) <!-- Pagado -->
                                                    <a href="javascript:void(0)" data-id="{{ $detdeuda->idpago }}"
                                                       class="btn btn-outline-success btn-sm verPdfPago">
                                                       <i class="fa fa-download mr-1"></i> Recibo
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $contador++; $totalGeneral += $detdeuda->total; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-right font-w600">Total a Pagar:</td>
                                        <td class="text-right"><strong class="text-primary fs-18">S/ {{ number_format($totalGeneral, 2) }}</strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif (Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
        <!-- ADMIN DASHBOARD -->

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="widget-stat card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="la la-users"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1 text-white">Ingresos (Pagos)</p>
                                <h3 class="text-white" id="cardIngresos">S/ {{ number_format($total_pagos_prop, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="widget-stat card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="la la-dollar"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1 text-white">Otros Ingresos</p>
                                <h3 class="text-white" id="cardOtros">S/ {{ number_format($total_ingresos_extra, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="widget-stat card bg-danger text-white mb-4">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="la la-shopping-cart"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1 text-white">Egresos Totales</p>
                                <h3 class="text-white" id="cardEgresos">S/ {{ number_format($total_egresos, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="widget-stat card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="la la-balance-scale"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1 text-white">Saldo General</p>
                                <h3 class="text-white" id="cardSaldo">S/ {{ number_format($saldo_general, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <div class="form-group mb-0 d-flex align-items-center">
                            <label class="mr-3 mb-0 font-w600">Filtrar por Concepto:</label>
                            <select class="form-control form-control-lg flex-grow-1" id="cbxConcepto" name="cbxConcepto">
                                @foreach ($conceptos as $concepto)
                                    <option value="{{ $concepto->id }}">
                                        {{ $concepto->descripcion_concepto . " - " . ($concepto->nombreMes ? $concepto->nombreMes->nombremes : '') . " " . ($concepto->anio ? $concepto->anio : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón para ver Detalle por Pisos -->
        <div class="row mb-3">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary btn-lg" id="btnVerDetallePisos" data-toggle="modal" data-target="#modalDetallePisos">
                    <i class="fa fa-building mr-2"></i> Ver Detalle por Pisos
                </button>
            </div>
        </div>

        <!-- Gráficos del Dashboard -->
        <!-- Estado de Pagos por Piso - Ocupa 12 columnas (todo el ancho) -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0 border-0">
                        <h4 class="card-title">Estado de Pagos por Piso</h4>
                        <p class="mb-0 text-muted fs-12">Distribución de pagados y pendientes</p>
                    </div>
                    <div class="card-body">
                        <div id="chartByFloor"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Porcentaje de Cobro y Distribución Financiera - 6 columnas cada uno -->
        <div class="row">
            <!-- Porcentaje de Cobro -->
            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header pb-0 border-0">
                        <h4 class="card-title">Porcentaje de Cobro</h4>
                        <p class="mb-0 text-muted fs-12">Concepto seleccionado</p>
                    </div>
                    <div class="card-body text-center">
                        <div id="chartDataCircle"></div>

                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="p-2 bg-primary rounded-circle mr-2"></span>
                                    <div>
                                        <p class="mb-0 fs-12">Pagados</p>
                                        <h4 class="font-w600" id="txtPagados">0%</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="p-2 bg-danger rounded-circle mr-2"></span>
                                    <div>
                                        <p class="mb-0 fs-12">Pendiente</p>
                                        <h4 class="font-w600" id="txtDebe">0%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Distribución Financiera -->
            <div class="col-xl-6 col-lg-6">
                <div class="card">
                    <div class="card-header pb-0 border-0">
                        <h4 class="card-title">Distribución Financiera</h4>
                        <p class="mb-0 text-muted fs-12">Ingresos vs Egresos</p>
                    </div>
                    <div class="card-body">
                        <div id="chartFinancial"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modals -->
<!-- PDF Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualizar Recibo</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfIframe" src="" width="100%" height="600px" style="border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Detalle por Pisos Modal -->
<div class="modal fade" id="modalDetallePisos" tabindex="-1" role="dialog" aria-labelledby="modalDetallePisosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalDetallePisosLabel">
                    <i class="fa fa-building mr-2"></i> Detalle por Pisos
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div id="pisoContainer" class="row">
                    <!-- Items will be injected here via JS -->
                    <div class="col-12 text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="AddPagoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="pagoFormProp" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="pagoId">
                <input type="hidden" name="estadoId" id="estadoId">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Registrar Pago</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-black font-w500">Departamento</label>
                        <input type="text" class="form-control bg-light" id="txtAddPayDepartamento" readonly>
                    </div>
                    <div class="form-group">
                        <label class="text-black font-w500">Concepto</label>
                        <input type="text" class="form-control bg-light" id="txtAddPayConcepto" readonly>
                    </div>
                    <div class="form-group">
                        <label class="text-black font-w500">Monto Total</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white">S/</span>
                            </div>
                            <input type="text" class="form-control bg-light font-w600 text-primary" id="txtAddPayTotal" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-black font-w500">Adjuntar Voucher <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="evidencia" id="evidencia" accept="image/*" required>
                            <label class="custom-file-label" for="evidencia" id="lblImagen">Elegir archivo...</label>
                        </div>
                        <small class="text-muted">Formatos: JPG, PNG, GIF</small>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-black font-w500">Observación (Opcional)</label>
                        <textarea class="form-control" rows="3" name="observacion" placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Comprobante</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    console.log("Script del panel cargado");
    $(document).ready(function() {
        console.log("Document ready ejecutado");
        // Fix for Modal Z-Index issues
        $('#pdfModal').appendTo("body");
        $('#AddPagoModal').appendTo("body");
        $('#modalDetallePisos').appendTo("body");

        // Función para cargar detalle por pisos en el modal
        function cargarDetallePisos(idConcepto) {
            if (!idConcepto) {
                return;
            }

            $.ajax({
                url: '{{ route("obtenerDatosPorConcepto") }}',
                type: 'GET',
                data: { idConcepto: idConcepto },
                dataType: 'json',
                beforeSend: function() {
                    $('#modalDetallePisos #pisoContainer').html('<div class="col-12 text-center p-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div></div>');
                },
                success: function(response){
                    try {
                        var propietariosPorPiso = response.propietariosPorPiso;
                        var pagos = response.pagos || [];

                        $('#modalDetallePisos #pisoContainer').empty();

                        if(!propietariosPorPiso || Object.keys(propietariosPorPiso).length === 0) {
                            $('#modalDetallePisos #pisoContainer').html('<div class="col-12 text-center p-3">No hay propietarios registrados.</div>');
                        } else {
                            generarHTMLPisos(propietariosPorPiso, pagos, '#modalDetallePisos #pisoContainer');
                        }
                    } catch (e) {
                         console.error("Error processing floor data:", e);
                         $('#modalDetallePisos #pisoContainer').html('<div class="alert alert-danger">Error mostrando datos: ' + e.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    $('#modalDetallePisos #pisoContainer').html('<div class="alert alert-danger">Error cargando datos del servidor.</div>');
                }
            });
        }

        // Cargar datos cuando se abre el modal de detalle por pisos
        $('#modalDetallePisos').on('show.bs.modal', function() {
            var idConcepto = $('#cbxConcepto').val();
            if (idConcepto) {
                cargarDetallePisos(idConcepto);
            }
        });

        // Actualizar modal si está abierto cuando cambia el concepto
        $('#cbxConcepto').on('change', function(){
            if ($('#modalDetallePisos').hasClass('show')) {
                var idConcepto = $(this).val();
                cargarDetallePisos(idConcepto);
            }
        });

        // Función para cargar datos del concepto
        function cargarDatosConcepto(idConcepto) {
            if (!idConcepto) {
                console.error("ID de concepto no válido");
                return;
            }

            console.log("Cargando datos para concepto:", idConcepto);

            $.ajax({
                url: '{{ route("obtenerDatosPorConcepto") }}',
                type: 'GET',
                data: { idConcepto: idConcepto },
                dataType: 'json',
                beforeSend: function() {
                    $('#chartDataCircle').html('<div class="text-center p-3"><div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div></div>');
                    $('#chartFinancial').html('<div class="text-center p-3"><div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div></div>');
                    $('#chartByFloor').html('<div class="text-center p-3"><div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div></div>');
                },
                success: function(response){
                    console.log("Respuesta recibida:", response);
                    try {
                        var propietariosPorPiso = response.propietariosPorPiso;
                        var pagos = response.pagos || [];
                        var porcentajePagados = response.porcentajePagados || 0;
                        var porcentajeDeben = response.porcentajeDeben || 0;

                        // No generar HTML de pisos aquí, solo se mostrará en el modal

                        // Verificar ApexCharts después de un pequeño delay para asegurar que esté cargado
                        setTimeout(function() {
                            if (typeof ApexCharts !== 'undefined') {
                                chartCircle(porcentajePagados, porcentajeDeben);
                                chartFinancial(
                                    parseFloat(response.total_pagos_prop || 0),
                                    parseFloat(response.total_egresos || 0),
                                    parseFloat(response.total_ingresos_extra || 0)
                                );
                                chartByFloor(propietariosPorPiso, pagos);
                            } else {
                                console.error("ApexCharts not loaded");
                                $('#chartDataCircle').html('<div class="alert alert-warning">Gráfico no disponible (Librería faltante)</div>');
                                $('#chartFinancial').html('<div class="alert alert-warning">Gráfico no disponible (Librería faltante)</div>');
                                $('#chartByFloor').html('<div class="alert alert-warning">Gráfico no disponible (Librería faltante)</div>');
                            }
                        }, 200);

                        // Update Counters with animation
                        if(response.total_pagos_prop !== undefined) {
                            updateCounter('#cardIngresos', parseFloat(response.total_pagos_prop || 0));
                            updateCounter('#cardOtros', parseFloat(response.total_ingresos_extra || 0));
                            updateCounter('#cardEgresos', parseFloat(response.total_egresos || 0));
                            updateCounter('#cardSaldo', parseFloat(response.saldo_general || 0));
                        }
                    } catch (e) {
                         console.error("Error processing dashboard data:", e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.error("Status:", status);
                    console.error("Response:", xhr.responseText);
                    $('#chartDataCircle').html('<div class="alert alert-danger">Error cargando gráfico.</div>');
                    $('#chartFinancial').html('<div class="alert alert-danger">Error cargando gráfico.</div>');
                    $('#chartByFloor').html('<div class="alert alert-danger">Error cargando gráfico.</div>');
                }
            });
        }

        // Event handler para cambio de concepto
        $('#cbxConcepto').on('change', function(){
            var idConcepto = $(this).val();
            cargarDatosConcepto(idConcepto);
        });

        // Cargar datos del primer concepto al iniciar la página
        // Esperar a que todo esté completamente cargado, incluyendo ApexCharts
        function inicializarPanel() {
            var primerConcepto = $('#cbxConcepto option:first').val();
            console.log("Inicializando panel con concepto:", primerConcepto);
            if (primerConcepto) {
                cargarDatosConcepto(primerConcepto);
            } else {
                console.error("No se encontró ningún concepto");
            }
        }

        // Esperar a que la página esté completamente cargada
        $(window).on('load', function() {
            // Esperar un poco más para asegurar que ApexCharts esté cargado
            setTimeout(function() {
                inicializarPanel();
            }, 800);
        });

        function generarHTMLPisos(propietariosPorPiso, pagos, containerSelector) {
            containerSelector = containerSelector || '#pisoContainer';
            let htmlContent = '';

            $.each(propietariosPorPiso, function(piso, propietarios){
                htmlContent += '<div class="col-12 mb-3">';
                htmlContent += '<div class="card shadow-sm">';
                htmlContent += '<div class="card-header bg-light py-2" data-toggle="collapse" data-target="#collapsePiso'+piso+'" style="cursor:pointer; min-height:auto;">';
                htmlContent += '<h5 class="mb-0 text-primary font-w600">Piso ' + piso + ' <i class="fa fa-chevron-down float-right fs-12 mt-1"></i></h5>';
                htmlContent += '</div>';

                htmlContent += '<div id="collapsePiso'+piso+'" class="collapse show">';
                htmlContent += '<div class="card-body p-2">';
                htmlContent += '<div class="row">';

                $.each(propietarios, function(index, propietario){
                    let estadoHtml = '';
                    let idPropietario = propietario.id;

                    // Filter: Check if ANY payment exists with estado_id == 3 for this owner
                    // Ensure 'pagos' is an array
                    let isPaid = false;
                    if(Array.isArray(pagos)) {
                        isPaid = pagos.some(function(p) {
                             return p.id_propietario == idPropietario && p.estado_id == 3;
                        });
                    }

                    if(isPaid) {
                        estadoHtml = '<span class="badge badge-success light badge-xs"><i class="fa fa-check mr-1"></i> Pagado</span>';
                    } else {
                        estadoHtml = '<span class="badge badge-danger light badge-xs">Debe</span>';
                    }

                    htmlContent += '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-2">';
                    htmlContent += '<div class="card bg-white border h-100 mb-0 shadow-none hover-card">';
                    htmlContent += '<div class="card-body p-3">';
                    htmlContent += '<div class="d-flex justify-content-between mb-2">';
                    htmlContent += '<h5 class="font-w700 mb-0 text-dark">' + propietario.departamento + '</h5>';
                    htmlContent += estadoHtml;
                    htmlContent += '</div>';
                    htmlContent += '<p class="fs-12 text-muted mb-0 text-truncate"><i class="fa fa-user mr-1"></i> ' + propietario.nombre + ' ' + (propietario.apellido || '') + '</p>';
                    htmlContent += '</div>';
                    htmlContent += '</div>';
                    htmlContent += '</div>';
                });

                htmlContent += '</div>';
                htmlContent += '</div>';
                htmlContent += '</div>';
                htmlContent += '</div>';
                htmlContent += '</div>';
            });

            $(containerSelector).html(htmlContent);
        }

        // Función para actualizar contadores con animación
        function updateCounter(selector, value) {
            var $element = $(selector);
            var currentValue = parseFloat($element.text().replace('S/ ', '').replace(',', '')) || 0;
            var targetValue = value || 0;

            // Animación suave
            $({ countNum: currentValue }).animate({
                countNum: targetValue
            }, {
                duration: 800,
                easing: 'swing',
                step: function() {
                    $element.text('S/ ' + this.countNum.toFixed(2));
                },
                complete: function() {
                    $element.text('S/ ' + targetValue.toFixed(2));
                }
            });
        }

        function chartCircle(porcentajePagados, porcentajeDeben) {
            $('#txtPagados').text(porcentajePagados + "%");
            $('#txtDebe').text(porcentajeDeben + "%");

            if(window.myRadialChart) {
                window.myRadialChart.destroy();
            }

            var options = {
                series: [porcentajePagados, porcentajeDeben],
                chart: {
                    height: 250,
                    type: 'donut',
                },
                labels: ['Pagados', 'Pendiente'],
                colors: ['#209f84', '#f72b50'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 600,
                                    formatter: function (val) {
                                        return val + "%"
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Cobrado',
                                    formatter: function (w) {
                                        return porcentajePagados + "%"
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    show: false
                }
            };

            window.myRadialChart = new ApexCharts(document.querySelector("#chartDataCircle"), options);
            window.myRadialChart.render();
        }

        // Gráfico de Distribución Financiera (Ingresos vs Egresos)
        function chartFinancial(totalIngresos, totalEgresos, otrosIngresos) {
            if(window.myFinancialChart) {
                window.myFinancialChart.destroy();
            }

            var options = {
                series: [{
                    name: 'Ingresos',
                    data: [totalIngresos, otrosIngresos]
                }, {
                    name: 'Egresos',
                    data: [totalEgresos, 0]
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#209f84', '#f72b50', '#ffc107'],
                xaxis: {
                    categories: ['Pagos Propietarios', 'Otros Ingresos'],
                },
                yaxis: {
                    title: {
                        text: 'Monto (S/)'
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "S/ " + val.toFixed(2)
                        }
                    }
                }
            };

            window.myFinancialChart = new ApexCharts(document.querySelector("#chartFinancial"), options);
            window.myFinancialChart.render();
        }

        // Gráfico de Estado de Pagos por Piso
        function chartByFloor(propietariosPorPiso, pagos) {
            if(window.myFloorChart) {
                window.myFloorChart.destroy();
            }

            var pisos = [];
            var pagadosData = [];
            var pendientesData = [];

            $.each(propietariosPorPiso, function(piso, propietarios) {
                pisos.push('Piso ' + piso);
                var pagados = 0;
                var pendientes = 0;

                $.each(propietarios, function(index, propietario) {
                    var isPaid = false;
                    if(Array.isArray(pagos)) {
                        isPaid = pagos.some(function(p) {
                            return p.id_propietario == propietario.id && p.estado_id == 3;
                        });
                    }
                    if(isPaid) {
                        pagados++;
                    } else {
                        pendientes++;
                    }
                });

                pagadosData.push(pagados);
                pendientesData.push(pendientes);
            });

            var options = {
                series: [{
                    name: 'Pagados',
                    data: pagadosData
                }, {
                    name: 'Pendientes',
                    data: pendientesData
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val
                    }
                },
                colors: ['#209f84', '#f72b50'],
                xaxis: {
                    categories: pisos,
                },
                yaxis: {
                    title: {
                        text: 'Cantidad de Propietarios'
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " propietarios"
                        }
                    }
                }
            };

            window.myFloorChart = new ApexCharts(document.querySelector("#chartByFloor"), options);
            window.myFloorChart.render();
        }

        // --- Event Handlers from Original Code (Refined) ---

        $(document).on('click', '.verPdfPago', function() {
            var pagoId = $(this).data('id');
            var url = '{{ route("pagos.pdf", ":id") }}'.replace(':id', pagoId);
            $('#pdfIframe').attr('src', url);
            $('#pdfModal').modal('show');
        });

        $(document).on('click', '.addPago', function () {
            let idPago = $(this).data('id');
            let idEstado = $(this).data('idestado');
            let departamento = $(this).data('departamento');
            let concepto = $(this).data('concepto');
            let total = $(this).data('total');

            $('#pagoId').val(idPago);
            $('#estadoId').val(idEstado);
            $('#txtAddPayDepartamento').val(departamento);
            $('#txtAddPayConcepto').val(concepto);
            $('#txtAddPayTotal').val(total);

            // Clean prev file input
            $('#evidencia').val('');
            $('#lblImagen').text('Elegir archivo...');

            $('#AddPagoModal').modal('show');
        });

         $('#evidencia').on('change', function() {
            var file = this.files[0];
            if(file){
               $('#lblImagen').text(file.name);
            }
        });

        $('#pagoFormProp').submit(function(e) {
            e.preventDefault();
            var $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

            var formData = new FormData(this);
            $.ajax({
                url: '{{ route("guardar.evidencia.propietario") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#AddPagoModal').modal('hide');
                        swal("¡Éxito!", response.success, "success").then(() => window.location.reload());
                    } else {
                        swal("Error", "No se pudo registrar.", "error");
                    }
                },
                error: function(resp) {
                    swal("Error", "Ocurrió un problema en el servidor.", "error");
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Enviar Comprobante');
                }
            });
        });
    });
</script>
@endsection
