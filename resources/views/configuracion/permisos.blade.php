{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Configuracion</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Permisos</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Permisos</h4>
                </div>
                <div class="card-body">
                    {{--<div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#AddPermisoModal" class="btn btn-primary ml-auto">+ Nueva Permiso</a>
                    </div>--}}
                    <div class="table-responsive">
                        <select id="usuarios-select">
                            <option value="">Selecciona un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->nombres_completos }}</option>
                            @endforeach
                        </select>
                        <button id="guardar-permisos-btn"  class="btn btn-primary ml-auto" >Actualizar Permisos</button>
                        <ul id="arbol-permisos">
                            @foreach($permisos as $permiso)
                                <li>
                                    <input type="checkbox" id="permiso_{{ $permiso->id }}" value="{{ $permiso->id }}">
                                    <label for="permiso_{{ $permiso->id }}">{{ $permiso->nombre_permiso }}</label>
                                    @if($permiso->hijos->count() > 0)
                                        <ul>
                                            @foreach($permiso->hijos as $hijo)
                                                <li>
                                                    <input type="checkbox" id="permiso_{{ $hijo->id }}" value="{{ $hijo->id }}">
                                                    <label for="permiso_{{ $hijo->id }}">{{ $hijo->nombre_permiso }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
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
            $('#usuarios-select').change(function() {
                var usuarioId = $(this).val();
                if(usuarioId === '') {
                    // Limpiar todos los checkboxes del árbol
                    actualizarCheckboxes([]);
                } else {
                    // Enviar solicitud AJAX para obtener los permisos del usuario seleccionado
                    $.ajax({
                        url: '/getpermisos/' + usuarioId,
                        type: 'GET',
                        /*data: {
                            usuario_id: usuarioId,
                            _token: $('meta[name="csrf-token"]').attr('content') // Agregar token CSRF
                        },*/
                        success: function(response) {
                            // Actualizar los checkboxes del árbol con los permisos del usuario
                            actualizarCheckboxes(response.permisos);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener permisos:', error);
                        }
                    });
                }
                
            });

            // Función para actualizar los checkboxes del árbol
            function actualizarCheckboxes(permisos) {
                // Desmarcar todos los checkboxes
                $('#arbol-permisos input[type="checkbox"]').prop('checked', false);

                // Marcar los checkboxes correspondientes a los permisos del usuario
                permisos.forEach(function(permisoId) {
                    $('#arbol-permisos input[type="checkbox"][value="' + permisoId + '"]').prop('checked', true);
                });
            }

            $('#guardar-permisos-btn').click(function() {
                var usuarioId = $('#usuarios-select').val();
                var permisosSeleccionados = [];

                $('#arbol-permisos').find('input[type="checkbox"]:checked').each(function() {
                    permisosSeleccionados.push($(this).val());
                });

                // Enviar datos mediante AJAX
                $.ajax({
                    url: '/addpermisos',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        usuario_id: usuarioId,
                        permisos_seleccionados: permisosSeleccionados,
                    },
                    success: function(response) {
                        swal("Actualizado!", response.success, "success")
                        console.log('Permisos guardados exitosamente.');
                    },
                    error: function(xhr, status, error) {
                        swal("Error!", "Error al guardar permisos: "+error, "error")
                        console.error('Error al guardar permisos:', error);
                    }
                });
            });

        });
    </script>