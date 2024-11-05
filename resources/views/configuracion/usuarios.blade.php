{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Configuracion</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Usuarios</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Usuarios</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                        <a href="javascript:void(0);" id="createUsersBtn" class="btn btn-primary ml-auto">+ Crear Usuarios Multiples</a>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#AddUsuarioModal" class="btn btn-primary ml-auto">+ Nuevo Usuario</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblUsuarios" class="table table-striped table-condensed flip-content">
                            <thead>
                                <tr>
                                    <th>Nombres</th>
                                    <th>Correo</th>
                                    <th>Celular</th>
                                    <th>Perfil</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="AddUsuarioModal" tabindex="-1" aria-labelledby="AddUsuarioModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('addusuario') }}"  id="usuarioForm">
                                @csrf
                                <input type="hidden" name="id" id="usuarioId">
                                <div class="modal-header">
                                <h4 class="modal-title" id="AddUsuarioModalLabel-1">Agregar Nuevo Usuario</h4>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="txtNombres">Nombres :</label>
                                        <input type="text" class="form-control" id="txtNombres" name="nombres_completos" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtCorreo">Correo:</label>
                                        <input type="email" class="form-control" id="txtCorreo" name="correo_electronico" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtCelular">Celular:</label>
                                        <input type="text" class="form-control" id="txtCelular" name="telefono" maxlength="9" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cbxPerfil">Perfil:</label>
                                        <select class="form-control" id="cbxPerfil" name="id_perfil" required>
                                            <option value="">Seleccione un Perfil</option>
                                            @foreach ($perfiles as $perfil)
                                                <option value="{{ $perfil->id }}"> {{ $perfil->nombre_perfil }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtCorreo">Usuario:</label>
                                        <input type="text" class="form-control" id="txtUsuario" name="usuario">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtCelular">Contraseña:</label>
                                        <input type="password" class="form-control" id="txtContrasenia" name="contrasenia" required>
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
            $('#tblUsuarios').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('usuarios.data') }}',
                columns: [
                    { data: 'nombres_completos', name: 'nombres_completos' },
                    { data: 'correo_electronico', name: 'correo_electronico' },
                    { data: 'telefono', name: 'telefono' },
                    { data: 'nombre_perfil', name: 'nombre_perfil' },
                    { data: 'estado', name: 'estado' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
            $('#AddUsuarioModal').on('hidden.bs.modal', function () {
                $('#usuarioId').val('');
                $('#txtNombres').val('');
                $('#txtCorreo').val('');
                $('#txtCelular').val('');
                $('#cbxPerfil').val('').trigger('change');
                $('#txtUsuario').val('');
                $('#txtUsuario').prop('disabled', '');
                $('#txtContrasenia').prop('required', 'required');
                $('#AddUsuarioModalLabel-1').text('Agregar Nuevo Usuario');
                $('#UsuarioForm').attr('action', '{{ route('addusuario') }}').attr('method', 'POST');
                $('#UsuarioForm input[name="_method"]').remove();
            });
            $('#tblUsuarios').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('/usuarios/get/' + id, function(data) {
                    console.log('Jamasss',data);
                    $('#usuarioId').val(data.id);
                    $('#txtNombres').val(data.nombres_completos);
                    $('#txtCorreo').val(data.correo_electronico);
                    $('#txtCelular').val(data.telefono);
                    $('#cbxPerfil').val(data.id_perfil).trigger('change');
                    $('#txtUsuario').val(data.usuario);
                    $('#txtContrasenia').prop('required', '');
                    $('#txtUsuario').prop('disabled', 'disabled');
                    $('#AddUsuarioModal').modal('show');
                    $('#AddUsuarioModalLabel-1').text('Editar Usuario');
                    $('#UsuarioForm').attr('action', '/editusuario/' + id).attr('method', 'POST');
                    $('#UsuarioForm').append('<input type="hidden" name="_method" value="PUT">');
                });
            });

            $('#usuarioForm').on('submit', function(e) {
                e.preventDefault();
                var formAction = $(this).attr('action');
                var formMethod = $(this).attr('method');
                var formData = $(this).serialize();

                $.ajax({
                    url: formAction,
                    type: formMethod,
                    data: formData,
                    success: function(response) {
                        $('#AddUsuarioModal').modal('hide');
                        swal("Registro Correcto!", response.success, "success")
                        $('#tblUsuarios').DataTable().ajax.reload(null, false);
                    },
                    error: function(response) {
                        swal("Error!", response, "error")
                        console.error(response);
                    }
                });
            });

            $('#tblUsuarios').on('click', '.deleteBtn', function() {
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
                            url: '/usuarios/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#tblUsuarios').DataTable().ajax.reload();
                            }
                        });

                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });

            $('#tblUsuarios').on('click', '.activeBtn', function() {
                var id = $(this).data('id');
                swal({
                    title: "¿Estás seguro?",
                    text: "¡Va a activar al usuario!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#6418C3",
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Sí, activar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: !1,
                    closeOnCancel: !1
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/usuarios/active/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Activado!", response.success, "success")
                                $('#tblUsuarios').DataTable().ajax.reload();
                            }
                        });

                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });


            $('#createUsersBtn').on('click', function() {
                swal({
                    title: "¿Estás seguro?",
                    text: "Esta acción creará usuarios para los propietarios seleccionados.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willCreate) => {
                    if (willCreate) {
                        // Llamar al controlador a través de AJAX
                        $.ajax({
                            url: "{{ route('addusuariomultiple', ['cantidadPropietario' => 120]) }}", // Reemplaza con el número de propietarios si es necesario
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}", // Token CSRF necesario para Laravel
                            },
                            success: function(response) {
                                // Si la respuesta es exitosa, mostrar SweetAlert de éxito
                                swal("¡Usuarios creados!", response.success, "success");
                                $('#tblUsuarios').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                // Si hay un error, mostrar el mensaje de error devuelto por el controlador
                                let errorMessage = xhr.responseJSON ? xhr.responseJSON.error : "Ocurrió un error, contacta al administrador.";
                                swal("Error!", errorMessage, "error");
                            }
                        });
                    }
                });
            });

        });
    </script>
