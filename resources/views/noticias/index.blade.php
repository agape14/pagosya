{{-- Extends layout --}}
@extends('layout.default')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

{{-- Content --}}
@section('content')
<style>
    .noticia-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 12px;
        overflow: hidden;
    }
    .noticia-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .noticia-imagen {
        height: 220px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.3s ease;
    }
    .noticia-card:hover .noticia-imagen {
        transform: scale(1.05);
    }
    .noticia-titulo {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .noticia-fecha {
        font-size: 0.875rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .noticia-contenido {
        color: #495057;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    .noticia-acciones {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    .btn-accion {
        flex: 1;
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    .btn-accion:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .badge-nuevo {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
    }
    .sin-imagen {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state-icon {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    .modal-detalle-imagen {
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
    }
    .card-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
</style>

<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Noticias y Comunicados</h4>
                <p class="mb-0">Mantente informado de las últimas novedades del condominio.</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            @if(Auth::user()->id_perfil <= 2)
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearNoticiaModal">
                <i class="fa fa-plus mr-2"></i> Nueva Noticia
            </button>
            @endif
        </div>
    </div>

    <div class="row">
        @forelse($noticias as $noticia)
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card noticia-card h-100 position-relative">
                @if($noticia->created_at->diffInDays(now()) <= 7)
                <span class="badge-nuevo">
                    <i class="fa fa-star mr-1"></i> Nuevo
                </span>
                @endif

                @if($noticia->imagen)
                    @php
                        // Manejar imágenes antiguas (public/images/noticias) y nuevas (storage/app/public/noticias)
                        $imagenUrl = str_starts_with($noticia->imagen, 'images/')
                            ? asset($noticia->imagen)
                            : Storage::url($noticia->imagen);
                    @endphp
                    <img src="{{ $imagenUrl }}" class="noticia-imagen" alt="{{ $noticia->titulo }}">
                @else
                    <div class="sin-imagen">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                @endif

                <div class="card-body">
                    <div class="noticia-fecha mb-2">
                        <i class="fa fa-calendar text-primary"></i>
                        <span>{{ $noticia->created_at->format('d') }} de {{ ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][$noticia->created_at->format('n') - 1] }}, {{ $noticia->created_at->format('Y') }}</span>
                        <span class="ml-auto">
                            <i class="fa fa-clock-o text-muted"></i>
                            {{ $noticia->created_at->format('h:i A') }}
                        </span>
                    </div>

                    <h5 class="noticia-titulo">{{ $noticia->titulo }}</h5>

                    <div class="noticia-contenido">
                        <p class="mb-0">{{ Str::limit(strip_tags($noticia->contenido), 120) }}</p>
                    </div>

                    <div class="noticia-acciones">
                        <button type="button" class="btn btn-primary btn-accion verNoticiaCompleta {{ Auth::user()->id_perfil > 2 ? 'w-100' : '' }}"
                           data-titulo="{{ $noticia->titulo }}"
                           data-contenido="{{ $noticia->contenido }}"
                           data-fecha="{{ $noticia->created_at->format('d') }} de {{ ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][$noticia->created_at->format('n') - 1] }}, {{ $noticia->created_at->format('Y') }} a las {{ $noticia->created_at->format('h:i A') }}"
                           data-imagen="{{ $noticia->imagen ? (str_starts_with($noticia->imagen, 'images/') ? asset($noticia->imagen) : Storage::url($noticia->imagen)) : '' }}">
                            <i class="fa fa-eye mr-1"></i> Ver más
                        </button>
                        @if(Auth::user()->id_perfil <= 2)
                        <button type="button" class="btn btn-success btn-accion compartirWhatsapp"
                           data-titulo="{{ $noticia->titulo }}"
                           data-contenido="{{ $noticia->contenido }}">
                            <i class="fa fa-whatsapp mr-1"></i> Compartir
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa fa-newspaper-o"></i>
                </div>
                <h4 class="text-muted mb-3">No hay noticias publicadas</h4>
                <p class="text-muted mb-4">Aún no se han publicado noticias en el sistema.</p>
                @if(Auth::user()->id_perfil <= 2)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearNoticiaModal">
                    <i class="fa fa-plus mr-2"></i> Publicar Primera Noticia
                </button>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if($noticias->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Paginación de noticias">
                {{ $noticias->links() }}
            </nav>
        </div>
    </div>
    @endif
</div>

<!-- Modal Crear Noticia -->
<div class="modal fade" id="crearNoticiaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data" id="formCrearNoticia">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">
                        <i class="fa fa-plus-circle mr-2"></i>Publicar Nueva Noticia
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required placeholder="Ej: Mantenimiento de Ascensor" maxlength="255">
                        <small class="form-text text-muted">Ingrese un título descriptivo para la noticia</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Contenido <span class="text-danger">*</span></label>
                        <textarea name="contenido" class="form-control" rows="6" required placeholder="Escriba el detalle completo de la noticia..."></textarea>
                        <small class="form-text text-muted">Describa todos los detalles importantes de la noticia</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Imagen (Opcional)</label>
                        <div class="custom-file">
                            <input type="file" name="imagen" class="custom-file-input" id="imagenNoticia" accept="image/*">
                            <label class="custom-file-label" for="imagenNoticia">Seleccionar Imagen</label>
                        </div>
                        <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                        <div class="mt-2" id="previewImagen" style="display: none;">
                            <img id="previewImg" src="" alt="Vista previa" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane mr-1"></i> Publicar Noticia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detalle Noticia -->
<div class="modal fade" id="detalleNoticiaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="detalleTitulo">
                    <i class="fa fa-newspaper-o mr-2"></i>Título
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center text-muted">
                        <i class="fa fa-calendar mr-2"></i>
                        <span id="detalleFecha"></span>
                    </div>
                </div>
                <img id="detalleImagen" src="" class="modal-detalle-imagen mb-3 d-none w-100" alt="Imagen de noticia">
                <div id="detalleContenido" class="noticia-contenido-detalle" style="line-height: 1.8; color: #495057;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cerrar
                </button>
                @if(Auth::user()->id_perfil <= 2)
                <button type="button" class="btn btn-success compartirWhatsappDesdeDetalle">
                    <i class="fa fa-whatsapp mr-1"></i> Compartir por WhatsApp
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Compartir WhatsApp -->
<div class="modal fade" id="whatsappModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="fa fa-whatsapp mr-2"></i>Difundir por WhatsApp
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle mr-2"></i>
                    Seleccione un vecino para enviar la noticia. Se abrirá WhatsApp Web/App con el mensaje precargado.
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control" id="searchVecino" placeholder="Buscar por nombre, apellido o departamento...">
                </div>
                <div class="list-group" id="listaVecinos" style="max-height: 400px; overflow-y: auto;">
                    @forelse($propietarios as $prop)
                        @if($prop->telefono)
                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start vecino-item">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fa fa-user mr-2 text-primary"></i>
                                        {{ $prop->nombre }} {{ $prop->apellido }}
                                        <small class="text-muted">({{ $prop->departamento }})</small>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fa fa-phone mr-1"></i>{{ $prop->telefono }}
                                    </small>
                                </div>
                                <button class="btn btn-success btn-sm btn-send-wa"
                                    data-phone="{{ $prop->telefono }}">
                                    <i class="fa fa-paper-plane mr-1"></i> Enviar
                                </button>
                            </div>
                        </a>
                        @endif
                    @empty
                    <div class="list-group-item text-center text-muted">
                        <i class="fa fa-users fa-2x mb-2"></i>
                        <p>No hay propietarios con teléfono registrado</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Fix for Modal Z-Index issues: Append to body
        $('#crearNoticiaModal').appendTo("body");
        $('#detalleNoticiaModal').appendTo("body");
        $('#whatsappModal').appendTo("body");

        // Preview de imagen al seleccionar
        $('#imagenNoticia').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImg').attr('src', e.target.result);
                    $('#previewImagen').show();
                }
                reader.readAsDataURL(file);
                $('.custom-file-label').text(file.name);
            }
        });

        // Ver Noticia Completa
        $('.verNoticiaCompleta').on('click', function() {
            const titulo = $(this).data('titulo');
            const contenido = $(this).data('contenido');
            const fecha = $(this).data('fecha');
            const imagen = $(this).data('imagen');

            $('#detalleTitulo').html('<i class="fa fa-newspaper-o mr-2"></i>' + titulo);
            $('#detalleContenido').html(contenido.replace(/\n/g, '<br>'));
            $('#detalleFecha').text(fecha);

            // Guardar datos para compartir desde el modal de detalle
            $('#detalleNoticiaModal').data('titulo', titulo);
            $('#detalleNoticiaModal').data('contenido', contenido);

            if(imagen) {
                $('#detalleImagen').attr('src', imagen).removeClass('d-none');
            } else {
                $('#detalleImagen').addClass('d-none');
            }

            $('#detalleNoticiaModal').modal('show');
        });

        // WhatsApp Share Logic
        let currentTitle = '';
        let currentBody = '';

        $('.compartirWhatsapp').on('click', function() {
            currentTitle = $(this).data('titulo');
            currentBody = $(this).data('contenido');
            $('#whatsappModal').modal('show');
        });

        // Compartir desde modal de detalle
        $('.compartirWhatsappDesdeDetalle').on('click', function() {
            currentTitle = $('#detalleNoticiaModal').data('titulo');
            currentBody = $('#detalleNoticiaModal').data('contenido');
            $('#detalleNoticiaModal').modal('hide');
            setTimeout(function() {
                $('#whatsappModal').modal('show');
            }, 300);
        });

        // Send Button Click
        $('.btn-send-wa').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let phone = $(this).data('phone');
            // Clean phone number (remove non-digits)
            phone = phone.toString().replace(/\D/g,'');

            // Text format: *TITULO* \n\n CONTENIDO
            let text = "*" + currentTitle + "*\n\n" + currentBody;
            let encodedText = encodeURIComponent(text);

            let url = `https://wa.me/${phone}?text=${encodedText}`;
            window.open(url, '_blank');
        });

        // Search Filter
        $("#searchVecino").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#listaVecinos .vecino-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Limpiar formulario al cerrar modal
        $('#crearNoticiaModal').on('hidden.bs.modal', function () {
            $('#formCrearNoticia')[0].reset();
            $('#previewImagen').hide();
            $('.custom-file-label').text('Seleccionar Imagen');
        });

        // Mostrar mensaje de éxito si existe
        @if(session('success'))
            setTimeout(function() {
                $('#crearNoticiaModal').modal('show');
            }, 100);
        @endif
    });
</script>
@endsection

