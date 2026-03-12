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
    #modalPdf .modal-dialog { max-width: 90%; height: 90vh; }
    #modalPdf .modal-content { height: 100%; }
    #modalPdf .modal-body { padding: 0; height: calc(100% - 120px); overflow: hidden; }
    #modalPdf iframe { width: 100%; height: 100%; border: none; }
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
                        @if($esAdmin)
                        <a href="{{ route('documentos-importantes.ver', $doc->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-eye mr-1"></i> Ver PDF
                        </a>
                        @else
                        <a href="{{ route('documentos-importantes.ver', $doc->id) }}" class="btn btn-outline-primary btn-sm btn-ver-pdf" data-id="{{ $doc->id }}" data-titulo="{{ $doc->titulo }}">
                            <i class="fa fa-eye mr-1"></i> Ver PDF
                        </a>
                        @endif
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
                    <div class="form-group">
                        <label class="font-weight-bold">Archivo PDF <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="archivo_pdf" class="custom-file-input" id="archivoPdf" accept=".pdf" required>
                            <label class="custom-file-label" for="archivoPdf">Seleccionar PDF (máx. 15 MB)</label>
                        </div>
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

{{-- Modal: Ver PDF (para propietarios y opcional para admin) --}}
<div class="modal fade" id="modalPdf" tabindex="-1" role="dialog" aria-labelledby="modalPdfTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 95%; height: 90vh;">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalPdfTitle"><i class="fa fa-file-pdf-o mr-2"></i> PDF</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0" style="height: calc(90vh - 120px);">
                <iframe id="iframePdf" src="" style="width:100%; height:100%; border:none;"></iframe>
            </div>
            <div class="modal-footer">
                <a id="linkDescargarPdf" href="" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-download mr-1"></i> Abrir en nueva pestaña
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
    // Nombre del archivo en input file
    $('#archivoPdf').on('change', function() {
        var name = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').text(name || 'Seleccionar PDF (máx. 15 MB)');
    });

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

    // Propietarios: abrir PDF en modal
    $('.btn-ver-pdf').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var titulo = $(this).data('titulo');
        var url = '{{ url("documentos-importantes") }}/' + id + '/ver';
        $('#modalPdfTitle').html('<i class="fa fa-file-pdf-o mr-2"></i> ' + titulo);
        $('#iframePdf').attr('src', url);
        $('#linkDescargarPdf').attr('href', url);
        $('#modalPdf').modal('show');
    });

    $('#modalPdf').on('hidden.bs.modal', function() {
        $('#iframePdf').attr('src', '');
    });
});
</script>
@endsection
