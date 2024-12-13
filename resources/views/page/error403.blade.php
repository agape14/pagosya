{{-- Extends layout --}}
@extends('layout.fullwidth')



{{-- Content --}}
@section('content')
	<div class="col-md-5">
      <div class="form-input-content text-center error-page">
          <h1 class="error-text  font-weight-bold">403</h1>
          <h4><i class="fa fa-times-circle text-danger"></i> ¡Error prohibido!</h4>
          <p>No tienes permiso para ver este recurso.</p>
          <div>
              <a class="btn btn-primary" href="{!! url('/panel'); !!}">Regresar al Inicio</a>
          </div>
      </div>
  </div>
@endsection
