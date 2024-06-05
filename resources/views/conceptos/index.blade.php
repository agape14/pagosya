{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Conceptos</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Conceptos</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#AddConceptoModal" class="btn btn-primary ml-auto">+ Nuevo Concepto</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblConceptos" class="table table-striped table-condensed flip-content">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo Concepto</th>
                                    <th>Descripción</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Apto <br>Pago</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="AddConceptoModal" tabindex="-1" aria-labelledby="AddConceptoModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('addconcepto') }}"  id="conceptoForm">
                                @csrf
                                <input type="hidden" name="id" id="conceptoId">
                                <div class="modal-header">
                                <h4 class="modal-title" id="AddConceptoModalLabel-1">Agregar Nuevo Concepto</h4>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="id_tipo_concepto">Tipo Concepto</label>
                                        <select class="form-control" name="id_tipo_concepto" id="idTipoConcepto" required>
                                            <option value="">Seleccione un Tipo Concepto</option>
                                            @foreach ($tipos_concepto as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->tipo_concepto }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion_concepto">Descripción</label>
                                        <input type="text" class="form-control" name="descripcion_concepto" id="nombreConcepto" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mes">Mes</label>
                                        <select class="form-control" id="cbxMes" name="mes" >
                                            <option value="0">Seleccione un mes</option>
                                            @foreach ($meses as $mes)
                                                <option value="{{ $mes->mes }}">{{ $mes->nombremes }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="anio">Año</label>
                                        <select class="form-control" id="cbxAnio" name="anio" >
                                            <option value="0">Seleccione un año</option>
                                            @foreach ($anios as $anio)
                                                <option value="{{ $anio->anio }}">{{ $anio->anio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" id="divAptoPago">
                                        <label for="activo">Apto Pago</label>
                                        <select class="form-control" id="cbxActivo" name="activo" >
                                            <option value="">Seleccione...</option>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                    <!--<label class="form-label d-block mb-2">Nombre Concepto</label>
                                    <input type="text" class="form-control w-100 mb-3" placeholder="Nombre Concepto" name="descripcion_concepto" id="nombreConcepto">-->
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
            $('#divAptoPago').hide();
            $('#tblConceptos').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('conceptos_get') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nombre_concepto', name: 'nombre_concepto' },
                    { data: 'descripcion_concepto', name: 'descripcion_concepto' },
                    { data: 'nombre_mes', name: 'nombre_mes' },
                    { data: 'anio', name: 'anio' },
                    { data: 'activo', name: 'activo' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#tblConceptos').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('/conceptos/get/' + id, function(data) {
                    $('#conceptoId').val(data.id);
                    $('#idTipoConcepto').val(data.id_tipo_concepto).trigger('change');
                    $('#nombreConcepto').val(data.descripcion_concepto);
                    $('#cbxMes').val(data.mes).trigger('change');
                    $('#cbxAnio').val(data.anio).trigger('change');
                    $('#cbxActivo').val(data.activo).trigger('change');
                    $('#divAptoPago').show();
                    $('#AddConceptoModalLabel-1').text('Editar Concepto');
                    $('#conceptoForm').attr('action', '/editconcepto/' + id).attr('method', 'POST');
                    $('#conceptoForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#AddConceptoModal').modal('show');
                });
            });

            $('#AddConceptoModal').on('hidden.bs.modal', function () {
                $('#conceptoId').val('');
                $('#idTipoConcepto').val('').trigger('change');
                $('#nombreConcepto').val('');
                // Set current month and year
                var currentDate = new Date();
                $('#cbxMes').val(currentDate.getMonth() + 1).trigger('change'); // Obtener el mes actual (0-11) y sumarle 1
                $('#cbxAnio').val(currentDate.getFullYear()).trigger('change'); // Obtener el año actual
                $('#divAptoPago').hide();
                $('#AddConceptoModalLabel-1').text('Agregar Nuevo Concepto');
                $('#conceptoForm').attr('action', '{{ route('addconcepto') }}').attr('method', 'POST');
                $('#conceptoForm input[name="_method"]').remove();
            });

            $('#tblConceptos').on('click', '.deleteBtn', function() {
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
                            url: '/conceptos/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#tblConceptos').DataTable().ajax.reload();
                            }
                        });
                        
                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });

            $('#conceptoForm').on('submit', function(e) {
                e.preventDefault();
                var formAction = $(this).attr('action');
                var formMethod = $(this).attr('method');
                var formData = $(this).serialize();

                $.ajax({
                    url: formAction,
                    type: formMethod,
                    data: formData,
                    success: function(response) { console.log('mensaje',response);
                        $('#AddConceptoModal').modal('hide');
                        swal("Registro Correcto!", response.success, "success")
                        $('#tblConceptos').DataTable().ajax.reload(null, false);
                    },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        swal("Error!", errorMessage, "error");
                        console.error(errors);
                    }
                });
            });

            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
