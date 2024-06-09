
@extends('layout.default')

@section('content')
<div class="container-fluid">
	<div class="page-titles">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">Pago</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Registro</a></li>
		</ol>
	</div>
	
	<div class="row">
		<div class="col-xl-12">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header p-2">
							<div class="card-title">
								Filtro
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="card-body  p-2">
                            <form id="frmBuscarPago"  class="form-valide-with-icon">
								@csrf
                                <div class="card-body pb-2">
									<div class="row">
										<div class="col-xl-3 col-xxl-6 col-sm-6 mb-3">
											<div class="form-group">
												<label class="form-label" for="cbxConcepto">Concepto</label>
												<select class="form-control" id="cbxConcepto" name="cbxConcepto" required>
													<option value="">Seleccione un Concepto</option>
													@foreach ($conceptos as $concepto)
														<option value="{{ $concepto->id }}">
															{{ $concepto->descripcion_concepto . " " . ($concepto->nombreMes ? $concepto->nombreMes->nombremes : '') . " " . ($concepto->anio?$concepto->anio : '') }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-xl-3 col-xxl-6 col-sm-6 mb-3">
											<div class="form-group">
												<label class="form-label" for="cbxPropietario">Propietario</label>
												<select class="form-control" id="cbxPropietario" name="cbxPropietario" readonly>
													<option value="0">Todos los Propietario</option>
													@foreach ($propietarios as $propietario)
														<option value="{{ $propietario->id }}">{{ $propietario->departamento }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-xl-2 col-xxl-6 col-sm-6 mb-3">
											<div class="form-group">
												<label class="form-label" for="cbxEstado">Estado</label>
												<select class="form-control" id="cbxEstado" name="cbxEstado" readonly>
													<option value="">Todos los Estados</option>
													@foreach ($estadopagos as $estadopago)
														<option value="{{ $estadopago->id }}">{{ $estadopago->nombre }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-xl-2 col-xxl-6 col-sm-6 mb-3">
											<div class="form-group">
												<label class="form-label" for="txtFecha">Fecha</label>
												<input class="datepicker-default form-control" id="txtFecha" name="txtFecha">
											</div>
											
										</div>
										<div class="col-xl-2 col-xxl-6 col-sm-6 mb-3">
											<button class="btn btn-primary mt-4" title="Click para buscar" type="button"><i class="fa fa-filter" aria-hidden="true"></i> Buscar</button>
											<button class="btn btn-danger mt-4 light ms-1" title="Click para limpiar el filtro" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
										</div>
									</div>
								</div>
                            </form>
						</div>
					</div>
				</div>
				<!--<div class="cm-content-body form excerpt"></div>-->
			</div>
			<!--<div class="mb-5">
				<a href="#" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Pago</a>
			</div>-->
			<div class="row ">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header p-2">
							<div class="card-title">
								Listado
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="card-body  p-2">
							<div class="table-responsive">
								<table class="table table-responsive-md mt-2" id="tblPago">
									<thead>
										<tr>
											<th class="width50">#</th>
											<th>Departamento</th>
											<th>Concepto</th>
											<th>Total</th>
											<th>Fecha</th>
											<th>Estado</th>
											<th>Acciones</th> <!-- Añadir la columna de acciones aquí -->
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
							<div class="modal fade" id="AddPagoModal" tabindex="-1" aria-labelledby="AddPagoModalLabel-1" aria-hidden="true">
								<div class="modal-dialog">
								<div class="modal-content">
									<form id="pagoForm"  action="{{ route('guardar.evidencia') }}" method="POST" enctype="multipart/form-data">
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
													<label class="custom-file-label" for="evidencia" id="lblImagen">Seleccionar imagen</label>
												</div>
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

							<div class="modal fade bd-example-modal-lg" id="ConfirmaEvidenciaModal" tabindex="-1" aria-labelledby="ConfirmaEvidenciaModal-1" aria-hidden="true">
								<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<form id="confirmaPagoForm"  action="{{ route('confirmar.evidencia') }}" method="POST" enctype="multipart/form-data">
										@csrf
										<input type="hidden" name="pagoIdEvidencia" id="pagoIdEvidencia">
										<input type="hidden" name="estadoIdEvidencia" id="estadoIdEvidencia">
										<div class="modal-header">
											<h4 class="modal-title" id="ConfirmaEvidenciaModal-1">Confirmar Voucher del Pago</h4>
											<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-xl-12">
													<div class="form-group">
														<h4 class="text-primary d-inline">Departamento:</h4>
														<span id="txtConfEvidenciaDepartamento" class="pull-right f-s-16"></span>
													</div>
													<div class="form-group">
														<h4 class="text-primary d-inline">Concepto:</h4>
														<span id="txtConfEvidenciaConcepto" class="pull-right f-s-16"></span>
													</div>
													<div class="form-group">
														<h4 class="text-primary d-inline">Total:</h4>
														<span id="txtConfEvidenciaTotal" class="pull-right f-s-16"></span>
													</div>
													<div class="profile-blog mb-5">
														<h5 class="text-primary d-inline">Imagen Voucher de Pago</h5><a href="javascript:void()" class="pull-right f-s-16"> </a>
														<img id="evidenciaImg" src="" alt="" class="img-fluid mt-4 mb-4 w-100">
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-danger light" data-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-info">Confirmar</button>
										</div>
									</form>
									
								</div>
								</div>
							</div>

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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
<script type="module">
	$(document).ready(function() {
		$("#cbxConcepto").select2();
		$("#cbxPropietario").select2();
		var tblPago =$('#tblPago').DataTable({
			processing: true,
			serverSide: true,
			language: {url: '/datatables/spanish.json'},
			ajax: {
				url: '{{ route('pagos.data') }}',
					data: function(d) {
						d.concepto = $('#cbxConcepto').val();
						d.propietario = $('#cbxPropietario').val();
						d.estado = $('#cbxEstado').val();
						d.fecha = $('#txtFecha').val();
					}
			},
			columns: [
				{ data: 'selectgroup', name: 'selectgroup', orderable: false, searchable: false  },
				{ data: 'propietario', name: 'propietario' },
				{ data: 'concepto', name: 'concepto' },
				{ data: 'total', name: 'total' },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'estado', name: 'estado', orderable: false, searchable: false },
				{ data: 'acciones', name: 'acciones', orderable: false, searchable: false } // Añadir la columna de acciones aquí
			]
		});

		// Recargar la tabla cuando se cambien los filtros
		$('#cbxConcepto, #cbxPropietario,#cbxEstado,#txtFecha').change(function() {
			tblPago.draw();
		});

		$('#tblPago').on('click', '.addPago', function() {
			var id = $(this).data('id');
			var idestado = $(this).data('idestado');
			if(idestado===1){
				$.get('/pagos/getpogramacion/' + id, function(data) {
					$('#pagoId').val(data.pagos[0].id);
					$('#estadoId').val(data.pagos[0].idestado);
					$('#txtAddPayDepartamento').val(data.pagos[0].departamento);
					$('#txtAddPayConcepto').val(data.pagos[0].descripcion_concepto+" "+data.pagos[0].nombremes+" "+data.pagos[0].anio.toString());
					$('#txtAddPayTotal').val(data.pagos[0].total);
					$('#evidencia').val('');
            		$('#evidencia').next('.custom-file-label').text('Seleccionar imagen');
					$('#AddPagoModal').modal('show');
				});
			}else{
				swal("Error!", 'El estado no corresponde, verificar', "error")
			}
			
		});

		$('#tblPago').on('click', '.verificaPago', function() {
			var id = $(this).data('id');
			var idestado = $(this).data('idestado');
			if(idestado===2){
				$.get('/pagos/get/' + id, function(data) {
					if (data.pagos && data.pagos.length > 0) {
						const pagodata = data.pagos[0]; // Asumiendo que solo hay un pago devuelto
						$('#pagoIdEvidencia').val(pagodata.id);
						$('#estadoIdEvidencia').val(pagodata.idestado);
						$('#txtConfEvidenciaDepartamento').text(pagodata.departamento);
						$('#txtConfEvidenciaConcepto').text(pagodata.descripcion_concepto+" "+pagodata.nombremes+" "+pagodata.anio.toString());
						$('#txtConfEvidenciaTotal').text(pagodata.total);
						if (pagodata.evidencia_url) {
							// Actualiza el atributo src del elemento img con la URL de la imagen recibida
							$('#evidenciaImg').attr('src', pagodata.evidencia_url);
						} else {
							console.error('No se encontró la evidencia');
						}
					} else {
						console.error('No se encontraron pagos');
					}
					
					//$('#evidenciaImg').attr('src', data.pagos[0].evidencia_url);
					$('#ConfirmaEvidenciaModal').modal('show');
				});
			}else{
				swal("Error!", 'El estado no corresponde, verificar', "error")
			}
			
		});

		$('#evidencia').on('change', function() {
			var file = this.files[0];
			var fileType = file.type;
			var match = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
			if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))) {
				swal("Error!", 'Seleccione una imagen válida (JPEG/JPG/PNG/GIF).', "error");
				$('#evidencia').val('');
            	$('#evidencia').next('.custom-file-label').text('Seleccionar imagen');
			}else{
				var fileName = file.name;
                $(this).next('.custom-file-label').text(fileName);
			}
		});

		$('#pagoForm').submit(function(e) {
			e.preventDefault();
			$(this).find('button[type="submit"]').prop('disabled', true);
			var formData = new FormData(this);
			$.ajax({
				url: '{{ route('guardar.evidencia') }}',
				method: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					if (response.success) {
						limpiarFormEvidencia();
						swal("Registro Correcto!", response.success, "success")
						$('#tblPago').DataTable().ajax.reload(null, false);
						
					} else {
						swal("Error!", 'Ocurrió un error', "error")
						console.log('Ocurrió un error');
					}
					$('#pagoForm').find('button[type="submit"]').prop('disabled', false);
				},
				error: function(response) {
					swal("Error!", 'Ocurrió un error', "error")
					console.log('Ocurrió un error');
				}
			});
		});

		$('#confirmaPagoForm').submit(function(e) {
			e.preventDefault();
			$(this).find('button[type="submit"]').prop('disabled', true);
			$.ajax({
				url: '{{ route('confirmar.evidencia') }}',
				method: 'POST',
                data: $(this).serialize(),
				success: function(response) {
					if (response.success) {
						$('#ConfirmaEvidenciaModal').modal('hide');
						swal("Registro Correcto!", response.success, "success")
						$('#tblPago').DataTable().ajax.reload(null, false);
						
					} else {
						swal("Error!", 'Ocurrió un error', "error")
						console.log('Ocurrió un error');
					}
					$('#confirmaPagoForm').find('button[type="submit"]').prop('disabled', false);
				},
				error: function(response) {
					swal("Error!", 'Ocurrió un error', "error")
					console.log('Ocurrió un error');
					$('#confirmaPagoForm').find('button[type="submit"]').prop('disabled', false);
				}
			});
		});

		function limpiarFormEvidencia(){
			$('#pagoForm')[0].reset();
        	$('#lblImagen').text('Seleccionar imagen');
			$('#AddPagoModal').modal('hide');
		}

		$('#tblPago').on('click', '.verPdfPago', function() {
			var pagoId = $(this).data('id');
			var idestado = $(this).data('idestado');
			var url = '{{ route("pagos.pdf", ":id") }}';
			url = url.replace(':id', pagoId);

			$('#pdfIframe').attr('src', url);
			$('#pdfModal').modal('show');
			
		});
	});
</script>