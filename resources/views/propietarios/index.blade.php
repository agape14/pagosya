{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Propietarios</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Propietarios</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                    </div>
                    <div class="table-responsive">
                        <table id="tblPropietarios" class="table table-striped table-condensed flip-content">
                             <thead>
                                <tr>
                                    <th>Departamento</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo Electrónico</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="AddPropietarioModal" tabindex="-1" aria-labelledby="AddPropietarioModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" id="propietarioForm">
                                @csrf
                                <input type="hidden" name="id" id="propietarioId">
                                <div class="modal-header">
                                <h4 class="modal-title" id="AddPropietarioModalLabel-1">Actualizar Propietario</h4>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="departamento">Departamento:</label>
                                        <input type="number" class="form-control" id="departamento" name="departamento" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombrePropietario" name="nombre" maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control"  id="apellido" name="apellido" maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <label for="correo_electronico">Correo Electrónico:</label>
                                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="pais">País:</label>
                                                <select name="pais" id="pais" class="form-control">
                                                    @foreach ($paises as $pais)
                                                        <option value="{{ $pais->id }}"  data-longitud="{{ $pais->longitud_telefono }}">
                                                            {{ $pais->nombre_pais }} ({{ $pais->codigo_telefono }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telefono">Teléfono:</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="12" title="Debe contener exactamente 9 dígitos numéricos">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="dni">DNI:</label>
                                                <input type="text" class="form-control" id="dni" name="dni" maxlength="8">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telefono">Usuario:</label>
                                                <input type="text" class="form-control" id="usuario" name="usuario"  disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="9" pattern="\d{9}" title="Debe contener exactamente 9 dígitos numéricos">
                                    </div>-->

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>

                        </div>
                        </div>
                    </div>

                    <!-- Modal Crear Sub Propietario-->
                    <div class="modal fade" id="addSubPropModal" tabindex="-1" role="dialog" aria-labelledby="addSubPropModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSubPropModalLabel">Agregar Sub Propietario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario para agregar sub propietario -->
                                    <form id="subPropForm">
                                        @csrf
                                        <input type="hidden" id="subpropietario_id" name="id">
                                        <input type="hidden" id="propietario_id" name="propietario_id">
                                        <!-- Primera línea: Departamento -->
                                        <div class="d-inline">
                                            <h5 class="d-inline">Departamento: </h5>
                                            <span id="txtDeptoProp" class="d-inline"></span>
                                        </div>
                                        <br>
                                        <!-- Segunda línea: Nombres Completos -->
                                        <div class="d-inline">
                                            <h5 class="d-inline">Nombres Completos: </h5>
                                            <span id="txtNombresProp" class="d-inline"></span>
                                        </div>
                                        <hr>
                                        <div id="accordion-one" class="accordion accordion-primary">
                                            <div class="accordion__item">
                                                <div class="accordion__header collapsed rounded-lg" data-toggle="collapse" data-target="#default_collapseTwo">
                                                    <span class="accordion__header--text">Nuevo Sub propietario</span>
                                                    <span class="accordion__header--indicator"></span>
                                                </div>
                                                <div id="default_collapseTwo" class="collapse accordion__body" data-parent="#accordion-one">
                                                    <div class="accordion__body--text">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" id="subpropnombre" name="nombre" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="apellido">Apellido</label>
                                                            <input type="text" class="form-control" id="subpropapellido" name="apellido" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo_electronico">Correo Electrónico</label>
                                                            <input type="email" class="form-control" id="subpropcorreo_electronico" name="correo_electronico" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="paissub">País:</label>
                                                                    <select name="paissub" id="paissub" class="form-control">
                                                                        @foreach ($paises as $pais)
                                                                            <option value="{{ $pais->id }}"  data-longitud="{{ $pais->longitud_telefono }}">
                                                                                {{ $pais->nombre_pais }} ({{ $pais->codigo_telefono }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="telefono">Teléfono:</label>
                                                                    <input type="text" class="form-control" id="subproptelefono" name="telefono" maxlength="12" title="Debe contener exactamente # dígitos numéricos">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-group">
                                                            <label for="telefono">Teléfono</label>
                                                            <input type="text" class="form-control" id="subproptelefono" name="telefono" maxlength="9" pattern="\d{9}" title="Debe contener exactamente 9 dígitos numéricos" required>
                                                        </div>--}}
                                                        <div class="form-group">
                                                            <label for="tipo_sub_propietario">Tipo Sub Propietario</label>
                                                            <select class="form-control" name="tipo_sub_propietario" id="tipo_sub_propietario" required>
                                                                <option value="">Seleccione un Tipo Sub Propietario</option>
                                                                @foreach ($tipos_subprop as $tiposp)
                                                                    <option value="{{ $tiposp->id }}">{{ $tiposp->tipo }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="button" class="btn btn-danger light btnCancelarSubPropietario" >Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    <!-- Tabla de sub propietarios -->
                                    <div class="table-responsive">
                                        <table  id="tblSubPropietarios" class="table mt-3 table-bordered table-responsive-md">
                                            <thead>
                                                <tr>
                                                    <th>Nombres</th>
                                                    <th>Apellido</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Teléfono</th>
                                                    <th>Tipo</th>
                                                    <th>Accion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Ver Sub Propietario-->
                    <div class="modal fade" id="verSubPropModal" tabindex="-1" role="dialog" aria-labelledby="verSubPropModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="verSubPropModalLabel">Ver Sub Propietario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                     <!-- Primera línea: Departamento -->
                                     <div class="d-inline">
                                        <h5 class="d-inline">Departamento: </h5>
                                        <span id="txtVerDeptoProp" class="d-inline"></span>
                                    </div>
                                    <br>
                                    <!-- Segunda línea: Nombres Completos -->
                                    <div class="d-inline">
                                        <h5 class="d-inline">Nombres Completos: </h5>
                                        <span id="txtVerNombresProp" class="d-inline"></span>
                                    </div>
                                    <hr>
                                    <!-- Tabla de sub propietarios -->
                                    <div class="table-responsive">
                                        <table  id="tblVerSubPropietarios" class="table mt-3 table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Apellido</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Teléfono</th>
                                                    <th>Tipo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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

</div>
@endsection
    <script type="module">
        $(document).ready(function() {

            $('#tblPropietarios').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('propietarios_get') }}',
                columns: [
                    { data: 'departamento', name: 'departamento' },
                    { data: 'nombre', name: 'nombre' },
                    { data: 'apellido', name: 'apellido' },
                    { data: 'correo_electronico', name: 'correo_electronico' },
                    { data: 'telefono', name: 'telefono' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#tblPropietarios').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('/propietarios/get/' + id, function(data) {
                    $('#propietarioId').val(data.id);
                    $('#departamento').val(data.departamento);
                    $('#nombrePropietario').val(data.nombre);
                    $('#apellido').val(data.apellido);
                    $('#correo_electronico').val(data.correo_electronico);
                    $('#telefono').val(data.telefono);
                    $('#dni').val(data.dni);
                    if (data.usuario) {
                        $('#usuario').val(data.usuario.usuario); // Si hay usuario, llenar el campo
                    } else {
                        $('#usuario').val(''); // Si no hay usuario, dejar el campo vacío
                    }
                    $('#AddPropietarioModalLabel-1').text('Editar Propietario');
                    $('#propietarioForm').attr('action', '/editpropietario/' + id).attr('method', 'POST');
                    $('#propietarioForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#AddPropietarioModal').modal('show');
                }).fail(function() {
                    swal("Error!", 'Error al obtener los datos del propietario.', "error")
                });
            });

            $('#tblPropietarios').on('click', '.addsubpropBtn', function() {
                var idpropietario = $(this).data('id');
                $('#propietario_id').val(idpropietario);

                // Destruir DataTable existente si existe para evitar conflictos
                if ($.fn.DataTable.isDataTable('#tblSubPropietarios')) {
                    $('#tblSubPropietarios').DataTable().destroy();
                }
                // Obtener datos del propietario y sus sub propietarios


                $.get('/propietarios/' + idpropietario + '/sub', function(data) {
                    var propietario = data.propietario;
                    var subPropietarios = data.subPropietarios;
                    $('#txtDeptoProp').text(propietario.departamento);
                    $('#txtNombresProp').text(propietario.nombre);

                    $('#tblSubPropietarios').DataTable({
                        processing: true,
                        serverSide: true,
                        language: {
                            url: '/datatables/spanish.json'
                        },
                        ajax: {
                            url: '{{ route('subpropietarios_get', ['id' => ':id']) }}'.replace(':id', idpropietario),
                            type: 'GET',
                            data: {
                                id: idpropietario
                            }
                        },
                        columns: [
                            { data: 'sub_propietario.nombre', name: 'sub_propietario.nombre' },
                            { data: 'sub_propietario.apellido', name: 'sub_propietario.apellido' },
                            { data: 'sub_propietario.correo_electronico', name: 'sub_propietario.correo_electronico' },
                            { data: 'sub_propietario.telefono', name: 'sub_propietario.telefono' },
                            { data: 'tipo_subpropietario', name: 'tipo_subpropietario' },
                            { data: 'action', name: 'action', orderable: false, searchable: false }
                        ]
                    });
                    // Mostrar el modal
                    $('#addSubPropModal').modal('show');
                });


            });

            $('#tblPropietarios').on('click', '.viewsubpropBtn', function() {
                var idpropietario = $(this).data('id');

                // Destruir DataTable existente si existe para evitar conflictos
                if ($.fn.DataTable.isDataTable('#tblVerSubPropietarios')) {
                    $('#tblVerSubPropietarios').DataTable().destroy();
                }
                // Obtener datos del propietario y sus sub propietarios


                $.get('/propietarios/' + idpropietario + '/sub', function(data) {
                    var propietario = data.propietario;
                    var subPropietarios = data.subPropietarios;
                    $('#txtVerDeptoProp').text(propietario.departamento);
                    $('#txtVerNombresProp').text(propietario.nombre);

                    $('#tblVerSubPropietarios').DataTable({
                        processing: true,
                        serverSide: true,
                        language: {
                            url: '/datatables/spanish.json'
                        },
                        ajax: {
                            url: '{{ route('subpropietarios_get', ['id' => ':id']) }}'.replace(':id', idpropietario),
                            type: 'GET',
                            data: {
                                id: idpropietario
                            }
                        },
                        columns: [
                            { data: 'sub_propietario.nombre', name: 'sub_propietario.nombre' },
                            { data: 'sub_propietario.apellido', name: 'sub_propietario.apellido' },
                            { data: 'sub_propietario.correo_electronico', name: 'sub_propietario.correo_electronico' },
                            { data: 'sub_propietario.telefono', name: 'sub_propietario.telefono' },
                            { data: 'tipo_subpropietario', name: 'tipo_subpropietario' },
                        ]
                    });
                    // Mostrar el modal
                    $('#verSubPropModal').modal('show');
                });


            });
            // Enviar formulario para agregar sub propietario
            $('#subPropForm').submit(function(e) {
                e.preventDefault();
                // Obtener la longitud del teléfono según el país seleccionado
                var longitudTelefono = $('#paissub option:selected').data('longitud');
                var telefono = $('#subproptelefono').val();

                // Validar longitud del teléfono
                if (telefono.length !== longitudTelefono) {
                    swal("Error!", "El número de teléfono debe tener "+longitudTelefono+" dígitos.", "error")
                    return; // Detener el envío si la validación falla
                }
                $.ajax({
                    url: '{{ route('sub_propietarios.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            limpiarSubPropietario();
                            swal("Registro Correcto!", response.success, "success")
                            $('#tblSubPropietarios').DataTable().ajax.reload(null, false);

                        } else {
                            swal("Error!", 'Ocurrió un error', "error")
                            console.log('Ocurrió un error');
                        }
                    },
                    error: function(response) {
                        swal("Error!", 'Ocurrió un error', "error")
                        console.log('Ocurrió un error');
                    }
                });
            });

            $('#propietarioForm').on('submit', function(e) {
                e.preventDefault();
                var formAction = $(this).attr('action');
                var formMethod = $(this).attr('method');
                var formData = $(this).serialize();
                // Obtener la longitud del teléfono según el país seleccionado
                var longitudTelefono = $('#pais option:selected').data('longitud');
                var telefono = $('#telefono').val();

                // Validar longitud del teléfono
                if (telefono.length !== longitudTelefono) {
                    swal("Error!", "El número de teléfono debe tener "+longitudTelefono+" dígitos.", "error")
                    return; // Detener el envío si la validación falla
                }
                $.ajax({
                    url: formAction,
                    type: formMethod,
                    data: formData,
                    success: function(response) {
                        $('#AddPropietarioModal').modal('hide');
                        swal("Registro Correcto!", response.success, "success")
                        $('#tblPropietarios').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Código de estado HTTP para errores de validación
                            var errors = xhr.responseJSON.errors; // Obtener los errores del JSON
                            var errorMessages = '';

                            // Construir un mensaje con todos los errores
                            $.each(errors, function(key, messages) {
                                errorMessages += messages.join('<br>') + '<br>';
                            });

                            swal({
                                title: "Error de Validación",
                                html: errorMessages, // Mostrar errores como HTML
                                icon: "error"
                            });
                        } else {
                            swal("Error!", "Ocurrió un error inesperado. Por favor, intenta nuevamente.", "error");
                        }
                    }
                });
            });

            $('#tblSubPropietarios').on('click', '.deleteBtnSubProp', function() {
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
                            url: '/propietarios/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#tblSubPropietarios').DataTable().ajax.reload();
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

            $('#tblSubPropietarios').on('click', '.editBtnSubProp', function() {
                var id = $(this).data('id');
                $.get('/propietarios/sub/' + id, function(data) {console.log('data',data);
                    $('#subpropietario_id').val(id);
                    //$('#propietario_id').val(data.propietario.id);
                    $('#subpropnombre').val(data.propietario.nombre);
                    $('#subpropapellido').val(data.propietario.apellido);
                    $('#subpropcorreo_electronico').val(data.propietario.correo_electronico);
                    $('#paissub').val(data.propietario.id_codigo_pais).trigger('change');
                    $('#subproptelefono').val(data.propietario.telefono);
                    $('#tipo_sub_propietario').val(data.subPropietarios.tipo_sub_propietario_id).trigger('change');
                    $('#default_collapseTwo').collapse('show');
                });
            });

            $('.btnCancelarSubPropietario').on('click', function() {
                limpiarSubPropietario()
            });

            function limpiarSubPropietario(){
                //btnCancelarSubPropietario
                $('#subpropietario_id').val('');
                $('#subpropnombre').val('');
                $('#subpropapellido').val('');
                $('#subpropcorreo_electronico').val('');
                $('#subproptelefono').val('');
                $('#paissub').val('1').trigger('change');
                $('#tipo_sub_propietario').val('');
                $('#tipo_sub_propietario').val('').trigger('change');
                $('#default_collapseTwo').collapse('hide');
            }
            $('#addSubPropModal').on('hidden.bs.modal', function () {
                // Recargar la tabla
                $('#tblPropietarios').DataTable().ajax.reload(null, false);
            });
        });
        document.getElementById('telefono').addEventListener('input', function (e) {
            var telefono = e.target.value;

            // Eliminar cualquier carácter que no sea un número
            telefono = telefono.replace(/\D/g, '');

            // Limitar a 9 dígitos
            /*if (telefono.length > 9) {
                telefono = telefono.slice(0, 9);
            }*/

            // Asignar el valor formateado de nuevo al campo de entrada
            e.target.value = telefono;
        });

        document.getElementById('subproptelefono').addEventListener('input', function (e) {
            var subproptelefono = e.target.value;

            // Eliminar cualquier carácter que no sea un número
            subproptelefono = subproptelefono.replace(/\D/g, '');

            // Limitar a 9 dígitos
            /*if (subproptelefono.length > 9) {
                subproptelefono = subproptelefono.slice(0, 9);
            }*/

            // Asignar el valor formateado de nuevo al campo de entrada
            e.target.value = subproptelefono;
        });


    </script>
