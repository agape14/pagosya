@extends('layout.default')

@section('content')
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Ingresos</a></li>
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
                            Listado Ingresos
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#AddIngresoModal" class="btn btn-primary" id="btnAddIngreso"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Ingreso</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="tblIngresos_wrapper" class="dataTables_wrapper no-footer">
                                <table id="tblIngresos" class="display min-w850 dataTable no-footer" role="grid"
                                    aria-describedby="tblIngresos_info">
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

                        <div class="modal fade" id="AddIngresoModal" tabindex="-1" aria-labelledby="AddIngresoModalLabel-1" aria-hidden="true">
                            <div class="modal-dialog  modal-lg">
                            <div class="modal-content">
                                <form id="ingresoForm"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="ingresoId" id="ingresoId">
                                    <div class="modal-header">
                                    <h4 class="modal-title" id="AddIngresoModalLabel-1">Registrar Ingreso</h4>
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
                                        {{--<div class="profile-blog mb-5 d-none" id="fileInputDiv">
                                            <h5 class="text-primary d-inline">Cotizacion de Pago</h5><a href="javascript:void()" class="pull-right f-s-16"> </a>
                                            <img id="evidenciaImg" src="" alt="" class="img-fluid mt-4 mb-4 w-100">
                                        </div>--}}

                                        <div class="profile-blog mb-5 d-none" id="fileInputDiv">
                                            <h5 class="text-primary d-inline">Cotización de Ingreso</h5>
                                            <a href="javascript:void()" class="pull-right f-s-16"> </a>
                                            <embed id="evidenciaPdf" src="" type="application/pdf" class="embed-responsive-item mt-4 mb-4 w-100" style="height:500px;">
                                        </div>

                                        <div class="input-group mb-3"  >
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="evidencia" id="evidencia" accept=".pdf" required>
                                                <label class="custom-file-label" for="evidencia" id="lblImagen">Seleccionar archivo</label>
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

        var tblIngresos =$('#tblIngresos').DataTable({
            processing: true,
            serverSide: true,
            //ajax: '{!! route('ingresos.data') !!}',
            language: {url: '/datatables/spanish.json'},
            ajax: {
				url: '{{ route('ingresos.data') }}',
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

        $('#ingresoForm').submit(function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').prop('disabled', true);
            // Obtener los datos del formulario
            var formData = new FormData(this);
            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: '{{ route('ingresos.evidencia') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    limpiarFormIngresos();
                    swal("Registro Correcto!", response.success, "success")
						$('#tblIngresos').DataTable().ajax.reload(null, false);
                    console.log(response);
                    // Actualizar la tabla u otra acción necesaria
                    $('#ingresoForm').find('button[type="submit"]').prop('disabled', false);
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
                    $('#ingresoForm').find('button[type="submit"]').prop('disabled', false);
                }
            });
        });

        function limpiarFormIngresos(){
			$('#ingresoForm')[0].reset();
        	$('#lblImagen').text('Seleccionar archivo');
			$('#AddIngresoModal').modal('hide');
		}

        $('#tblIngresos').on('click', '.editBtn', function() {
            var id = $(this).data('id');
            $.get('/ingresos/get/' + id, function(data) {
                const Ingresosdata = data.ingresos[0];
                $('#ingresoId').val(Ingresosdata.id);
                $('#cbxConceptoAdd').val(Ingresosdata.id_concepto).trigger('change');
                $('#txtMontoAdd').val(Ingresosdata.total);
                $('#txtDescripcion').val(Ingresosdata.descripcion);

                if (Ingresosdata.evidencia_url) {
                    // Actualiza el atributo src del elemento img con la URL de la imagen recibida
                    //$('#evidenciaImg').attr('src', Ingresosdata.evidencia_url);
                    $('#evidenciaPdf').attr('src', Ingresosdata.evidencia_url);
                } else {
                    console.error('No se encontró el archivo');
                }
                $("#evidencia").removeAttr("required");
                $("#fileInputDiv").removeClass("d-none");
                $('#AddIngresoModalLabel-1').text('Editar Ingreso');
                $('#ingresoForm').attr('action', '{{ route('ingresos.evidencia') }}').attr('method', 'POST');
                $('#ingresoForm input[name="_method"]').remove();

                $('#AddIngresoModal').modal('show');
            });
        });

        $('#AddIngresoModal').on('hidden.bs.modal', function () {
            $('#ingresoId').val("");
            $('#ingresoForm')[0].reset();
        	$('#lblImagen').text('Seleccionar archivo');
            $("#evidencia").attr("required", "required");
            $("#fileInputDiv").addClass("d-none");
            $('#AddIngresoModalLabel-1').text('Registrar Ingreso');
            $('#ingresoForm').attr('action', '{{ route('ingresos.evidencia') }}').attr('method', 'POST');
            $('#ingresoForm input[name="_method"]').remove();
        });

        $('#tblIngresos').on('click', '.deleteBtn', function() {
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
                        url: '/ingresos/delete/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            swal("Eliminado!", response.success, "success");
                            $('#tblIngresos').DataTable().ajax.reload(null, false);
                        }
                    });

                }else{
                    swal("Cancelado!", "Se cancelo la accion", "error")
                }
            })
        });
        $('#cbxConcepto, #txtFecha').change(function() {
			tblIngresos.draw();
		});

        $('#evidencia').on('change', function() {
            var file = this.files[0];
            var fileType = file.type;
            var match = ['application/pdf'];
            if(fileType != match[0]) {
                swal("Error!", 'Seleccione un archivo PDF válido.', "error");
                $('#evidencia').val(''); // Limpiar el valor del input file
                $('#evidencia').next('.custom-file-label').text('Seleccionar archivo');
            } else {
                var fileName = file.name;
                $(this).next('.custom-file-label').text(fileName);
            }
        });

	});
</script>
