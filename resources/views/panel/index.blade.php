{{-- Extends layout --}}
@extends('layout.default')



{{-- Content --}}
@section('content')
            <!-- row -->
			<div class="container-fluid">
                @if (Auth::user()->id_perfil == 3 )
                 <!-- Mostrar el panel para el propietario con alertas según su deuda -->
                 <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 pb-0 d-sm-flex d-block">
                                <div class="col-sm-12 col-md-12 px-0">
                                    @if ($contdeuda === 0)
                                        <div class="alert alert-success" role="alert">
                                            No tiene deudas pendientes.
                                        </div>
                                    @elseif ($contdeuda >= 1 && $contdeuda <= 2)
                                        <div class="alert alert-warning" role="alert">
                                            Usted tiene {{ $contdeuda }} deuda(s) pendiente(s).
                                        </div>
                                    @elseif ($contdeuda >= 3)
                                        <div class="alert alert-danger" role="alert">
                                            Usted debe {{ $contdeuda }} pagos. Por favor, regularice sus deudas.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mx-0 align-items-center">
                                    <div class="col-sm-12 col-md-12  px-0">
                                        <!-- Listado de deudas -->
                                        <h4>Detalles de su deuda:</h4>
                                        <table class="table   table-hover" id="tblEstadoCuenta">
                                            <thead class="table-primary">
                                              <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Departamento</th>
                                                <th scope="col">Concepto</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">...</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $contador = 1; // Contador inicial
                                                    $totalGeneral = 0; // Inicializar la suma total
                                                @endphp
                                                @foreach ($detdeudas_con_observacion as $detdeuda)
                                                    <tr>
                                                        <th scope="row">{{ $contador }}</th>
                                                        <td>{{ $detdeuda->departamento }}</td>
                                                        <td>{{ $detdeuda->descripcion_concepto }} <br>
                                                            <span class="text-danger">{{ $detdeuda->observacion ?? '' }}</span>
                                                        </td>
                                                        <td class="text-right text-primary">{{ $detdeuda->total }}</td>
                                                        <td>{{ $detdeuda->estado }}</td>

                                                        <td>
                                                            @if($detdeuda->idestado==1)
                                                                <a href="javascript:void(0)"
                                                                    data-id="{{ $detdeuda->id }}"
                                                                    data-idestado="{{ $detdeuda->idestado }}"
                                                                    data-departamento="{{ $detdeuda->departamento }}"
                                                                    data-concepto="{{ $detdeuda->descripcion_concepto }}"
                                                                    data-total="{{ $detdeuda->total }}"
                                                                    class="btn btn-outline-warning shadow btn-sm sharp mr-1 addPago">
                                                                    <i class="fa fa-money fa-2x"></i>
                                                                </a>
                                                            @endif
                                                            @if($detdeuda->idestado==3)
                                                                <a href="javascript:void(0)" data-id="{{ $detdeuda->idpago }}"  class="btn btn-outline-success shadow btn-sm sharp mr-1 verPdfPago"><i class="fa fa-print fa-2x"></i></a>
                                                            @endif
                                                        </td>
                                                      </tr>
                                                    @php
                                                        $contador++; // Incrementar el contador
                                                        $totalGeneral += $detdeuda->total; // Sumar al total general
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right text-primary">Total Deuda:</th>
                                                    <th class="text-right text-primary"><strong class="text-primary">{{ number_format($totalGeneral, 2)  }}</strong></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="modal fade bd-example-modal-lg" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="pdfModalLabel">Impresión del Pago</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <iframe id="pdfIframe" src="" width="100%" height="600px"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="AddPagoModal" tabindex="-1" aria-labelledby="AddPagoModalLabel-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form id="pagoFormProp"  action="{{ route('guardar.evidencia.propietario') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" id="pagoId">
                                                    <input type="hidden" name="estadoId" id="estadoId">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title" id="AddPagoModalLabel-1">Adjuntar Voucher del Pago</h4>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="txtAddPayDepartamento">Departamento:</label>
                                                            <input type="text" class="form-control" id="txtAddPayDepartamento" name="txtAddPayDepartamento" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="txtAddPayConcepto">Concepto:</label>
                                                            <input type="text" class="form-control" id="txtAddPayConcepto" name="txtAddPayConcepto" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="txtAddPayTotal">Total:</label>
                                                            <input type="text" class="form-control" id="txtAddPayTotal" name="txtAddPayTotal" readonly>
                                                        </div>
                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="evidencia" id="evidencia" accept="image/*" required>
                                                                <label class="custom-file-label" for="evidencia" id="lblImagen">Seleccionar archivo</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <label for="observacion">Observacion:</label>
                                                            <textarea class="form-control" rows="4" id="observacion" name="observacion"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>

                                            </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif (Auth::user()->id_perfil == 1 || Auth::user()->id_perfil == 2)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="input-group search-area d-inline-flex">
                            <select class="form-control" id="cbxConcepto" name="cbxConcepto" required>
                                @foreach ($conceptos as $concepto)
                                    <option value="{{ $concepto->id }}">
                                        {{ $concepto->descripcion_concepto . " " . ($concepto->nombreMes ? $concepto->nombreMes->nombremes : '') . " " . ($concepto->anio?$concepto->anio : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
					<div id="pisoContainer" class="row">
					</div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 pb-0 d-sm-flex d-block">
                                <div>
                                    <h4 class="fs-20 text-black">Datos en porcentaje</h4>
                                    <p class="mb-0 fs-13">Ver datos en porcentaje en pye.</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mx-0 align-items-center">
                                    <div class="col-sm-7 col-md-7  px-0">
                                        <div id="chartDataCircle"></div>
                                    </div>
                                    <div class="col-sm-5 col-md-5 px-0">
                                        <div class="panel-chart-deta">
                                            <div class="col px-0">
                                                <span></span>
                                                <div>
                                                    <p class="mb-1">Pagados <h3 id="txtPagados"></h3></p>
                                                    {{--<h3 class="fs-20 font-w600 text-black">$632,662,662</h3>--}}
                                                </div>
                                            </div>
                                            <div class="col px-0">
                                                <span></span>
                                                <div>
                                                    <p class="mb-1">Debe <h3 id="txtDebe"></h3></p>
                                                    {{--<h3 class="fs-20 font-w600 text-black">$21,412,556</h3>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
                @endif
            </div>
@endsection
<script type="module">
	$(document).ready(function() {
		setTimeout(function() {
			$('#cbxConcepto').val($('#cbxConcepto option:first').val()).change();
		}, 100);
		$('#cbxConcepto').change(function(){
			var idConcepto = $(this).val();

			$.ajax({
				url: '{{ route("obtenerDatosPorConcepto") }}',
				type: 'GET',
				data: {
					idConcepto: idConcepto,
				},
				success: function(response){
					var propietariosPorPiso = response.propietariosPorPiso;
					var pagos = response.pagos;

					var porcentajePagados = response.porcentajePagados;
					var porcentajeDeben = response.porcentajeDeben;

					$('#pisoContainer').empty();
					generarHTMLPisos(propietariosPorPiso, pagos);


					// Actualiza los datos del gráfico Chartist
					chartCircle(porcentajePagados, porcentajeDeben);
				}
			});
		});

		function generarHTMLPisos(propietariosPorPiso, pagos) {
			$('#pisoContainer').empty();

			$.each(propietariosPorPiso, function(piso, propietarios){
				var html = '<div class="col-xl-4 col-xxl-12">';
				html += '<div class="row">';
				html += '<div class="col-xl-12">';
				html += '<div class="card">';
				html += '<div class="card-header border-0 pb-0 d-sm-flex d-block">';
				html += '<a href="javascript:void(0);" class="btn btn-primary btn-lg btn-block">Piso ' + piso + '</a>';
				html += '</div>';
				html += '<div class="card-body contacts-list">';

				$.each(propietarios, function(index, propietario){
					html += '<div class="media mb-2 align-items-center">';
					html += '<h3 class="fs-20 font-w600 text-black p-2">' + propietario.departamento + '</h3>';
					html += '<div class="media-body">';
					html += '<h6 class="text-black fs-12 mb-0">' + propietario.nombre + '</h6>';
					html += '<span class="fs-14">' + propietario.correo_electronico + '</span>';
					html += '</div>';

					// Encontrar el estado del pago
					var estadoPago = '';
					var idPropietario = propietario.id;
					var pagosPropietario = pagos.filter(function(pago){
						return pago.id_propietario === idPropietario;
					});
					if(pagosPropietario.length > 0){
						if(pagosPropietario[0].detalles.length > 0){
							if(pagosPropietario[0].estado_id == 3) {
								estadoPago = 'Pagado';
								html += '<a class="btn btn-md btn-outline-primary rounded-0 estado-btn" href="javascript:void(0);" >' + estadoPago + '</a>';
							} else {
								estadoPago = 'Debe';
								html += '<a class="btn btn-md btn-outline-danger rounded-0 estado-btn" href="javascript:void(0);" >' + estadoPago + '</a>';
							}
						} else {
							html += '<a class="btn btn-md btn-outline-danger rounded-0 estado-btn" href="javascript:void(0);" >Debe</a>';
						}

					} else {
						html += '<a class="btn btn-md btn-outline-danger rounded-0 estado-btn" href="javascript:void(0);" >Debe</a>';
					}

					html += '</div>';
				});

				html += '</div>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
				html += '</div>';

				$('#pisoContainer').append(html);
			});
		}

		function chartCircle(porcentajePagados, porcentajeDeben) {
			$('#txtPagados').empty();
		    $('#txtDebe').empty();

			$('#txtPagados').append(porcentajePagados+"%");
			$('#txtDebe').append(porcentajeDeben+"%");

			var optionsDataCircle = {
				chart: {
					type: 'radialBar',
					//width:320,
					height: 320,
					offsetY: 0,
					offsetX: 0,

				},
				plotOptions: {
					radialBar: {
					size: undefined,
					inverseOrder: false,
					hollow: {
						margin: 0,
						size: '35%',
						background: 'transparent',
					},



					track: {
						show: true,
						background: '#e1e5ff',
						strokeWidth: '10%',
						opacity: 1,
						margin: 10, // margin is in pixels
					},


					},
				},
				responsive: [{
				breakpoint: 480,
				options: {
					chart: {
					offsetY: 0,
					offsetX: 0
				},
					legend: {
					position: 'bottom',
					offsetX:0,
					offsetY: 0
					}
				}
				}],

				fill: {
				opacity: 1
				},

				colors:['#6418C3', '#e06666'],
				series: [porcentajePagados, porcentajeDeben],
				labels: ['Pagados', 'Debe'],
				legend: {
					fontSize: '16px',
					show: false,
				},
			}

			var chartDataCircle1 = new ApexCharts(document.querySelector('#chartDataCircle'), optionsDataCircle);
			chartDataCircle1.render();
		};

        $('#tblEstadoCuenta').on('click', '.verPdfPago', function() {
			var pagoId = $(this).data('id');
			var idestado = $(this).data('idestado');
			var url = '{{ route("pagos.pdf", ":id") }}';
			url = url.replace(':id', pagoId);

			$('#pdfIframe').attr('src', url);
			$('#pdfModal').modal('show');

		});
        muestraResumenGastosIngresos();
        function muestraResumenGastosIngresos() {
            $.ajax({
                url: '{{ route("obtenerResumenGastosIngresos") }}',
                method: 'GET',
                success: function(response) {
                    const ingresos = parseFloat(response.ingresos || 0).toFixed(2);
                    const egresos = parseFloat(response.egresos || 0).toFixed(2);
                    const saldo = parseFloat(response.saldo || 0).toFixed(2);
                    const verpopup = parseInt(response.verpopup);
                    // Construir el mensaje del toastr
                    if(verpopup==1){
                        const mensaje = `
                            Ingresos: S/ ${ingresos}<br>
                            Egresos: S/ ${egresos}<br>
                            Saldo: S/ ${saldo}<br>
                            <a href="{{ route('noticias') }}" class="text-white " style="text-decoration: underline;">Ver más</a>
                        `;

                        // Mostrar el toastr
                        toastr.info(mensaje, "Resumen de Gastos e Ingresos", {
                            positionClass: "toast-top-center",
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true,
                            showMethod: "fadeIn",
                            hideMethod: "fadeOut",
                        });
                    }
                },
                error: function(xhr) {
                    toastr.error("No se pudo obtener el resumen de gastos e ingresos.", "Error", {
                        positionClass: "toast-top-center",
                        timeOut: 5000,
                        closeButton: true,
                        progressBar: true,
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                    });
                }
            });
        }


        $(document).on('click', '.verificaPago', function () {
            let pagoId = $(this).data('id');
            let estadoId = $(this).data('idestado');

            // Asignar datos al modal
            $('#pagoIdEvidencia').val(pagoId);
            $('#estadoIdEvidencia').val(estadoId);

            // Abrir el modal
            $('#ConfirmaEvidenciaModal').modal('show');
        });

        $(document).on('click', '.addPago', function () { console.log('aaaaasdeeeeeeeeeeee',);
            // Capturar los datos del botón
            let idPago = $(this).data('id');
            let idEstado = $(this).data('idestado');
            let departamento = $(this).data('departamento');
            let concepto = $(this).data('concepto');
            let total = $(this).data('total');
            console.log('aaaaasdeeeeeeeeeeee',idPago);
            // Rellenar los campos del modal
            $('#pagoId').val(idPago);
            $('#estadoId').val(idEstado);
            $('#txtAddPayDepartamento').val(departamento);
            $('#txtAddPayConcepto').val(concepto);
            $('#txtAddPayTotal').val(total);

            // Mostrar el modal
            $('#AddPagoModal').modal('show');
        });

        $('#evidencia').on('change', function() {
			var file = this.files[0];
			var fileType = file.type;
			var match = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
			if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))) {
				swal("Error!", 'Seleccione una imagen válida (JPEG/JPG/PNG/GIF).', "error");
				$('#evidencia').val('');
            	$('#evidencia').next('.custom-file-label').text('Seleccionar archivo');
			}else{
				var fileName = file.name;
                $(this).next('.custom-file-label').text(fileName);
			}
		});

        $('#pagoFormProp').submit(function(e) {
			e.preventDefault();
			$(this).find('button[type="submit"]').prop('disabled', true);
			var formData = new FormData(this);
			$.ajax({
				url: '{{ route('guardar.evidencia.propietario') }}',
				method: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					if (response.success) {
                        limpiarFormEvidencia();
                        swal("Registro Correcto!", response.success, "success")
                            .then(() => {
                                window.location.reload();
                            });
                        $('#tblPago').DataTable().ajax.reload(null, false);
                    } else {
                        swal("Error!", 'Ocurrió un error', "error");
                        console.log('Ocurrió un error');
                    }
                    $('#pagoFormProp').find('button[type="submit"]').prop('disabled', false);
				},
				error: function(response) {
                    let msgerror='';
                    if(response.responseJSON && response.responseJSON.error){
                        msgerror = response.responseJSON.error;
                    }
                    console.log('Ocurrió un error',response.responseJSON.error);
                    swal("Error!", 'Ocurrió un error. '+msgerror, "error")

                    $('#pagoFormProp').find('button[type="submit"]').prop('disabled', false);
				}
			});
		});

        function limpiarFormEvidencia(){
			$('#pagoFormProp')[0].reset();
        	$('#lblImagen').text('Seleccionar archivo');
			$('#AddPagoModal').modal('hide');
		}
	});
</script>
