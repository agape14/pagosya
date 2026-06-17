{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
<style>
    .doc-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        height: 100%;
        border-left: 4px solid #0d6efd;
    }
    .doc-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .doc-card .card-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .doc-titulo {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .doc-descripcion {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.5;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .doc-tag {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
        margin-top: 0.75rem;
    }
    .doc-acciones {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .doc-icon-header {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    .empty-state-docs {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
    }
    .empty-state-docs .icon-empty {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    #modalPdf .modal-header {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
    }
    #modalPdf .modal-header .modal-title {
        flex: 1;
        min-width: 0;
        margin-right: 1rem;
        padding-right: 0;
    }
    #modalPdf .modal-header .modal-header-actions {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        gap: 0.5rem;
        margin-left: auto;
    }
    #modalPdf .modal-header .close {
        position: static;
        float: none;
        margin: 0;
        padding: 0.25rem 0.5rem;
        opacity: 1;
        line-height: 1;
    }
    #modalPdf .modal-dialog { max-width: 90%; height: 90vh; }
    #modalPdf .modal-content { height: 100%; }
    #modalPdf .modal-body { padding: 0; height: calc(100% - 120px); overflow: hidden; display: flex; flex-direction: column; }
    #modalPdf .pdf-tabs { flex-shrink: 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 0.5rem 1rem 0; overflow-x: auto; white-space: nowrap; }
    #modalPdf .pdf-tabs .nav-link { border-radius: 8px 8px 0 0; color: #475569; font-size: 0.85rem; padding: 0.5rem 1rem; margin-right: 0.25rem; }
    #modalPdf .pdf-tabs .nav-link.active { background: #fff; color: #3730a3; font-weight: 600; border-color: #e2e8f0 #e2e8f0 #fff; }
    #modalPdf .pdf-viewer-wrap { flex: 1; min-height: 0; position: relative; }
    #modalPdf iframe { width: 100%; height: 100%; border: none; }
    #modalPdf.modal-fullscreen .modal-dialog {
        max-width: 100%;
        width: 100%;
        height: 100vh;
        margin: 0;
    }
    #modalPdf.modal-fullscreen .modal-content { height: 100vh; border-radius: 0; }
    #modalPdf.modal-fullscreen .modal-body { height: calc(100vh - 120px); }
    .pdf-dropzone {
        border: 2px dashed #c7d2fe;
        border-radius: 12px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .pdf-dropzone:hover,
    .pdf-dropzone.dragover {
        border-color: #6366f1;
        background: #eef2ff;
    }
    .pdf-dropzone .dropzone-icon { font-size: 2.5rem; color: #6366f1; margin-bottom: 0.75rem; }
    .pdf-dropzone .dropzone-text { color: #475569; margin-bottom: 0.25rem; }
    .pdf-dropzone .dropzone-hint { color: #94a3b8; font-size: 0.85rem; }
    .pdf-file-list { margin-top: 1rem; }
    .pdf-file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .pdf-file-item .file-name { color: #334155; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 85%; }
    .pdf-file-item .btn-remove-file { padding: 0.15rem 0.5rem; font-size: 0.8rem; }
</style>

<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Documentos importantes</h4>
                <p class="mb-0">Consulte y gestione los documentos PDF del condominio.</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex align-items-center">
            @if(session('success'))
                <div class="alert alert-success py-2 mb-0 mr-3">
                    <i class="fa fa-check-circle mr-1"></i>{{ session('success') }}
                </div>
            @endif
            @if($esAdmin)
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCargarDocumento">
                <i class="fa fa-upload mr-2"></i> Cargar documento
            </button>
            @endif
        </div>
    </div>

    {{-- Filtro por tag (opcional, para muchos documentos) --}}
    @if($documentos->isNotEmpty() && $documentos->pluck('tag')->filter()->unique()->count() > 1)
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group btn-group-sm flex-wrap" role="group">
                <button type="button" class="btn btn-outline-primary active filtro-tag" data-tag="">Todos</button>
                @foreach($documentos->pluck('tag')->filter()->unique()->sort() as $tag)
                <button type="button" class="btn btn-outline-primary filtro-tag" data-tag="{{ $tag }}">{{ $tag }}</button>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row" id="listadoDocumentos">
        @forelse($documentos as $doc)
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4 doc-item" data-tag="{{ $doc->tag ?? '' }}">
            <div class="card doc-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-2">
                        <div class="doc-icon-header mr-3 flex-shrink-0">
                            <i class="fa fa-file-pdf-o"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h5 class="doc-titulo">{{ $doc->titulo }}</h5>
                            @if($doc->tag)
                            <span class="doc-tag">{{ $doc->tag }}</span>
                            @endif
                        </div>
                    </div>
                    @if($doc->descripcion)
                    <p class="doc-descripcion" title="{{ $doc->descripcion }}">{{ Str::limit($doc->descripcion, 120) }}</p>
                    @else
                    <p class="doc-descripcion text-muted font-italic">Sin descripción</p>
                    @endif
                    <div class="doc-acciones">
                        <button type="button"
                            class="btn btn-outline-primary btn-sm btn-ver-pdf"
                            data-id="{{ $doc->id }}"
                            data-titulo="{{ $doc->titulo }}"
                            data-archivos="{{ ($doc->archivos->isNotEmpty()
                                ? $doc->archivos->map(function ($a) { return ['id' => $a->id, 'nombre' => $a->nombre_archivo]; })
                                : collect([['id' => null, 'nombre' => basename($doc->ruta_pdf ?: 'documento.pdf')]])
                            )->values()->toJson() }}">
                            <i class="fa fa-eye mr-1"></i> Ver PDF
                            @if($doc->archivos->count() > 1)
                            <span class="badge badge-light ml-1">{{ $doc->archivos->count() }}</span>
                            @endif
                        </button>
                        @if($esAdmin)
                        <form action="{{ route('documentos-importantes.destroy', $doc->id) }}" method="POST" class="d-inline form-eliminar-doc">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este documento?');">
                                <i class="fa fa-trash mr-1"></i> Eliminar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state-docs">
                <div class="icon-empty"><i class="fa fa-file-pdf-o"></i></div>
                <h5 class="text-muted mb-2">No hay documentos registrados</h5>
                <p class="text-muted mb-4">Los documentos PDF aparecerán aquí para su consulta.</p>
                @if($esAdmin)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCargarDocumento">
                    <i class="fa fa-upload mr-2"></i> Cargar primer documento
                </button>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal: Cargar documento (solo admin) --}}
@if($esAdmin)
<div class="modal fade" id="modalCargarDocumento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('documentos-importantes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white"><i class="fa fa-upload mr-2"></i> Cargar documento PDF</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required maxlength="255" placeholder="Ej: Reglamento interno">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Descripción (opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="3" maxlength="2000" placeholder="Breve descripción del documento"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tag (opcional)</label>
                        <input type="text" name="tag" class="form-control" maxlength="100" placeholder="Ej: Reglamento, Actas, Contratos (para ordenar/filtrar)">
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Archivo(s) PDF <span class="text-danger">*</span></label>
                        <div class="pdf-dropzone" id="pdfDropzone">
                            <div class="dropzone-icon"><i class="fa fa-cloud-upload"></i></div>
                            <div class="dropzone-text font-weight-bold">Arrastre sus PDF aquí o haga clic para seleccionar</div>
                            <div class="dropzone-hint">Puede cargar varios archivos PDF (máx. 15 MB c/u)</div>
                            <input type="file" name="archivo_pdf[]" id="archivoPdf" accept=".pdf,application/pdf" multiple class="d-none" required>
                        </div>
                        <div class="pdf-file-list" id="pdfFileList"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload mr-1"></i> Cargar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal: Ver PDF --}}
<div class="modal fade" id="modalPdf" tabindex="-1" role="dialog" aria-labelledby="modalPdfTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 95%; height: 90vh;">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalPdfTitle"><i class="fa fa-file-pdf-o mr-2"></i> PDF</h5>
                <div class="modal-header-actions">

                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0" style="height: calc(90vh - 120px);">
                <ul class="nav nav-tabs pdf-tabs d-none" id="pdfTabs" role="tablist"></ul>
                <div class="pdf-viewer-wrap">
                    <iframe id="iframePdf" src="" title="Visor PDF"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <a id="linkDescargarPdf" href="" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-external-link mr-1"></i> Abrir en nueva pestaña
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    var baseUrl = '{{ url("documentos-importantes") }}';
    var selectedFiles = [];
    var pdfInput = document.getElementById('archivoPdf');

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function syncFileInput() {
        if (!pdfInput) return;
        var dt = new DataTransfer();
        selectedFiles.forEach(function(file) { dt.items.add(file); });
        pdfInput.files = dt.files;
        pdfInput.required = selectedFiles.length === 0;
    }

    function renderFileList() {
        var $list = $('#pdfFileList');
        $list.empty();
        if (!selectedFiles.length) return;

        selectedFiles.forEach(function(file, index) {
            var $item = $('<div class="pdf-file-item"></div>');
            $item.append(
                '<span class="file-name"><i class="fa fa-file-pdf-o text-danger mr-2"></i>' +
                $('<span>').text(file.name).html() + ' <small class="text-muted">(' + formatFileSize(file.size) + ')</small></span>'
            );
            $item.append(
                '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-file" data-index="' + index + '">' +
                '<i class="fa fa-times"></i></button>'
            );
            $list.append($item);
        });
    }

    function addPdfFiles(files) {
        var maxSize = 15 * 1024 * 1024;
        Array.from(files).forEach(function(file) {
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                return;
            }
            if (file.size > maxSize) {
                alert('El archivo "' + file.name + '" supera el límite de 15 MB.');
                return;
            }
            var exists = selectedFiles.some(function(f) {
                return f.name === file.name && f.size === file.size;
            });
            if (!exists) {
                selectedFiles.push(file);
            }
        });
        syncFileInput();
        renderFileList();
    }

    if (pdfInput) {
        var $dropzone = $('#pdfDropzone');

        $dropzone.on('click', function(e) {
            if (!$(e.target).closest('.btn-remove-file').length) {
                pdfInput.click();
            }
        });

        pdfInput.addEventListener('change', function() {
            addPdfFiles(this.files);
        });

        $dropzone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        $dropzone.on('dragleave dragend drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        $dropzone.on('drop', function(e) {
            var files = e.originalEvent.dataTransfer.files;
            addPdfFiles(files);
        });

        $('#pdfFileList').on('click', '.btn-remove-file', function(e) {
            e.stopPropagation();
            var index = $(this).data('index');
            selectedFiles.splice(index, 1);
            syncFileInput();
            renderFileList();
        });

        $('#modalCargarDocumento').on('hidden.bs.modal', function() {
            selectedFiles = [];
            syncFileInput();
            renderFileList();
            $(this).find('form')[0].reset();
        });
    }

    // Filtro por tag
    $('.filtro-tag').on('click', function() {
        var tag = $(this).data('tag');
        $('.filtro-tag').removeClass('active');
        $(this).addClass('active');
        $('.doc-item').each(function() {
            var itemTag = $(this).data('tag') || '';
            $(this).toggle(tag === '' || itemTag === tag);
        });
    });

    function buildPdfUrl(docId, archivoId) {
        var url = baseUrl + '/' + docId + '/ver';
        if (archivoId) {
            url += '/' + archivoId;
        }
        return url;
    }

    function truncateName(name, max) {
        if (name.length <= max) return name;
        return name.substring(0, max - 3) + '...';
    }

    function loadPdfInModal(docId, archivoId) {
        var url = buildPdfUrl(docId, archivoId);
        $('#iframePdf').attr('src', url);
        $('#linkDescargarPdf').attr('href', url);
    }

    // Ver PDF en modal con tabs
    $('.btn-ver-pdf').on('click', function() {
        var id = $(this).data('id');
        var titulo = $(this).data('titulo');
        var archivos = $(this).data('archivos') || [];

        if (typeof archivos === 'string') {
            try { archivos = JSON.parse(archivos); } catch (e) { archivos = []; }
        }

        $('#modalPdfTitle').html('<i class="fa fa-file-pdf-o mr-2"></i> ' + titulo);
        $('#modalPdf').removeClass('modal-fullscreen');
        $('#btnFullscreenPdf i').removeClass('fa-compress').addClass('fa-expand');

        var $tabs = $('#pdfTabs');
        $tabs.empty();

        if (archivos.length > 1) {
            $tabs.removeClass('d-none');
            archivos.forEach(function(archivo, index) {
                var tabId = 'pdf-tab-' + archivo.id;
                var label = truncateName(archivo.nombre.replace(/\.pdf$/i, ''), 30);
                var $li = $('<li class="nav-item" role="presentation"></li>');
                var $link = $('<a class="nav-link" data-toggle="tab" href="#" role="tab"></a>')
                    .text(label)
                    .attr('data-archivo-id', archivo.id)
                    .attr('data-doc-id', id);
                if (index === 0) $link.addClass('active');
                $li.append($link);
                $tabs.append($li);
            });
            loadPdfInModal(id, archivos[0].id);
        } else {
            $tabs.addClass('d-none');
            var archivoId = archivos.length ? archivos[0].id : null;
            loadPdfInModal(id, archivoId);
        }

        $('#modalPdf').modal('show');
    });

    $('#pdfTabs').on('click', '.nav-link', function(e) {
        e.preventDefault();
        $('#pdfTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        loadPdfInModal($(this).data('doc-id'), $(this).data('archivo-id'));
    });

    $('#btnFullscreenPdf').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $modal = $('#modalPdf');
        var isFullscreen = $modal.toggleClass('modal-fullscreen').hasClass('modal-fullscreen');
        $(this).find('i').toggleClass('fa-expand', !isFullscreen).toggleClass('fa-compress', isFullscreen);
    });

    $('#modalPdf').on('hidden.bs.modal', function() {
        $('#iframePdf').attr('src', '');
        $('#pdfTabs').empty().addClass('d-none');
        $(this).removeClass('modal-fullscreen');
        $('#btnFullscreenPdf i').removeClass('fa-compress').addClass('fa-expand');
    });
});
</script>
@endsection
