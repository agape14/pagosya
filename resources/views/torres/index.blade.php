{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Torres</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Torres</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#AddTorreModal" class="btn btn-primary ml-auto">+ Nueva Torre</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblTorres" class="table table-striped table-condensed flip-content">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Torre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="AddTorreModal" tabindex="-1" aria-labelledby="AddTorreModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('addtorre') }}"  id="torreForm">
                                @csrf
                                <input type="hidden" name="id" id="torreId">
                                <div class="modal-header">
                                <h4 class="modal-title" id="AddTorreModalLabel-1">Agregar Nueva Torre</h4>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label d-block mb-2">Nombre Torre</label>
                                    <input type="text" class="form-control w-100 mb-3" placeholder="Nombre Torre" name="nombre_torre" id="nombreTorre">
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
            $('#tblTorres').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('torres_get') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nombre_torre', name: 'nombre_torre' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#tblTorres').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('/torres/get/' + id, function(data) {
                    $('#torreId').val(data.id);
                    $('#nombreTorre').val(data.nombre_torre);
                    $('#AddTorreModalLabel-1').text('Editar Torre');
                    $('#torreForm').attr('action', '/edittorre/' + id).attr('method', 'POST');
                    $('#torreForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#AddTorreModal').modal('show');
                });
            });

            $('#AddTorreModal').on('hidden.bs.modal', function () {
                $('#torreId').val('');
                $('#nombreTorre').val('');
                $('#AddTorreModalLabel-1').text('Agregar Nueva Torre');
                $('#torreForm').attr('action', '{{ route('addtorre') }}').attr('method', 'POST');
                $('#torreForm input[name="_method"]').remove();
            });

            $('#tblTorres').on('click', '.deleteBtn', function() {
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
                            url: '/torres/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#tblTorres').DataTable().ajax.reload();
                            }
                        });
                        
                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });

            $('#torreForm').on('submit', function(e) {
                e.preventDefault();
                var formAction = $(this).attr('action');
                var formMethod = $(this).attr('method');
                var formData = $(this).serialize();

                $.ajax({
                    url: formAction,
                    type: formMethod,
                    data: formData,
                    success: function(response) {
                        $('#AddTorreModal').modal('hide');
                        swal("Registro Correcto!", response.success, "success")
                        $('#tblTorres').DataTable().ajax.reload(null, false);
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
    </script>
