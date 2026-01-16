{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Panel</a></li>
            <li class="breadcrumb-item active">Notificación Masiva - Morosidad Crítica</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 pb-0 bg-danger text-white">
                    <h4 class="card-title text-white">
                        <i class="fa fa-exclamation-triangle mr-2"></i>Notificación Masiva - Morosidad Crítica
                    </h4>
                </div>
                <div class="card-body">
                    @if(!$whatsappConnected)
                    <div class="alert alert-warning">
                        <i class="fa fa-warning mr-2"></i>
                        <strong>WhatsApp no está conectado.</strong> 
                        Por favor, conéctelo primero desde 
                        <a href="{{ route('whatsapp.config') }}" class="alert-link">Configuración de WhatsApp</a>.
                    </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle mr-2"></i>
                        Se enviará el mensaje a <strong>{{ count($propietariosCriticos) }} propietarios</strong> en morosidad crítica (deuda de 3 meses o más).
                    </div>

                    <form id="formNotificacionMasiva">
                        @csrf
                        <div class="form-group">
                            <label class="font-w600">Mensaje Personalizado</label>
                            <textarea 
                                class="form-control" 
                                id="mensaje" 
                                name="mensaje" 
                                rows="8" 
                                placeholder="Escriba el mensaje a enviar..."
                                required
                            >Estimado/a {nombre} del departamento {departamento}, le recordamos que tiene una deuda pendiente de S/ {deuda}. Por favor, regularice su situación a la brevedad posible. Gracias por su atención.</textarea>
                            <small class="text-muted">
                                Puede usar las siguientes variables: <code>{departamento}</code>, <code>{nombre}</code>, <code>{deuda}</code>
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-danger btn-lg" {{ !$whatsappConnected ? 'disabled' : '' }}>
                                <i class="fa fa-paper-plane mr-2"></i>Enviar Notificaciones ({{ count($propietariosCriticos) }})
                            </button>
                            <a href="{{ route('panel') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fa fa-times mr-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Propietarios Críticos -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Lista de Propietarios en Morosidad Crítica</h4>
                    <p class="mb-0 text-muted fs-12">Vecinos con deuda de 3 meses o más</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Departamento</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th class="text-right">Deuda Pendiente</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($propietariosCriticos as $propietario)
                                    <tr>
                                        <td><strong>{{ $propietario['departamento'] }}</strong></td>
                                        <td>{{ $propietario['nombre'] }} {{ $propietario['apellido'] }}</td>
                                        <td>{{ $propietario['telefono'] ?? 'N/A' }}</td>
                                        <td class="text-right text-danger font-w600">
                                            S/ {{ number_format($propietario['deuda'], 2) }}
                                        </td>
                                        <td>
                                            @if($propietario['telefono_valido'] ?? false)
                                                <span class="badge badge-success">Teléfono Válido</span>
                                            @else
                                                <span class="badge badge-warning">Sin Teléfono</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay propietarios en morosidad crítica</td>
                                    </tr>
                                @endforelse
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
    $(document).ready(function() {
        $('#formNotificacionMasiva').on('submit', function(e) {
            e.preventDefault();
            
            var $btn = $(this).find('button[type="submit"]');
            var $btnOriginal = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i>Enviando...');

            var formData = {
                mensaje: $('#mensaje').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route("notificacion.masiva.criticos.enviar") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        swal({
                            title: "¡Éxito!",
                            text: `Se enviaron ${response.enviados} de ${response.total} notificaciones.${response.fallidos > 0 ? ' ' + response.fallidos + ' fallaron.' : ''}`,
                            type: "success",
                            confirmButtonText: "Aceptar"
                        }).then(() => {
                            window.location.href = '{{ route("panel") }}';
                        });
                    } else {
                        swal("Error", response.error || "Ocurrió un error al enviar las notificaciones", "error");
                        $btn.prop('disabled', false).html($btnOriginal);
                    }
                },
                error: function(xhr) {
                    var error = xhr.responseJSON?.error || "Ocurrió un error en el servidor";
                    swal("Error", error, "error");
                    $btn.prop('disabled', false).html($btnOriginal);
                }
            });
        });
    });
</script>
@endsection

