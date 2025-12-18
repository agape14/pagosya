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
                        <div class="btn-toolbar mb-2" role="toolbar">
                            <div class="btn-group btn-group-sm mr-2" role="group">
                                <button type="button" class="btn btn-outline-secondary formato-wa" data-formato="negrita" title="Negrita">
                                    <i class="fa fa-bold"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary formato-wa" data-formato="cursiva" title="Cursiva">
                                    <i class="fa fa-italic"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary formato-wa" data-formato="tachado" title="Tachado">
                                    <i class="fa fa-strikethrough"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary formato-wa" data-formato="mono" title="Monoespaciado">
                                    <i class="fa fa-code"></i>
                                </button>
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary formato-wa" data-formato="lista" title="Lista con viñetas">
                                    <i class="fa fa-list-ul"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary formato-wa" data-formato="numero" title="Lista numerada">
                                    <i class="fa fa-list-ol"></i>
                                </button>
                            </div>
                        </div>
                        <textarea name="contenido" id="contenidoNoticia" class="form-control" rows="6" required placeholder="Escriba el detalle completo de la noticia..."></textarea>
                        <small class="form-text text-muted">
                            <strong>Formato WhatsApp:</strong> *negrita*, _cursiva_, ~tachado~, ```monoespaciado```
                        </small>
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
                <!-- Tabs para Individual/Grupo -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tabIndividual">
                            <i class="fa fa-user mr-1"></i> Individual
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tabGrupo">
                            <i class="fa fa-users mr-1"></i> Grupo
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab Individual -->
                    <div class="tab-pane fade show active" id="tabIndividual">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i>
                            Envíe la noticia a vecinos individuales o a todos a la vez.
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="searchVecino" placeholder="Buscar por nombre, apellido o departamento...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-block" id="btnEnviarTodos">
                                    <i class="fa fa-send mr-1"></i> Enviar a Todos
                                </button>
                            </div>
                        </div>
                        <div class="list-group" id="listaVecinos" style="max-height: 350px; overflow-y: auto;">
                    @forelse($propietarios as $prop)
                        @if($prop->telefono)
                        @php
                            $codigoPais = $prop->codigoPais->codigo_telefono ?? '51';
                        @endphp
                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start vecino-item" data-vecino-id="{{ $prop->id }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fa fa-user mr-2 text-primary"></i>
                                        {{ $prop->nombre }} {{ $prop->apellido }}
                                        <small class="text-muted">({{ $prop->departamento }})</small>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fa fa-phone mr-1"></i>{{ $codigoPais }} {{ $prop->telefono }}
                                    </small>
                                </div>
                                <button class="btn btn-success btn-sm btn-send-wa"
                                    data-phone="{{ $prop->telefono }}"
                                    data-country-code="{{ $codigoPais }}">
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

                    <!-- Tab Grupo -->
                    <div class="tab-pane fade" id="tabGrupo">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle mr-2"></i>
                            Envíe la noticia directamente a un grupo de WhatsApp. Use la ⭐ para marcar favoritos.
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="searchGrupo" placeholder="Buscar grupo...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-primary btn-block" id="btnCargarGrupos">
                                    <i class="fa fa-refresh mr-1"></i> Cargar Grupos
                                </button>
                            </div>
                        </div>
                        <div class="list-group" id="listaGrupos" style="max-height: 350px; overflow-y: auto;">
                            <div class="list-group-item text-center text-muted">
                                <i class="fa fa-users fa-2x mb-2"></i>
                                <p>Haga clic en "Cargar Grupos" para ver los grupos disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        // Configurar toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
        // Fix for Modal Z-Index issues: Append to body
        $('#crearNoticiaModal').appendTo("body");
        $('#detalleNoticiaModal').appendTo("body");
        $('#whatsappModal').appendTo("body");

        // Formato WhatsApp para textarea
        $('.formato-wa').on('click', function() {
            let formato = $(this).data('formato');
            let textarea = document.getElementById('contenidoNoticia');
            let start = textarea.selectionStart;
            let end = textarea.selectionEnd;
            let text = textarea.value;
            let selectedText = text.substring(start, end);
            let newText = '';

            switch(formato) {
                case 'negrita':
                    newText = '*' + (selectedText || 'texto') + '*';
                    break;
                case 'cursiva':
                    newText = '_' + (selectedText || 'texto') + '_';
                    break;
                case 'tachado':
                    newText = '~' + (selectedText || 'texto') + '~';
                    break;
                case 'mono':
                    newText = '```' + (selectedText || 'código') + '```';
                    break;
                case 'lista':
                    if (selectedText) {
                        newText = selectedText.split('\n').map(line => '• ' + line).join('\n');
                    } else {
                        newText = '• Item 1\n• Item 2\n• Item 3';
                    }
                    break;
                case 'numero':
                    if (selectedText) {
                        newText = selectedText.split('\n').map((line, i) => (i+1) + '. ' + line).join('\n');
                    } else {
                        newText = '1. Primero\n2. Segundo\n3. Tercero';
                    }
                    break;
            }

            textarea.value = text.substring(0, start) + newText + text.substring(end);
            textarea.focus();
            textarea.selectionStart = start;
            textarea.selectionEnd = start + newText.length;
        });

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
            $('#detalleNoticiaModal').data('imagen', imagen || '');

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
        let currentImage = '';
        let currentNoticiaId = '';
        const appName = '{{ config("app.name") }}';
        const tipoMensaje = '{{ $page_title ?? "Comunicado" }}';
        const baseUrl = '{{ url("/") }}';
        let gruposFavoritos = JSON.parse(localStorage.getItem('whatsapp_grupos_favoritos') || '[]');

        // Función para convertir URL relativa a absoluta
        function getAbsoluteUrl(url) {
            if (!url) return '';
            if (url.startsWith('http://') || url.startsWith('https://')) {
                return url;
            }
            // Si es relativa, agregar base URL
            return baseUrl + (url.startsWith('/') ? url : '/' + url);
        }

        // Función para resetear botones al abrir modal con nueva noticia
        function resetearBotonesEnvio() {
            // Resetear botones individuales
            $('#listaVecinos .btn-send-wa').each(function() {
                $(this).removeClass('btn-secondary').addClass('btn-success')
                    .html('<i class="fa fa-paper-plane mr-1"></i> Enviar')
                    .prop('disabled', false);
            });
            // Resetear botones de grupos
            $('#listaGrupos .btn-send-grupo').each(function() {
                $(this).removeClass('btn-secondary').addClass('btn-success')
                    .html('<i class="fa fa-paper-plane mr-1"></i> Enviar')
                    .prop('disabled', false);
            });
            // Resetear botón enviar a todos
            $('#btnEnviarTodos').prop('disabled', false).html('<i class="fa fa-send mr-1"></i> Enviar a Todos');
        }

        $('.compartirWhatsapp').on('click', function() {
            let $card = $(this).closest('.noticia-card');
            let noticiaId = $card.data('noticia-id') || $(this).data('titulo');
            // Si es una noticia diferente, resetear botones
            if (currentNoticiaId !== noticiaId) {
                currentNoticiaId = noticiaId;
                resetearBotonesEnvio();
            }
            currentTitle = $(this).data('titulo');
            currentBody = $(this).data('contenido');
            // Buscar imagen en la tarjeta y convertir a URL absoluta
            let $img = $card.find('img.noticia-imagen');
            currentImage = $img.length ? getAbsoluteUrl($img.attr('src')) : '';
            console.log('Imagen encontrada:', currentImage); // Debug
            $('#whatsappModal').modal('show');
        });

        // Compartir desde modal de detalle
        $('.compartirWhatsappDesdeDetalle').on('click', function() {
            let noticiaId = $('#detalleNoticiaModal').data('titulo');
            if (currentNoticiaId !== noticiaId) {
                currentNoticiaId = noticiaId;
                resetearBotonesEnvio();
            }
            currentTitle = $('#detalleNoticiaModal').data('titulo');
            currentBody = $('#detalleNoticiaModal').data('contenido');
            currentImage = getAbsoluteUrl($('#detalleNoticiaModal').data('imagen') || '');
            console.log('Imagen desde detalle:', currentImage); // Debug
            $('#detalleNoticiaModal').modal('hide');
            setTimeout(function() {
                $('#whatsappModal').modal('show');
            }, 300);
        });

        // Send Button Click - Enviar via microservicio
        $('.btn-send-wa').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            let $btn = $(this);
            let phone = $btn.data('phone');
            let countryCode = $btn.data('country-code') || '51';
            let vecinoId = $btn.closest('.vecino-item').data('vecino-id') || null;

            // Clean phone number (remove non-digits) and add country code
            phone = phone.toString().replace(/\D/g,'');
            phone = countryCode + phone;

            // Text format con nombre de app y tipo de módulo
            let text = "📢 *" + appName + " - " + tipoMensaje + "*\n\n";
            text += "*" + currentTitle + "*\n\n";
            text += currentBody + "\n\n";
            text += "_Mensaje automático del sistema_";

            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ route("whatsapp.send") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    phone: phone,
                    message: text,
                    image_url: currentImage,
                    vecino_id: vecinoId,
                    tipo: 'noticia'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Mensaje enviado correctamente');
                        $btn.removeClass('btn-success').addClass('btn-secondary').html('<i class="fa fa-check"></i> Enviado');
                    } else {
                        toastr.error(response.error || 'Error al enviar');
                        $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Enviar');
                        // Fallback a wa.me si falla el servicio
                        if (response.error && response.error.includes('no está conectado')) {
                            if (confirm('WhatsApp no está vinculado. ¿Desea abrir WhatsApp Web manualmente?')) {
                                let encodedText = encodeURIComponent(text);
                                window.open(`https://wa.me/${phone}?text=${encodedText}`, '_blank');
                            }
                        }
                    }
                },
                error: function() {
                    toastr.error('Error de conexión');
                    $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Enviar');
                }
            });
        });

        // Search Filter
        $("#searchVecino").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#listaVecinos .vecino-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Enviar a todos
        $('#btnEnviarTodos').on('click', function() {
            let totalVecinos = $('#listaVecinos .vecino-item').length;
            if (!confirm(`¿Enviar esta noticia a ${totalVecinos} vecinos?\n\nNota: El envío incluye pausas de 5-8 segundos entre mensajes para evitar bloqueos de WhatsApp.`)) return;

            let $btn = $(this);

            // Formato del mensaje con nombre de app y tipo de módulo
            let text = "📢 *" + appName + " - " + tipoMensaje + "*\n\n";
            text += "*" + currentTitle + "*\n\n";
            text += currentBody + "\n\n";
            text += "_Mensaje automático del sistema_";

            let vecinos = [];

            $('#listaVecinos .vecino-item').each(function() {
                let $sendBtn = $(this).find('.btn-send-wa');
                let phone = $sendBtn.data('phone');
                let countryCode = $sendBtn.data('country-code') || '51';
                let vecinoId = $(this).data('vecino-id');
                if (phone) {
                    // Agregar código de país al número
                    let fullPhone = countryCode + phone.toString().replace(/\D/g,'');
                    vecinos.push({ phone: fullPhone, vecino_id: vecinoId });
                }
            });

            if (vecinos.length === 0) {
                toastr.warning('No hay vecinos con teléfono registrado');
                return;
            }

            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Iniciando...');
            toastr.info(`Iniciando envío a ${vecinos.length} vecinos. Por favor espere...`);

            let enviados = 0;
            let fallidos = 0;
            let total = vecinos.length;

            function getRandomDelay() {
                // Delay aleatorio entre 5 y 8 segundos para evitar detección de spam
                return Math.floor(Math.random() * 3000) + 5000;
            }

            function enviarSiguiente(index) {
                if (index >= vecinos.length) {
                    $btn.prop('disabled', false).html('<i class="fa fa-send mr-1"></i> Enviar a Todos');
                    toastr.success(`✅ Proceso completado: ${enviados} enviados, ${fallidos} fallidos`);
                    // Marcar botones como enviados
                    $('#listaVecinos .vecino-item .btn-send-wa').each(function() {
                        $(this).removeClass('btn-success').addClass('btn-secondary').html('<i class="fa fa-check"></i>').prop('disabled', true);
                    });
                    return;
                }

                let vecino = vecinos[index];
                $btn.html(`<i class="fa fa-spinner fa-spin"></i> ${index + 1}/${total}`);

                $.ajax({
                    url: '{{ route("whatsapp.send") }}',
                    method: 'POST',
                    timeout: 120000, // 2 minutos timeout para imágenes
                    data: {
                        _token: '{{ csrf_token() }}',
                        phone: vecino.phone,
                        message: text,
                        image_url: currentImage,
                        vecino_id: vecino.vecino_id,
                        tipo: 'noticia'
                    },
                    success: function(response) {
                        if (response.success) {
                            enviados++;
                        } else {
                            fallidos++;
                            console.log('Error enviando a ' + vecino.phone + ': ' + response.error);
                        }
                    },
                    error: function(xhr) {
                        fallidos++;
                        console.log('Error de conexión enviando a ' + vecino.phone);
                    },
                    complete: function() {
                        let delay = getRandomDelay();
                        $btn.html(`<i class="fa fa-spinner fa-spin"></i> ${index + 1}/${total} (espera ${Math.round(delay/1000)}s)`);
                        setTimeout(function() {
                            enviarSiguiente(index + 1);
                        }, delay);
                    }
                });
            }

            enviarSiguiente(0);
        });

        // Cargar grupos de WhatsApp
        let gruposData = [];

        function renderGrupos(grupos) {
            let html = '';
            if (grupos && grupos.length > 0) {
                // Ordenar: favoritos primero, luego alfabéticamente
                grupos.sort((a, b) => {
                    let aFav = gruposFavoritos.includes(a.id) ? 0 : 1;
                    let bFav = gruposFavoritos.includes(b.id) ? 0 : 1;
                    if (aFav !== bFav) return aFav - bFav;
                    return a.name.localeCompare(b.name);
                });

                grupos.forEach(function(group) {
                    let isFavorito = gruposFavoritos.includes(group.id);
                    let starClass = isFavorito ? 'fa-star text-warning' : 'fa-star-o text-muted';
                    html += `
                        <a href="#" class="list-group-item list-group-item-action grupo-item ${isFavorito ? 'border-warning' : ''}"
                           data-group-id="${group.id}" data-group-name="${group.name}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-link btn-sm p-0 mr-2 btn-favorito" data-group-id="${group.id}" title="Marcar como favorito">
                                        <i class="fa ${starClass}" style="font-size: 1.2rem;"></i>
                                    </button>
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fa fa-users mr-2 text-success"></i>
                                            ${group.name}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fa fa-user mr-1"></i>${group.participants || '?'} participantes
                                        </small>
                                    </div>
                                </div>
                                <button class="btn btn-success btn-sm btn-send-grupo" data-group-id="${group.id}" data-group-name="${group.name}">
                                    <i class="fa fa-paper-plane mr-1"></i> Enviar
                                </button>
                            </div>
                        </a>
                    `;
                });
            } else {
                html = `
                    <div class="list-group-item text-center text-muted">
                        <i class="fa fa-exclamation-circle fa-2x mb-2"></i>
                        <p>No se encontraron grupos</p>
                    </div>
                `;
            }
            $('#listaGrupos').html(html);
        }

        $('#btnCargarGrupos').on('click', function() {
            let $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Cargando...');

            $.get('{{ route("whatsapp.groups") }}', function(response) {
                if (response.success && response.groups) {
                    gruposData = response.groups;
                    renderGrupos(gruposData);
                } else {
                    $('#listaGrupos').html(`
                        <div class="list-group-item text-center text-muted">
                            <i class="fa fa-exclamation-circle fa-2x mb-2"></i>
                            <p>${response.error || 'No se encontraron grupos'}</p>
                        </div>
                    `);
                }
            }).fail(function() {
                $('#listaGrupos').html(`
                    <div class="list-group-item text-center text-danger">
                        <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>Error al cargar grupos</p>
                    </div>
                `);
            }).always(function() {
                $btn.prop('disabled', false).html('<i class="fa fa-refresh mr-1"></i> Cargar Grupos');
            });
        });

        // Buscador de grupos
        $('#searchGrupo').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            if (gruposData.length > 0) {
                let filtrados = gruposData.filter(g => g.name.toLowerCase().includes(value));
                renderGrupos(filtrados);
            }
        });

        // Toggle favorito
        $(document).on('click', '.btn-favorito', function(e) {
            e.preventDefault();
            e.stopPropagation();

            let groupId = $(this).data('group-id');
            let $icon = $(this).find('i');

            if (gruposFavoritos.includes(groupId)) {
                // Quitar de favoritos
                gruposFavoritos = gruposFavoritos.filter(id => id !== groupId);
                $icon.removeClass('fa-star text-warning').addClass('fa-star-o text-muted');
                $(this).closest('.grupo-item').removeClass('border-warning');
            } else {
                // Agregar a favoritos
                gruposFavoritos.push(groupId);
                $icon.removeClass('fa-star-o text-muted').addClass('fa-star text-warning');
                $(this).closest('.grupo-item').addClass('border-warning');
            }

            // Guardar en localStorage
            localStorage.setItem('whatsapp_grupos_favoritos', JSON.stringify(gruposFavoritos));

            // Re-renderizar para reordenar
            let searchValue = $('#searchGrupo').val().toLowerCase();
            let filtrados = searchValue ? gruposData.filter(g => g.name.toLowerCase().includes(searchValue)) : gruposData;
            renderGrupos(filtrados);

            toastr.info(gruposFavoritos.includes(groupId) ? 'Grupo marcado como favorito' : 'Grupo removido de favoritos');
        });

        // Enviar a grupo
        $(document).on('click', '.btn-send-grupo', function(e) {
            e.preventDefault();
            e.stopPropagation();

            let $btn = $(this);
            let groupId = $btn.data('group-id');
            let groupName = $btn.data('group-name');

            if (!confirm(`¿Enviar esta noticia al grupo "${groupName}"?`)) return;

            // Formato del mensaje
            let text = "📢 *" + appName + " - " + tipoMensaje + "*\n\n";
            text += "*" + currentTitle + "*\n\n";
            text += currentBody + "\n\n";
            text += "_Mensaje automático del sistema_";

            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ route("whatsapp.send.group") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    group_id: groupId,
                    message: text,
                    image_url: currentImage,
                    tipo: 'noticia'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Mensaje enviado al grupo correctamente');
                        $btn.removeClass('btn-success').addClass('btn-secondary').html('<i class="fa fa-check"></i> Enviado');
                    } else {
                        toastr.error(response.error || 'Error al enviar');
                        $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Enviar');
                    }
                },
                error: function() {
                    toastr.error('Error de conexión');
                    $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Enviar');
                }
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

