{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
	<div class="page-titles">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">Pago</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Programacion</a></li>
		</ol>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="row">
				<div class="col-xl-4">
					<div class="card">
						<div class="card-header primary">
							<div class="card-title">
								Agregar Programacion
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="card-body">
                            <form id="frmRegistraProgramacion"  class="form-valide-with-icon">
                                @csrf
                                <input type="hidden" id="id_programacion" name="id_programacion">
							    <div class="basic-form">
                                    <div class="d-flex justify-content-between" style="background: rgba(100, 24, 195, 0.1);padding: 1rem;margin: 1.875rem 0 1.25rem 0;">
                                        <div class="form-check">
                                            <input id="chkGrupal" name="chkGrupal" class="form-check-input mb-0" type="checkbox" checked>
                                            <label for="chkGrupal" class="form-check-label text-primary">GRUPAL</label>
                                            <br>
                                            <small class="text-primary">La opcion GRUPAL le permite programar el pago para toda la torre.</small>
                                        </div>
                                    </div>
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
                                    <div class="mb-3">
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
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="txtMonto" class="form-label">Monto</label>
                                            <input type="text" class="form-control" id="txtMonto" name="txtMonto" required>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" id="btnGuardarProgramacion" class="btn btn-primary">Guardar</button>
                                        <button type="button" id="btnCancelarProgramacion" class="btn light btn-danger">Cancelar</button>
                                    </div>
							    </div>
                            </form>
						</div>
					</div>
				</div>
				<div class="col-xl-8">
					<div class="card">
						<div class="card-header">
							<div class="card-title">
								Listado de Programados
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="card-body">
							<div class="basic-form">
                                <div class="row">
                                    <div class="col-xl-6 mb-3">
                                        <div class="example">
                                            <p class="mb-1">Concepto</p>
                                            <select id="cbxConceptoBusqueda">
                                                <option value="">Seleccione un Concepto</option>
                                                @foreach ($conceptos as $concepto)
                                                    <option value="{{ $concepto->id }}">
                                                        {{ $concepto->descripcion_concepto . " " . ($concepto->nombreMes ? $concepto->nombreMes->nombremes : '') . " " . ($concepto->anio?$concepto->anio : '') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <div class="example">
                                            <p class="mb-1">Propietario</p>
                                            <select id="cbxPropietarioBusqueda">
                                                <option value="">Seleccione un Propietario</option>
                                                @foreach ($propietarios as $propietario)
                                                    <option value="{{ $propietario->id }}">{{ $propietario->departamento }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
								<div class="table-responsive">
									<table class="table table-responsive-md mt-2" id="programacionTable">
                                        <thead>
                                            <tr>
                                                <!--<th class="width50">
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="checkAll" required="">
														<label class="custom-control-label" for="checkAll"></label>
													</div>
												</th>-->
                                                <th>Departamento</th>
                                                <th>Concepto</th>
                                                <th>Total</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Acciones</th> <!-- Añadir la columna de acciones aquí -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--<tr>
                                                <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
														<label class="custom-control-label" for="customCheckBox2"></label>
													</div>
												</td>
                                                <td><strong>542</strong></td>
                                                <td><div class="d-flex align-items-center"><img src="{{ asset('images/avatar/1.jpg') }}" class="rounded-lg mr-2" width="24" alt=""/> <span class="w-space-no">Dr. Jackson</span></div></td>
                                                <td>example@example.com	</td>
                                                <td>01 August 2020</td>
                                                <td><span class="badge light badge-success">Successful</span></td>
                                                <td>
													<div class="dropdown">
														<button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
															<svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="#">Edit</a>
															<a class="dropdown-item" href="#">Delete</a>
														</div>
													</div>
												</td>
                                            </tr>
											<tr>
                                                <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox3" required="">
														<label class="custom-control-label" for="customCheckBox3"></label>
													</div>
												</td>
                                                <td><strong>542</strong></td>
                                                <td><div class="d-flex align-items-center"><img src="{{ asset('images/avatar/2.jpg') }}" class="rounded-lg mr-2" width="24" alt=""/> <span class="w-space-no">Dr. Jackson</span></div></td>
                                                <td>example@example.com	</td>
                                                <td>01 August 2020</td>
                                                <td><span class="badge light badge-danger">Canceled</span></td>
                                                <td>
													<div class="dropdown">
														<button type="button" class="btn btn-danger light sharp" data-toggle="dropdown">
															<svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="#">Edit</a>
															<a class="dropdown-item" href="#">Delete</a>
														</div>
													</div>
												</td>
                                            </tr>
											<tr>
                                                <td>
													<div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
														<input type="checkbox" class="custom-control-input" id="customCheckBox4" required="">
														<label class="custom-control-label" for="customCheckBox4"></label>
													</div>
												</td>
                                                <td><strong>542</strong></td>
                                                <td><div class="d-flex align-items-center"><img src="{{ asset('images/avatar/3.jpg') }}" class="rounded-lg mr-2" width="24" alt=""/> <span class="w-space-no">Dr. Jackson</span></div></td>
                                                <td>example@example.com	</td>
                                                <td>01 August 2020</td>
                                                <td><span class="badge light badge-warning">Pending</span></td>
                                                <td>
													<div class="dropdown">
														<button type="button" class="btn btn-warning light sharp" data-toggle="dropdown">
															<svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="#">Edit</a>
															<a class="dropdown-item" href="#">Delete</a>
														</div>
													</div>
												</td>
                                            </tr>-->
                                        </tbody>
                                    </table>
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
            $("#cbxConceptoBusqueda").select2();
            $("#cbxPropietarioBusqueda").select2();
            var tableProgramacion =$('#programacionTable').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: {
                    url: '{{ route('programacion.data') }}',
                        data: function(d) {
                            d.concepto = $('#cbxConceptoBusqueda').val();
                            d.propietario = $('#cbxPropietarioBusqueda').val();
                        }
                },
                columns: [
                    { data: 'propietario', name: 'propietario' },
                    { data: 'concepto', name: 'concepto' },
                    { data: 'total', name: 'total' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'estado', name: 'estado', orderable: false, searchable: false },
                    { data: 'acciones', name: 'acciones', orderable: false, searchable: false } // Añadir la columna de acciones aquí
                ]
            });
            
            // Recargar la tabla cuando se cambien los filtros
            $('#cbxConceptoBusqueda, #cbxPropietarioBusqueda').change(function() {
                tableProgramacion.draw();
            });

            function limpiarFormProgramacion(){
                $('#id_programacion').val('');
                
                $('#cbxConcepto').val('').trigger('change');
                $('#cbxPropietario').val('0').trigger('change');
                $('#cbxPropietario').prop('disabled', true).css('background-color', '#e9ecef');
                $('#txtMonto').val('')
                $('#chkGrupal').prop('disabled', false); 
                $('#chkGrupal').prop('checked', true); 
            }
            $('#chkGrupal').change(function() {
                if($(this).is(':checked')) {
                    $('#cbxPropietario').val('0').trigger('change');
                    $('#cbxPropietario').prop('disabled', true).css('background-color', '#e9ecef');
                } else {
                    $('#cbxPropietario').prop('disabled', false).css('background-color', '');
                }
            });

            // Asegurarse de que el select esté desactivado al cargar la página si el checkbox está marcado
            if($('#chkGrupal').is(':checked')) {
                $('#cbxPropietario').val('0').trigger('change');
                $('#cbxPropietario').prop('disabled', true).css('background-color', '#e9ecef');
            }
            
            $('#btnGuardarProgramacion').on('click', function(e) {
                e.preventDefault();
                let isValid = true;

                // Validar el campo de concepto
                if ($('#cbxConcepto').val() === null || $('#cbxConcepto').val() === '') {
                    isValid = false;
                    swal("Error!", 'Debe seleccionar un concepto.', "error"); return;
                }
                // Validar el campo de propietario si el checkbox no está marcado
                if (!$('#chkGrupal').is(':checked') && $('#cbxPropietario').val() === '0') {
                    isValid = false;
                    swal("Error!", 'Debe seleccionar un propietario.', "error");return;
                }

                 // Validar el campo de monto
                 if ($('#txtMonto').val().trim() === '') {
                    isValid = false;
                    swal("Error!", 'Debe ingresar un monto.', "error");return;
                }

                // Si no es válido, prevenir el envío del formulario
                if (isValid) {
                    $('#btnGuardarProgramacion').prop('disabled', true);
                    $.ajax({
                        url: '{{ route('programacion.store') }}',
                        method: 'POST',
                        data: $('#frmRegistraProgramacion').serialize(),
                        success: function(response) {
                            if (response.success) {
                                limpiarFormProgramacion();
                                swal("Registro Correcto!", response.success, "success");
                                $('#btnGuardarProgramacion').prop('disabled', false);
                                $('#programacionTable').DataTable().ajax.reload();
                            } else {
                                swal("Error!", 'Ocurrió un error', "error");
                                $('#btnGuardarProgramacion').prop('disabled', false);
                            }
                        },
                        error: function(response) {
                            swal("Error!", 'Ocurrió un error', "error");
                            console.log('Ocurrió un error');
                            $('#btnGuardarProgramacion').prop('disabled', false);
                        }
                    });
                } else {
                    $('#btnGuardarProgramacion').prop('disabled', false);
                }
            });
            $('#btnCancelarProgramacion').on('click', function(e) {
                e.preventDefault();
                limpiarFormProgramacion();
            });
            
            $(document).on('click', '.btnEditarProgramacion[data-id]', function() {
                var id = $(this).data('id');
                $.get('/programacion/get/' + id, function(data) {
                    $('#id_programacion').val(data.programaciones.id);
                    $('#chkGrupal').prop('disabled', true); 
                    $('#chkGrupal').prop('checked', false); 
                    $('#cbxConcepto').val(data.programacionesdet.id_concepto).trigger('change');
                    $('#cbxPropietario').val(data.programaciones.id_propietario).trigger('change');
                    $('#cbxPropietario').prop('disabled', false).css('background-color', '');
                    $('#txtMonto').val(data.programacionesdet.monto);
                });
            });

            $(document).on('click', '.btnEliminarProgramacion[data-id]', function() {
                var id = $(this).data('id');
                swal({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#6418C3",
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: !1,
                    closeOnCancel: !1
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/programacion/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#programacionTable').DataTable().ajax.reload();
                            },
                            error: function(response) {
                                console.log(response);
                                swal("Error!", "Ocurrio un error, contactese con el administrador del sistema.", "error")
                            }
                        });
                        
                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });
        });
    </script>