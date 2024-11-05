{{-- Extends layout --}}
@extends('layout.fullwidth')



{{-- Content --}}
@section('content')
	<div class="col-md-6">
      <div class="authincation-content">
          <div class="row no-gutters">
              <div class="col-xl-12">
                  <div class="auth-form">
                        <div class="row mt-4 mb-4 d-flex justify-content-center align-items-center">
                            @if(!empty($logo))
                                <img class="logo-abbr" src="{{ asset($logo) }}" alt="">
                            @else
                                <img class="logo-abbr" src="{{ asset('images/logo.png') }}" alt="">
                            @endif
                            @if(!empty($logoText))
                                <img class="logo-compact" src="{{ asset($logoText) }}" alt="">
                                <img src="{{ asset($logoText) }}" alt="">
                            @else
                                <img class="logo-compact" src="{{ asset('images/logo-text.png') }}" alt="">
                            @endif
                        </div>
                        <!-- Mostrar nombre de torre en la esquina superior derecha -->
                        <div class="nombre-torre text-primary">
                            Torre: {{ $torre_trabajo->nombre_torre }}
                        </div>
                        @if ($errors->any())
                            <div>
                                <ul>
                                    @foreach ($errors->all() as $error)

                                        <div class="alert alert-danger alert-dismissible fade show">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            <strong>Error!</strong> {{ $error }}
                                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                            </button>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('iniciarsesion') }}">
                            @csrf <!-- Agrega el token CSRF para protección contra CSRF -->

                            <div class="form-group">
                                <label class="mb-1"><strong>Usuario</strong></label>
                                <input type="text" class="form-control" name="usuario" required>
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>Contraseña</strong></label>
                                <input type="password" class="form-control" name="contrasenia" required>
                            </div>
                            <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox ml-1">
                                        <input type="checkbox" class="custom-control-input" id="basic_checkbox_1">
                                        <label class="custom-control-label" for="basic_checkbox_1">Recordar contraseña</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="{!! url('/page-forgot-password'); !!}">Olvide contraseña?</a>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                            </div>
                        </form>

                      <div class="new-account mt-3">
                          <p>¿No tienes cuenta? <a class="text-primary" href="{!! url('/page-register'); !!}">Registrar</a></p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection
