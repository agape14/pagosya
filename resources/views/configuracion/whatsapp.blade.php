@extends('layout.default')

@section('content')
<style>
    .status-badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }
    .qr-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
    }
    .qr-container img {
        max-width: 280px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .whatsapp-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        border-radius: 12px;
    }
    .log-table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .status-enviado { background-color: #28a745; }
    .status-fallido { background-color: #dc3545; }
    .status-pendiente { background-color: #ffc107; color: #000; }
</style>

<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Configuración de WhatsApp</h4>
                <p class="mb-0">Gestiona la conexión de WhatsApp para envío de notificaciones</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item active">WhatsApp</li>
            </ol>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    <div class="row">
        <!-- Estado de Conexión -->
        <div class="col-xl-6 col-lg-12">
            <div class="card whatsapp-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-whatsapp text-success mr-2"></i>Estado de Conexión
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span id="statusBadge" class="badge status-badge {{ ($status['connected'] ?? false) ? 'badge-success' : 'badge-danger' }}">
                            <i class="fa {{ ($status['connected'] ?? false) ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                            <span id="statusText">{{ ($status['connected'] ?? false) ? 'Conectado' : 'Desconectado' }}</span>
                        </span>
                    </div>

                    <!-- QR Container -->
                    <div id="qrContainer" class="qr-container {{ ($status['connected'] ?? false) ? 'd-none' : '' }}">
                        @if($qr)
                            <p class="text-muted mb-3">Escanea el código QR con WhatsApp para vincular:</p>
                            <img id="qrImage" src="{{ $qr }}" alt="Código QR">
                        @else
                            <p class="text-muted mb-3">
                                <i class="fa fa-spinner fa-spin mr-2"></i>
                                Generando código QR...
                            </p>
                        @endif
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary" id="btnRefreshQR">
                                <i class="fa fa-refresh mr-1"></i> Refrescar QR
                            </button>
                        </div>
                    </div>

                    <!-- Conectado -->
                    <div id="connectedContainer" class="{{ ($status['connected'] ?? false) ? '' : 'd-none' }} text-center">
                        <div class="mb-3">
                            <i class="fa fa-whatsapp text-success" style="font-size: 5rem;"></i>
                        </div>
                        <p class="text-success font-weight-bold">WhatsApp está listo para enviar mensajes</p>
                        <button type="button" class="btn btn-danger mt-3" id="btnDisconnect">
                            <i class="fa fa-sign-out mr-1"></i> Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="col-xl-6 col-lg-12">
            <div class="card whatsapp-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-info-circle text-info mr-2"></i>Instrucciones
                    </h4>
                </div>
                <div class="card-body">
                    <ol class="pl-3">
                        <li class="mb-2">Abre WhatsApp en tu teléfono</li>
                        <li class="mb-2">Ve a <strong>Configuración > Dispositivos vinculados</strong></li>
                        <li class="mb-2">Toca <strong>Vincular un dispositivo</strong></li>
                        <li class="mb-2">Escanea el código QR que aparece en esta pantalla</li>
                        <li class="mb-2">Espera a que el estado cambie a "Conectado"</li>
                    </ol>
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-exclamation-triangle mr-2"></i>
                        <strong>Importante:</strong> No cierres sesión de WhatsApp Web en tu teléfono, ya que esto desconectará el sistema.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs de Mensajes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card whatsapp-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-history mr-2"></i>Historial de Mensajes
                    </h4>
                </div>
                <div class="card-body">
                    <form class="row mb-4" id="filterForm">
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fechaInicio" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fechaFin" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tipo</label>
                            <select class="form-control" name="tipo" id="tipoFiltro">
                                <option value="">Todos</option>
                                <option value="noticia">Noticias</option>
                                <option value="moroso">Morosos</option>
                                <option value="recibo">Recibos</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" id="btnBuscarLogs">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped log-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Vecino</th>
                                    <th>Teléfono</th>
                                    <th>Tipo</th>
                                    <th>Mensaje</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Haz clic en "Buscar" para cargar los logs
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Prevenir error de selectpicker si no existe
if (typeof $.fn.selectpicker === 'undefined') {
    $.fn.selectpicker = function() { return this; };
}
$(document).ready(function() {
    // Refrescar estado cada 5 segundos si está desconectado
    let statusInterval = null;

    function checkStatus() {
        $.get('{{ route("whatsapp.status") }}', function(data) {
            if (data.connected) {
                $('#statusBadge').removeClass('badge-danger').addClass('badge-success');
                $('#statusBadge i').removeClass('fa-times-circle').addClass('fa-check-circle');
                $('#statusText').text('Conectado');
                $('#qrContainer').addClass('d-none');
                $('#connectedContainer').removeClass('d-none');
                if (statusInterval) {
                    clearInterval(statusInterval);
                    statusInterval = null;
                }
            } else {
                $('#statusBadge').removeClass('badge-success').addClass('badge-danger');
                $('#statusBadge i').removeClass('fa-check-circle').addClass('fa-times-circle');
                $('#statusText').text('Desconectado');
                $('#qrContainer').removeClass('d-none');
                $('#connectedContainer').addClass('d-none');
            }
        });
    }

    // Iniciar verificación periódica si está desconectado
    @if(!($status['connected'] ?? false))
    statusInterval = setInterval(checkStatus, 5000);
    @endif

    // Refrescar QR
    $('#btnRefreshQR').on('click', function() {
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Cargando...');
        $.get('{{ route("whatsapp.qr") }}', function(data) {
            if (data.qr) {
                $('#qrImage').attr('src', data.qr);
            } else {
                toastr.warning(data.message || 'QR no disponible aún');
            }
            $('#btnRefreshQR').prop('disabled', false).html('<i class="fa fa-refresh mr-1"></i> Refrescar QR');
        }).fail(function() {
            toastr.error('Error al obtener QR');
            $('#btnRefreshQR').prop('disabled', false).html('<i class="fa fa-refresh mr-1"></i> Refrescar QR');
        });
    });

    // Cerrar sesión
    $('#btnDisconnect').on('click', function() {
        if (!confirm('¿Estás seguro de cerrar la sesión de WhatsApp?')) return;

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Cerrando...');
        $.post('{{ route("whatsapp.disconnect") }}', {
            _token: '{{ csrf_token() }}'
        }, function(data) {
            if (data.success) {
                toastr.success('Sesión cerrada correctamente');
                location.reload();
            } else {
                toastr.error(data.error || 'Error al cerrar sesión');
            }
        }).fail(function() {
            toastr.error('Error al cerrar sesión');
        }).always(function() {
            $('#btnDisconnect').prop('disabled', false).html('<i class="fa fa-sign-out mr-1"></i> Cerrar Sesión');
        });
    });

    // Buscar logs
    $('#btnBuscarLogs').on('click', function() {
        loadLogs();
    });

    function loadLogs() {
        $('#logsTableBody').html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</td></tr>');

        $.get('{{ route("whatsapp.logs") }}', {
            fecha_inicio: $('#fechaInicio').val(),
            fecha_fin: $('#fechaFin').val(),
            tipo: $('#tipoFiltro').val()
        }, function(response) {
            let html = '';
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(log) {
                    let statusClass = 'status-' + log.status;
                    let vecinoNombre = log.propietario ? (log.propietario.nombre + ' ' + log.propietario.apellido) : '-';
                    html += `
                        <tr>
                            <td>${new Date(log.fecha).toLocaleString('es-PE')}</td>
                            <td>${vecinoNombre}</td>
                            <td>${log.telefono || '-'}</td>
                            <td><span class="badge badge-info">${log.tipo}</span></td>
                            <td>${log.mensaje.substring(0, 50)}...</td>
                            <td>
                                <span class="badge ${statusClass}">${log.status}</span>
                                ${log.error_message ? '<br><small class="text-danger">' + log.error_message + '</small>' : ''}
                            </td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="6" class="text-center text-muted">No hay registros para las fechas seleccionadas</td></tr>';
            }
            $('#logsTableBody').html(html);
        }).fail(function() {
            $('#logsTableBody').html('<tr><td colspan="6" class="text-center text-danger">Error al cargar logs</td></tr>');
        });
    }
});
</script>
@endsection

