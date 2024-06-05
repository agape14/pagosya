{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Tipos Concepto</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tipos Concepto</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                    </div>
                    <div class="table-responsive">
                        <table id="tblTiposConcepto" class="table table-striped table-condensed flip-content">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>TiposConcepto</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
    <script type="module">
        $(document).ready(function() {
            $('#tblTiposConcepto').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('tipoconceptos_get') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'tipo_concepto', name: 'tipo_concepto' },
                ]
            });
            /*
            $('#tblTiposConcepto').on('click', '.editBtn', function() {
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
            */
        });
    </script>
