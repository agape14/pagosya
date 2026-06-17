{{-- Extends layout --}}
@extends('layout.default')

@section('content')
@php
    $etiquetasPermisos = [
        'panelcontrol' => 'Panel de control',
        'mantenimientos' => 'Mantenimientos',
        'torres' => 'Torres',
        'tipoconcepto' => 'Tipos de concepto',
        'conceptos' => 'Conceptos',
        'propietarios' => 'Propietarios',
        'intbancario' => 'Interés bancario',
        'pagos' => 'Pagos',
        'programacion' => 'Programación de pagos',
        'registropagos' => 'Registro de pagos',
        'gastos' => 'Gastos',
        'configuracion' => 'Configuración',
        'usuarios' => 'Usuarios',
        'permisos' => 'Permisos',
        'agregar' => 'Agregar',
        'editar' => 'Editar',
        'eliminar' => 'Eliminar',
        'ingresos' => 'Ingresos',
        'reportes' => 'Reportes',
        'noticias' => 'Noticias',
        'documentosimportantes' => 'Documentos importantes',
    ];
@endphp

<style>
    .permisos-toolbar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .permisos-toolbar .form-group label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
    }
    .permisos-toolbar .select2-container {
        width: 100% !important;
    }
    .permisos-toolbar .select2-container--default .select2-selection--single {
        height: 42px;
        border: 1px solid #ced4da;
        border-radius: 8px;
    }
    .permisos-toolbar .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        padding-left: 12px;
        color: #334155;
    }
    .permisos-toolbar .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .permisos-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: flex-end;
        height: 100%;
    }
    .permisos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    .permiso-grupo-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
        height: 100%;
    }
    .permiso-grupo-card .grupo-header {
        background: linear-gradient(135deg, #6418c3 0%, #4f46e5 100%);
        color: #fff;
        padding: 0.85rem 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .permiso-grupo-card .grupo-header .btn-link {
        color: rgba(255,255,255,0.9);
        font-size: 0.75rem;
        text-decoration: none;
        white-space: nowrap;
    }
    .permiso-grupo-card .grupo-header .btn-link:hover {
        color: #fff;
        text-decoration: underline;
    }
    .permiso-grupo-card .grupo-body {
        padding: 0.75rem 1rem 1rem;
    }
    .permiso-item {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        padding: 0.55rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .permiso-item:last-child { border-bottom: none; }
    .permiso-item input[type="checkbox"] {
        margin-top: 0.2rem;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
    .permiso-item label {
        margin: 0;
        color: #334155;
        font-size: 0.92rem;
        line-height: 1.4;
        cursor: pointer;
    }
    .permiso-item.hijo {
        margin-left: 0.5rem;
        padding-left: 0.75rem;
        border-left: 2px solid #e0e7ff;
    }
    .permisos-empty-hint {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #64748b;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
    }
    .permisos-empty-hint i {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 0.75rem;
    }
</style>

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Configuracion</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Permisos</a></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                    <h4 class="card-title mb-0">Gestión de permisos por usuario</h4>
                </div>
                <div class="card-body">
                    <div class="permisos-toolbar">
                        <div class="row align-items-end">
                            <div class="col-lg-6 col-md-8 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label for="usuarios-select">Seleccionar usuario</label>
                                    <select id="usuarios-select" class="form-control">
                                        <option value="">Buscar o seleccionar un usuario...</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->nombres_completos }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4">
                                <div class="permisos-actions">
                                    <button id="guardar-permisos-btn" class="btn btn-primary" disabled>
                                        <i class="fa fa-save mr-1"></i> Actualizar permisos
                                    </button>
                                    <button id="habilitapopup" class="btn btn-success">
                                        <i class="fa fa-window-restore mr-1"></i> Activar / Desactivar PopUp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="permisos-empty" class="permisos-empty-hint">
                        <div><i class="fa fa-user-circle-o d-block"></i></div>
                        <h5 class="mb-2">Seleccione un usuario</h5>
                        <p class="mb-0">Use el buscador para encontrar un usuario y asignarle los permisos del sistema.</p>
                    </div>

                    <div id="permisos-content" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                            <p class="text-muted mb-2 mb-md-0">
                                <i class="fa fa-info-circle mr-1"></i>
                                Marque los módulos a los que el usuario tendrá acceso.
                            </p>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-seleccionar-todos">
                                    <i class="fa fa-check-square-o mr-1"></i> Seleccionar todos
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-deseleccionar-todos">
                                    <i class="fa fa-square-o mr-1"></i> Quitar todos
                                </button>
                            </div>
                        </div>

                        <div class="permisos-grid" id="arbol-permisos">
                            @foreach($permisos as $permiso)
                                <div class="permiso-grupo-card" data-grupo="{{ $permiso->id }}">
                                    <div class="grupo-header">
                                        <span>{{ $etiquetasPermisos[$permiso->nombre_permiso] ?? ucfirst($permiso->nombre_permiso) }}</span>
                                        <a href="javascript:void(0);" class="btn-link btn-toggle-grupo" data-grupo="{{ $permiso->id }}">Alternar</a>
                                    </div>
                                    <div class="grupo-body">
                                        <div class="permiso-item">
                                            <input type="checkbox" id="permiso_{{ $permiso->id }}" value="{{ $permiso->id }}" data-grupo="{{ $permiso->id }}">
                                            <label for="permiso_{{ $permiso->id }}">{{ $etiquetasPermisos[$permiso->nombre_permiso] ?? ucfirst($permiso->nombre_permiso) }}</label>
                                        </div>
                                        @foreach($permiso->hijos as $hijo)
                                            <div class="permiso-item hijo">
                                                <input type="checkbox" id="permiso_{{ $hijo->id }}" value="{{ $hijo->id }}" data-grupo="{{ $permiso->id }}">
                                                <label for="permiso_{{ $hijo->id }}">{{ $etiquetasPermisos[$hijo->nombre_permiso] ?? ucfirst($hijo->nombre_permiso) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#usuarios-select').select2({
        placeholder: 'Buscar o seleccionar un usuario...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return 'No se encontraron usuarios'; },
            searching: function() { return 'Buscando...'; }
        }
    });

    function togglePermisosPanel(show) {
        $('#permisos-content').toggleClass('d-none', !show);
        $('#permisos-empty').toggleClass('d-none', show);
        $('#guardar-permisos-btn').prop('disabled', !show);
    }

    function actualizarCheckboxes(permisos) {
        $('#arbol-permisos input[type="checkbox"]').prop('checked', false);
        permisos.forEach(function(permisoId) {
            $('#arbol-permisos input[type="checkbox"][value="' + permisoId + '"]').prop('checked', true);
        });
    }

    $('#usuarios-select').on('change', function() {
        var usuarioId = $(this).val();
        if (usuarioId === '') {
            togglePermisosPanel(false);
            actualizarCheckboxes([]);
            return;
        }

        togglePermisosPanel(true);

        $.ajax({
            url: '/getpermisos/' + usuarioId,
            type: 'GET',
            success: function(response) {
                actualizarCheckboxes(response.permisos);
            },
            error: function(xhr, status, error) {
                swal('Error!', 'No se pudieron cargar los permisos del usuario.', 'error');
                console.error('Error al obtener permisos:', error);
            }
        });
    });

    $('#btn-seleccionar-todos').on('click', function() {
        $('#arbol-permisos input[type="checkbox"]').prop('checked', true);
    });

    $('#btn-deseleccionar-todos').on('click', function() {
        $('#arbol-permisos input[type="checkbox"]').prop('checked', false);
    });

    $('.btn-toggle-grupo').on('click', function() {
        var grupoId = $(this).data('grupo');
        var $checks = $('#arbol-permisos input[data-grupo="' + grupoId + '"]');
        var todosMarcados = $checks.length === $checks.filter(':checked').length;
        $checks.prop('checked', !todosMarcados);
    });

    $('#guardar-permisos-btn').on('click', function() {
        var usuarioId = $('#usuarios-select').val();
        if (!usuarioId) {
            swal('Atención!', 'Seleccione un usuario primero.', 'warning');
            return;
        }

        var permisosSeleccionados = [];
        $('#arbol-permisos input[type="checkbox"]:checked').each(function() {
            permisosSeleccionados.push($(this).val());
        });

        $.ajax({
            url: '/addpermisos',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                usuario_id: usuarioId,
                permisos_seleccionados: permisosSeleccionados,
            },
            success: function(response) {
                swal('Actualizado!', response.success, 'success');
            },
            error: function(xhr, status, error) {
                swal('Error!', 'Error al guardar permisos: ' + error, 'error');
            }
        });
    });

    $('#habilitapopup').on('click', function() {
        swal({
            title: '¿Estás seguro?',
            text: '¡Actualizando la visualización de popup!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6418C3',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '/habilitapopup',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        swal('Actualizado!', response.success, 'success');
                    },
                    error: function(xhr, status, error) {
                        swal('Error!', 'Error al des/habilitar Popup: ' + error, 'error');
                    }
                });
            } else {
                swal('Cancelado!', 'Se canceló la acción', 'error');
            }
        });
    });
});
</script>
@endsection
