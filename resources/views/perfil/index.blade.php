{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
            <!-- row -->
			<div class="container-fluid">
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Configuracion</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Mi Cuenta</a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="profile card card-body px-3 pt-3 pb-0">
                            <div class="profile-head">
                                <div class="photo-content">
                                    <div class="p-4 bgl-primary"></div>
                                </div>
                                <div class="profile-info">
									<div class="profile-photo">
										<img src="{{ asset('images/profile/user.png') }}" class="img-fluid rounded-circle" alt="">
									</div>
									<div class="profile-details">
										<div class="profile-name px-3 pt-2">
											<h4 class="text-primary mb-0">{{ $usuario->nombres_completos }}</h4>
											<p>{{ $usuario->perfil->nombre_perfil }}</p>
										</div>
										<div class="profile-email px-2 pt-2">
											<h4 class="text-muted mb-0">{{ $usuario->correo_electronico }}</h4>
											<p>Correo</p>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="profile-tab">
                                    <div class="custom-tab-1">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a href="#profile-settings" data-toggle="tab" class="nav-link  active show">Configuracion</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="profile-settings" class="tab-pane fade active show">
                                                <div class="pt-3">
                                                    <div class="settings-form">
                                                        <h4 class="text-primary">Configuracion de la cuenta</h4>
                                                        <form method="POST" action="{{ url('/updusuario/' . $usuario->id) }}" >
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" id="id" value="{{ $usuario->id }}">
                                                            <div class="form-group">
                                                                <label>Nombres Completos</label>
                                                                <input type="text" placeholder="Correo" class="form-control" name="nombres_completos" value="{{ $usuario->nombres_completos }}">
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label>Usuario</label>
                                                                    <input type="text" placeholder="Usuario" class="form-control" name="usuario" value="{{ $usuario->usuario }}" readonly>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>Password</label>
                                                                    <input type="password" placeholder="Password" class="form-control" name="contrasenia">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Correo</label>
                                                                <input type="email" placeholder="Correo" class="form-control" name="correo_electronico" value="{{ $usuario->correo_electronico }}">
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label>Celular</label>
                                                                    <input type="text" class="form-control" placeholder="Celular" name="telefono" value="{{ $usuario->telefono }}" maxlength="9">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label>Tipo</label>
                                                                    <select class="form-control" id="cbxTipo" disabled>
                                                                        <option selected="">Seleccionar...</option>
                                                                        @foreach ($perfiles as $perfil)
                                                                            <option value="{{ $perfil->id }}" {{ $perfil->id == $usuario->id_perfil ? 'selected' : '' }}>
                                                                                {{ $perfil->nombre_perfil }}
                                                                            </option>
                                                                        @endforeach
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            {{--<div class="form-group">
                                                                <div class="custom-control custom-checkbox">
																	<input type="checkbox" class="custom-control-input" id="gridCheck">
																	<label class="custom-control-label" for="gridCheck"> Check me out</label>
																</div>
                                                            </div>--}}
                                                            <button class="btn btn-primary" type="submit">Actualizar Datos</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
@endsection			