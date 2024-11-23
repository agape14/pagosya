{{-- Extends layout --}}
@extends('layout.default')

@section('content')

<div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Interes Bancario</a></li>
        </ol>
    </div>

    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Interes Bancario</h4>
                </div>
                <div class="card-body">
                    <div class="form-head d-flex mb-2 mb-md-2 align-items-start">
                        <div class="input-group search-area d-inline-flex"></div>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#AddInteresBancarioModal" class="btn btn-primary ml-auto">+ Nueva Interes Bancario</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblInteresBancario" class="table table-striped table-condensed flip-content">
                            <thead>
                                <tr>
                                    <th>Banco</th>
                                    <th>Saldo Final</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Estado</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="AddInteresBancarioModal" tabindex="-1" aria-labelledby="AddInteresBancarioModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('addintbancario') }}"  id="interesBancarioForm">
                                @csrf
                                <input type="hidden" name="id" id="intBancarioId">
                                <div class="modal-header">
                                <h4 class="modal-title" id="AddInteresBancarioModalLabel-1">Agregar Interes Bancario</h4>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <label for="banco_id">Banco:</label>
                                    <select name="banco_id" id="banco_id" class="form-control">
                                        @foreach ($bancos as $banco)
                                            <option value="{{ $banco->id }}" >
                                                {{ $banco->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label d-block mb-2">Saldo Final</label>
                                    <input type="text" class="form-control w-100 mb-3" placeholder="Saldo Final" name="saldo_final" id="saldo_final">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="mes">Mes</label>
                                                <select class="form-control" id="cbxMes" name="mes" >
                                                    <option value="0">Seleccione un mes</option>
                                                    @foreach ($meses as $mes)
                                                        <option value="{{ $mes->mes }}">{{ $mes->nombremes }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="anio">Año</label>
                                                <select class="form-control" id="cbxAnio" name="anio" >
                                                    <option value="0">Seleccione un año</option>
                                                    @foreach ($anios as $anio)
                                                        <option value="{{ $anio->anio }}">{{ $anio->anio }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
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
            $('#tblInteresBancario').DataTable({
                processing: true,
                serverSide: true,
                language: {url: '/datatables/spanish.json'},
                ajax: '{{ route('intbancario_get') }}',
                columns: [
                    { data: 'banco_nombre', name: 'banco_nombre' },
                    { data: 'saldo_final', name: 'saldo_final' },
                    { data: 'nombre_mes', name: 'nombre_mes' },
                    { data: 'anio', name: 'anio' },
                    { data: 'activo', name: 'activo' },
                    { data: 'action', name: 'action' },
                ]
            });

            $('#tblInteresBancario').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.get('/intbancario/get/' + id, function(data) {
                    $('#intBancarioId').val(data.id);
                    $('#saldo_final').val(data.saldo_final);
                    $('#AddInteresBancarioModalLabel-1').text('Editar Interes Bancario');
                    $('#interesBancarioForm').attr('action', '/editintbancario/' + id).attr('method', 'POST');
                    $('#interesBancarioForm').append('<input type="hidden" name="_method" value="PUT">');
                    $('#AddInteresBancarioModal').modal('show');
                });
            });

            $('#AddInteresBancarioModal').on('hidden.bs.modal', function () {
                $('#intBancarioId').val('');
                $('#saldo_final').val('');
                $('#AddInteresBancarioModalLabel-1').text('Agregar Interes Bancario');
                $('#interesBancarioForm').attr('action', '{{ route('addintbancario') }}').attr('method', 'POST');
                $('#interesBancarioForm input[name="_method"]').remove();
            });

            $('#tblInteresBancario').on('click', '.deleteBtn', function() {
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
                            url: '/intbancario/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal("Eliminado!", response.success, "success")
                                $('#tblInteresBancario').DataTable().ajax.reload();
                            }
                        });

                    }else{
                        swal("Cancelado!", "Se cancelo la accion", "error")
                    }
                })
            });

            $('#interesBancarioForm').on('submit', function(e) {
                e.preventDefault();
                var formAction = $(this).attr('action');
                var formMethod = $(this).attr('method');
                var formData = $(this).serialize();

                $.ajax({
                    url: formAction,
                    type: formMethod,
                    data: formData,
                    success: function(response) {
                        $('#AddInteresBancarioModal').modal('hide');
                        swal("Registro Correcto!", response.success, "success")
                        $('#tblInteresBancario').DataTable().ajax.reload(null, false);
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
