{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')

			<div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Finanzas</a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
					<div class="col-xl-12 col-lg-12">
                        <div class="card shadow-md">
                            <div class="card-header border-0 pb-0">
                                <h4 class="card-title">Resumen de Ingresos y Gastos</h4>
                                @if(auth()->check() && auth()->user()->id_perfil <= 2)
                                <div class="dropdown custom-dropdown mb-0">
                                    <div class="btn sharp btn-primary tp-btn" data-toggle="dropdown">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g>
										</svg>
									</div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="javascript:void(0);" id="btnActualizarTotales">Actualizar totales</a>
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
                                                    @if(isset($totalPagos))
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
                </div>
            </div>

@endsection

@section('scripts')
<script type="module">
	$(document).ready(function() {
        $('#tblVerIngresos').on('click', '.verPdfIngresos', function() {
            const ingresoId = $(this).data('id');
            const url = `/ingresosverpdf/${ingresoId}`;
            
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    if (response.evidencia_url) {
                        $('#pdfIframeIngresoGasto').attr('src', response.evidencia_url);
                        $('#pdfVerPdfGastoIngreso').modal('show');
                    } else {
                        swal("Error", 'No se encontró el archivo.', "error");
                    }
                },
                error: function () {
                    swal("Error", 'Ocurrió un error al intentar obtener el archivo.', "error");
                }
            });

		});

        $('#tblVerGastos').on('click', '.verPdfGastos', function() {
            const gastoId = $(this).data('id');
            const url = `/gastosverpdf/${gastoId}`;

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    if (response.evidencia_url) {
                        $('#pdfIframeIngresoGasto').attr('src', response.evidencia_url);
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
                url: '{{ route("acumuladores.actualizarTotales") }}', 
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
	});
</script>
@endsection
