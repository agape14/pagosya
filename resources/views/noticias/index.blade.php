{{-- Extends layout --}}
@extends('layout.default')



{{-- Content --}}
@section('content')

			<div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Noticias</a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
					<div class="col-xl-8 col-lg-12">
                        <div class="card">
                            <div class="card-header  border-0 pb-0">
                                <h4 class="card-title">Resumen de Ingresos y Gastos</h4>
                                @if(auth()->check() && auth()->user()->id === 1)
                                <div class="dropdown custom-dropdown mb-0">
                                    <div class="btn sharp btn-primary tp-btn" data-toggle="dropdown">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g>
										</svg>
									</div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="javascript:void(0);" id="btnActualizarTotales">Actualizar totales</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="btnActivarNotificarUsuarios">Activar Notificacion Usuarios</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="btnNotificarUsuarios">Notificar Usuarios</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="btnVerIdTorre">Ver Id Torre</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div id="DZ_W_Todo1" class="widget-media dz-scroll height370">
                                    <ul class="timeline">
                                        <li>
                                            <div class="timeline-panel">
												<div class="media mr-2">
													<i class="fa fa-plus"></i>
												</div>
                                                <div class="media-body">
													<h5 class="mb-1">INGRESOS <small class="text-muted">|</small></h5>
													<p class="mb-1">Para visualizar los ingresos del mes, debe dar click en el boton [Ver Ingresos]</p>
													<a href="javascript:void(0);" data-toggle="modal" data-target="#AddVerIngresosModal" class="btn btn-success btn-sm shadow">Ver Ingresos</a>
                                                    @if($totalPagos!=0)
                                                    <br> Total Pagos: <small class="text-muted">S/.</small> {{ number_format($totalPagos, 2) }}
                                                    @endif
                                                    <br> Total Ingresos: <small class="text-muted">S/.</small> {{ number_format($totalIngresos, 2) }}
                                                    <br> Intereses Bancarios: <small class="text-muted">S/.</small> {{ number_format($saldoFinalInteres, 2) }}
												</div>
                                                <div class="dropdown">
													<button type="button" class="btn btn-success light sharp" >
														<small class="text-muted">S/.</small> {{ number_format($totales_ingresos, 2) }}
													</button>
												</div>
											</div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
												<div class="media mr-2">
													<i class="fa fa-minus"></i>
												</div>
                                                <div class="media-body">
													<h5 class="mb-1">GASTOS <small class="text-muted">|</small></h5>
                                                    <p class="mb-1">Para visualizar los ingresos del mes, debe dar click en el boton [Ver Gastos]</p>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#AddVerGastosModal" class="btn btn-danger btn-sm shadow">Ver Gastos</a>
												</div>
                                                <div class="dropdown">
													<button type="button" class="btn btn-danger light sharp" >
														<small class="text-muted">S/.</small> {{ number_format($totales_egresos, 2) }}
													</button>
												</div>
											</div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
												<div class="media mr-2">
													<i class="fa fa-money"></i>
												</div>
                                                <div class="media-body">
													<h5 class="mb-1 text-info">SALDO <small class="text-muted"></small></h5>
												</div>
                                                <div class="dropdown">
													<button type="button" class="btn btn-info light sharp" >
														<small class="text-muted">S/.</small> {{ number_format($totales_saldo, 2) }}
													</button>
												</div>
											</div>
                                        </li>
                                        {{--
                                        <li>
                                            <div class="timeline-panel">
												<div class="media mr-2 media-info">
													KG
												</div>
												<div class="media-body">
													<h5 class="mb-1">Resport created successfully</h5>
													<small class="d-block">29 July 2020 - 02:26 PM</small>
												</div>
												<div class="dropdown">
													<button type="button" class="btn btn-info light sharp" data-toggle="dropdown">
														<svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="#">Edit</a>
														<a class="dropdown-item" href="#">Delete</a>
													</div>
												</div>
											</div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media mr-2 media-success">
													<i class="fa fa-home"></i>
												</div>
												<div class="media-body">
													<h5 class="mb-1">Reminder : Treatment Time!</h5>
													<small class="d-block">29 July 2020 - 02:26 PM</small>
												</div>
												<div class="dropdown">
													<button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
														<svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="#">Edit</a>
														<a class="dropdown-item" href="#">Delete</a>
													</div>
												</div>
											</div>
                                        </li>
										 <li>
                                            <div class="timeline-panel">
												<div class="media mr-2">
													<img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
												</div>
                                                <div class="media-body">
													<h5 class="mb-1">Dr sultads Send you Photo</h5>
													<small class="d-block">29 July 2020 - 02:26 PM</small>
												</div>
												<div class="dropdown">
													<button type="button" class="btn btn-primary light sharp" data-toggle="dropdown">
														<svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="#">Edit</a>
														<a class="dropdown-item" href="#">Delete</a>
													</div>
												</div>
											</div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
												<div class="media mr-2 media-danger">
													KG
												</div>
												<div class="media-body">
													<h5 class="mb-1">Resport created successfully</h5>
													<small class="d-block">29 July 2020 - 02:26 PM</small>
												</div>
												<div class="dropdown">
													<button type="button" class="btn btn-danger light sharp" data-toggle="dropdown">
														<svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="#">Edit</a>
														<a class="dropdown-item" href="#">Delete</a>
													</div>
												</div>
											</div>
                                        </li>
                                        <li>
                                            <div class="timeline-panel">
                                                <div class="media mr-2 media-primary">
													<i class="fa fa-home"></i>
												</div>
												<div class="media-body">
													<h5 class="mb-1">Reminder : Treatment Time!</h5>
													<small class="d-block">29 July 2020 - 02:26 PM</small>
												</div>
												<div class="dropdown">
													<button type="button" class="btn btn-primary light sharp" data-toggle="dropdown">
														<svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="#">Edit</a>
														<a class="dropdown-item" href="#">Delete</a>
													</div>
												</div>
											</div>
                                        </li>
                                        --}}
                                    </ul>
                                </div>

                                <div class="modal fade" id="AddVerIngresosModal" tabindex="-1" aria-labelledby="AddVerIngresosModalLabel-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="AddVerIngresosModalLabel-1">Ver Ingresos</h4>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- TABLA DEL LISTADO DE LOS INGRESOS PARA DESCARGAR --}}
                                            <div class="table-responsive recentOrderTable">
                                                <table class="table   table-hover" id="tblVerIngresos">
                                                    <thead class="table-primary">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th>Fecha</th>
                                                        <th>Concepto</th>
                                                        <th>Total</th>
                                                        <th scope="col">...</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $contador = 1; // Contador inicial
                                                            $totalGeneral = 0; // Inicializar la suma total
                                                        @endphp
                                                        @foreach ($ingresos as $ingreso)
                                                            <tr>
                                                                <th scope="row">{{ $contador }}</th>
                                                                <td>{{ \Carbon\Carbon::parse($ingreso->fecha)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    @foreach($ingreso->detalles as $detalle)
                                                                        <p>
                                                                            {{ $detalle->concepto->descripcion_concepto ?? 'Sin concepto' }} <br>
                                                                            {{ ($detalle->concepto->nombreMes ? $detalle->concepto->nombreMes->nombremes : '') . " " . ($detalle->concepto->anio? $detalle->concepto->anio : '') }}<br>
                                                                            <br>
                                                                            <span class="text-danger">{{ $detalleg->descripcion ?? '' }}</span>
                                                                        </p>
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-right text-primary">S/ {{ number_format($ingreso->total, 2) }}</td>
                                                                <td><a href="javascript:void(0)" data-id="{{ $ingreso->id }}"  class="btn btn-outline-success shadow btn-sm sharp mr-1 verPdfIngresos"><i class="fa fa-print fa-2x"></i></a></td>

                                                            </tr>
                                                            @php
                                                                $contador++; // Incrementar el contador
                                                                $totalGeneral += $ingreso->total; // Sumar al total general
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class="text-right text-primary">Total Ingresos:</th>
                                                            <th class="text-right text-primary"><strong class="text-primary">S/ {{ number_format($totalGeneral,2) }}</strong></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="AddVerGastosModal" tabindex="-1" aria-labelledby="AddVerGastosModalLabel-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="AddVerGastosModalLabel-1">Ver Gastos</h4>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- TABLA DEL LISTADO DE LOS INGRESOS PARA DESCARGAR --}}
                                            <div class="table-responsive recentOrderTable">
                                                <table class="table   table-hover" id="tblVerGastos">
                                                    <thead class="table-primary">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th>Fecha</th>
                                                        <th>Concepto</th>
                                                        <th>Total</th>
                                                        <th scope="col">...</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $contadorg = 1; // Contador inicial
                                                            $totalGeneralg = 0; // Inicializar la suma total
                                                        @endphp
                                                        @foreach ($gastos as $gasto)
                                                            <tr>
                                                                <th scope="row">{{ $contadorg }}</th>
                                                                <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    @foreach($gasto->detalles as $detalleg)
                                                                        <p>
                                                                            {{ $detalleg->concepto->descripcion_concepto ?? 'Sin concepto' }} <br>
                                                                            {{ ($detalleg->concepto->nombreMes ? $detalleg->concepto->nombreMes->nombremes : '') . " " . ($detalleg->concepto->anio? $detalleg->concepto->anio : '') }}
                                                                            <br>
                                                                            <span class="text-danger">{{ $detalleg->descripcion ?? '' }}</span>
                                                                        </p>

                                                                    @endforeach
                                                                </td>
                                                                <td class="text-right text-primary">S/ {{ number_format($gasto->total, 2) }}</td>
                                                                <td><a href="javascript:void(0)" data-id="{{ $gasto->id }}"  class="btn btn-outline-success shadow btn-sm sharp mr-1 verPdfGastos"><i class="fa fa-print fa-2x"></i></a></td>

                                                            </tr>
                                                            @php
                                                                $contadorg++; // Incrementar el contador
                                                                $totalGeneralg += $gasto->total; // Sumar al total general
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class="text-right text-primary">Total Gastos:</th>
                                                            <th class="text-right text-primary"><strong class="text-primary">S/ {{ number_format($totalGeneralg, 2)  }}</strong></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="modal fade bd-example-modal-lg" id="pdfVerPdfGastoIngreso" tabindex="-1" role="dialog" aria-labelledby="pdfVerPdfGastoIngresoLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="pdfVerPdfGastoIngresoLabel">Impresión del Archivo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <iframe id="pdfIframeIngresoGasto" src="" width="100%" height="600px"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="card">
                            <div class="card-header border-0 pb-0">
                                <h4 class="card-title">Noticias Semanales</h4>
                            </div>
                            <div class="card-body">
                                <div id="DZ_W_TimeLine" class="widget-timeline dz-scroll height370">
                                    <ul class="timeline">
                                        <li>
                                            <div class="timeline-badge primary"></div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span>Muy pronto</span>
                                                <h6 class="mb-0">Se estara publicando mas detalles. <strong class="text-primary">¡No se lo pierda!</strong>.</h6>
                                            </a>
                                        </li>
                                        {{--
                                        <li>
                                            <div class="timeline-badge info">
                                            </div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span>20 minutes ago</span>
                                                <h6 class="mb-0">New order placed <strong class="text-info">#XF-2356.</strong></h6>
												<p class="mb-0">Quisque a consequat ante Sit amet magna at volutapt...</p>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="timeline-badge danger">
                                            </div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span>30 minutes ago</span>
                                                <h6 class="mb-0">john just buy your product <strong class="text-warning">Sell $250</strong></h6>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="timeline-badge success">
                                            </div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span>15 minutes ago</span>
                                                <h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="timeline-badge warning">
                                            </div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span>20 minutes ago</span>
                                                <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="timeline-badge dark">
                                            </div>
                                            <a class="timeline-panel text-muted" href="#">
                                                <span>20 minutes ago</span>
                                                <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                            </a>
                                        </li>
                                         --}}

                                    </ul>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
            </div>

@endsection

<script type="module">
	$(document).ready(function() {
        $('#tblVerIngresos').on('click', '.verPdfIngresos', function() {
            const ingresoId = $(this).data('id'); // Obtener el ID del ingreso desde el botón
            const url = `/ingresosverpdf/${ingresoId}`; // Construir la URL para obtener la evidencia

            // Llamada AJAX para obtener la evidencia
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    if (response.evidencia_url) {
                        // Asignar la URL al iframe
                        $('#pdfIframeIngresoGasto').attr('src', response.evidencia_url);
                        // Abrir el modal
                        $('#pdfVerPdfGastoIngreso').modal('show');
                    } else {
                        alert('No se encontró el archivo.');
                    }
                },
                error: function () {
                    alert('Ocurrió un error al intentar obtener el archivo.');
                }
            });

		});


        $('#tblVerGastos').on('click', '.verPdfGastos', function() {
            const gastoId = $(this).data('id'); // Obtener el ID del ingreso desde el botón
            const url = `/gastosverpdf/${gastoId}`; // Construir la URL para obtener la evidencia

            // Llamada AJAX para obtener la evidencia
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    if (response.evidencia_url) {
                        // Asignar la URL al iframe
                        $('#pdfIframeIngresoGasto').attr('src', response.evidencia_url);
                        // Abrir el modal
                        $('#pdfVerPdfGastoIngreso').modal('show');
                    } else {
                        alert('No se encontró el archivo.');
                    }
                },
                error: function () {
                    alert('Ocurrió un error al intentar obtener el archivo.');
                }
            });

		});

        $(document).on('click', '#btnActualizarTotales', function () {
            $.ajax({
                url: '{{ route("acumuladores.actualizarTotales") }}', // Ruta definida en web.php
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Actualizacion Correcta!', response.message, 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo realizar la operación. ' +response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Ocurrió un error.', 'error');

                }
            });
        });

        $(document).on('click', '#btnNotificarUsuarios', function () {
            $.ajax({
                url: "{{ route('notificar.propietarios') }}", // Ruta al controlador
                type: "GET",
                success: function(response) {
                    // Mostrar alerta de éxito
                    /*Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    });*/
                    swal("Actualizado!", response.message, "success")
                },
                error: function(xhr) {
                    // Mostrar alerta de error
                    swal("¡Error!", xhr.responseJSON.message || 'Ocurrió un error inesperado.', "error")
                }
            });
        });

        $(document).on('click', '#btnActivarNotificarUsuarios', function () {
            swal({
                title: "¿Estás seguro?",
                text: "¡Actualizando la Notificacion de Usuarios!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#6418C3",
                cancelButtonColor: '#d33',
                confirmButtonText: "Sí, actualizar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: !1,
                closeOnCancel: !1
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/habilitanotifuser',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            swal("Actualizado!", response.success, "success")
                            console.log('Popup des/habilitado exitosamente.');
                        },
                        error: function(xhr, status, error) {
                            swal("Error!", "Error al des/habilitado Popup: "+error, "error")
                            console.error('Error al des/habilitado Popup:', error);
                        }
                    });

                }else{
                    swal("Cancelado!", "Se cancelo la accion", "error")
                }
            })
        });
        //btnVerIdTorre
        $(document).on('click', '#btnVerIdTorre', function () {

            $.ajax({
                url: "{{ route('verificar_parametro') }}", // Ruta al controlador
                type: "GET",
                success: function(response) {
                    swal("ID de Torre:", response.ID_TORRE_SISTEMA, "success")
                },
                error: function(xhr) {
                    // Mostrar alerta de error
                    swal("¡Error!", xhr.responseJSON.message || 'Ocurrió un error inesperado.', "error")
                }
            });

        });
	});
</script>
