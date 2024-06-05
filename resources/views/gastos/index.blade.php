@extends('layout.default')

@section('content')
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Gastos</a></li>
            </ol>
        </div>
        <!-- row -->
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
                                    <div class="col-xl-2 col-xxl-6 col-sm-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="txtFecha">Fecha</label>
                                            <input class="datepicker-default form-control" id="txtFecha" name="txtFecha">
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-2 col-xxl-6 col-sm-6 mb-3">
                                        <button class="btn btn-primary mt-4 disabled" title="Click para buscar" type="button"><i class="fa fa-filter" aria-hidden="true"></i> Buscar</button>
                                        <button class="btn btn-danger mt-4 light ms-1" title="Click para limpiar el filtro" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header  p-2">
                        <div class="card-title">
                            Listado Gastos
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#AddGastoModal" class="btn btn-primary" id="btnAddGasto"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Gasto</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="tblGastos_wrapper" class="dataTables_wrapper no-footer">
                                <table id="tblGastos" class="display min-w850 dataTable no-footer" role="grid"
                                    aria-describedby="tblGastos_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Total</th>
                                            <th>Creado Por</th>
                                            <th>Detalle</th>
                                            <th>Acciones</th> <!-- Nueva columna para acciones -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal fade" id="AddGastoModal" tabindex="-1" aria-labelledby="AddGastoModalLabel-1" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="gastoForm"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="gastoId" id="gastoId">
                                    <div class="modal-header">
                                    <h4 class="modal-title" id="AddGastoModalLabel-1">Registrar Gasto</h4>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="cbxConceptoAdd">Concepto:</label>
                                            <select class="form-control" id="cbxConceptoAdd" name="cbxConceptoAdd" required>
                                                <option value="">Seleccione un Concepto</option>
                                                @foreach ($conceptos as $concepto)
                                                    <option value="{{ $concepto->id }}">
                                                        {{ $concepto->descripcion_concepto . " " . ($concepto->nombreMes ? $concepto->nombreMes->nombremes : '') . " " . ($concepto->anio?$concepto->anio : '') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtMontoAdd">Monto:</label>
                                            <input type="text" class="form-control" id="txtMontoAdd" name="txtMontoAdd" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtDescripcion">Descripcion:</label>
                                            <textarea class="form-control" rows="4" id="txtDescripcion" name="txtDescripcion" required></textarea>
                                        </div>
                                        <div class="profile-blog mb-5 d-none" id="fileInputDiv">
                                            <h5 class="text-primary d-inline">Imagen Evidencia de Pago</h5><a href="javascript:void()" class="pull-right f-s-16"> </a>
                                            <img id="evidenciaImg" src="" alt="" class="img-fluid mt-4 mb-4 w-100">
                                        </div>
                                        <div class="input-group mb-3"  >
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="evidencia" id="evidencia" accept="image/*" required>
                                                <label class="custom-file-label" for="evidencia" id="lblImagen">Seleccionar imagen</label>
                                            </div>
                                            <div class="invalid-feedback d-block" id="evidenciaError"></div>
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
@endsection

<script type="module">
	$(document).ready(function() {
		$("#cbxConcepto").select2();
		$("#cbxPropietario").select2();
		
        var tblGastos =$('#tblGastos').DataTable({
            processing: true,
            serverSide: true,
            //ajax: '{!! route('gastos.data') !!}',
            language: {url: '/datatables/spanish.json'},
            ajax: {
				url: '{{ route('gastos.data') }}',
					data: function(d) {
						d.concepto = $('#cbxConcepto').val();
						d.fecha = $('#txtFecha').val();
					}
			},
            columns: [
                { data: 'fecha', name: 'fecha' },
                { data: 'concepto', name: 'concepto' },
                { data: 'total', name: 'total' },
                { data: 'creado_por', name: 'creado_por' },
                { data: 'detalle', name: 'detalle', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false } // Nueva columna para acciones
            ]
        });

        $('#gastoForm').submit(function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').prop('disabled', true);
            // Obtener los datos del formulario
            var formData = new FormData(this);

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: '{{ route('gastos.evidencia') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    limpiarFormGastos();
                    swal("Registro Correcto!", response.success, "success")
						$('#tblGastos').DataTable().ajax.reload(null, false);
                    console.log(response);
                    // Actualizar la tabla u otra acción necesaria
                    $('#gastoForm').find('button[type="submit"]').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    // Manejar errores
                    console.error(xhr.responseText);
                    // Limpiar errores anteriores
                    $('.error').text('');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;

                        // Mostrar errores en los campos correspondientes
                        if (errors.evidencia) {
                            $('#evidenciaError').text(errors.evidencia[0]);
                        }
                    } else {
                        console.log('Error desconocido.');
                    }
                    $('#gastoForm').find('button[type="submit"]').prop('disabled', false);
                }
            });
        });

        function limpiarFormGastos(){
			$('#gastoForm')[0].reset();
        	$('#lblImagen').text('Seleccionar imagen');
			$('#AddGastoModal').modal('hide');
		}

        $('#tblGastos').on('click', '.editBtn', function() {
            var id = $(this).data('id');
            $.get('/gastos/get/' + id, function(data) {
                const gastosdata = data.gastos[0]; 
                $('#gastoId').val(gastosdata.id);
                $('#cbxConceptoAdd').val(gastosdata.id_concepto).trigger('change');
                $('#txtMontoAdd').val(gastosdata.total);
                $('#txtDescripcion').val(gastosdata.descripcion);

                if (gastosdata.evidencia_url) {
                    // Actualiza el atributo src del elemento img con la URL de la imagen recibida
                    $('#evidenciaImg').attr('src', gastosdata.evidencia_url);
                } else {
                    console.error('No se encontró la evidencia');
                }
                $("#evidencia").removeAttr("required");
                $("#fileInputDiv").removeClass("d-none");
                $('#AddGastoModalLabel-1').text('Editar Gasto');
                $('#gastoForm').attr('action', '{{ route('gastos.evidencia') }}').attr('method', 'POST');
                $('#gastoForm input[name="_method"]').remove();

                $('#AddGastoModal').modal('show');
            });
        });

        $('#AddGastoModal').on('hidden.bs.modal', function () {
            $('#gastoId').val("");
            $('#gastoForm')[0].reset();
        	$('#lblImagen').text('Seleccionar imagen');
            $("#evidencia").attr("required", "required");
            $("#fileInputDiv").addClass("d-none");
            $('#AddGastoModalLabel-1').text('Registrar Gasto');
            $('#gastoForm').attr('action', '{{ route('gastos.evidencia') }}').attr('method', 'POST');
            $('#gastoForm input[name="_method"]').remove();
        });

        $('#tblGastos').on('click', '.deleteBtn', function() {
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
                        url: '/gastos/delete/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            swal("Eliminado!", response.success, "success");
                            $('#tblGastos').DataTable().ajax.reload(null, false);
                        }
                    });
                    
                }else{
                    swal("Cancelado!", "Se cancelo la accion", "error")
                }
            })
        });
        $('#cbxConcepto, #txtFecha').change(function() {
			tblGastos.draw();
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
	});
</script>