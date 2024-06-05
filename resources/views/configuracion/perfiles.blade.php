{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Configuracion</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Perfiles</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Perfiles</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#AddPerfileModal" class="btn btn-primary ml-auto">+ Nueva Perfile</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblPerfiles" class="table table-striped table-condensed flip-content">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Perfile</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="AddPerfileModal" tabindex="-1" aria-labelledby="AddPerfileModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">{{--action="{{ route('addPerfile') }}"--}}
                            <form method="POST"   id="PerfileForm">
                                @csrf
                                <input type="hidden" name="id" id="PerfileId">
                                <div class="modal-header">
                                <h4 class="modal-title" id="AddPerfileModalLabel-1">Agregar Nueva Perfile</h4>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label d-block mb-2">Nombre Perfile</label>
                                    <input type="text" class="form-control w-100 mb-3" placeholder="Nombre Perfile" name="nombre_Perfile" id="nombrePerfile">
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
    {{--<script type="module">
        $(document).ready(function() {
            $('#tblPerfiles').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('Perfiles_get') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nombre_Perfile', name: 'nombre_Perfile' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#tblPerfiles').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('/Perfiles/get/' + id, function(data) {
                    $('#PerfileId').val(data.id);
                    $('#nombrePerfile').val(data.nombre_Perfile);
                    $('#AddPerfileModalLabel-1').text('Editar Perfile');
                    $('#PerfileForm').attr('action', '/editPerfile/' + id).attr('method', 'POST');
                    $('#PerfileForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#AddPerfileModal').modal('show');
                });
            });

            $('#AddPerfileModal').on('hidden.bs.modal', function () {
                $('#PerfileId').val('');
                $('#nombrePerfile').val('');
                $('#AddPerfileModalLabel-1').text('Agregar Nueva Perfile');
                $('#PerfileForm').attr('action', '{{ route('addPerfile') }}').attr('method', 'POST');
                $('#PerfileForm input[name="_method"]').remove();
            });

            $('#tblPerfiles').on('click', '.deleteBtn', function() {
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
                            url: '/Perfiles/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#tblPerfiles').DataTable().ajax.reload();
                            }
                        });
                        
                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });

            $('#PerfileForm').on('submit', function(e) {
                e.preventDefault();
                var formAction = $(this).attr('action');
                var formMethod = $(this).attr('method');
                var formData = $(this).serialize();

                $.ajax({
                    url: formAction,
                    type: formMethod,
                    data: formData,
                    success: function(response) {
                        $('#AddPerfileModal').modal('hide');
                        swal("Registro Correcto!", response.success, "success")
                        $('#tblPerfiles').DataTable().ajax.reload(null, false);
                    },
                    error: function(response) {
                        swal("Error!", response, "error")
                        console.error(response);
                    }
                });
            });

            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>--}}