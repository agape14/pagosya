{{-- Extends layout --}}
@extends('layout.fullwidth')



{{-- Content --}}
@section('content')
	<div class="col-md-5">
      <div class="form-input-content text-center error-page">
          <h1 class="error-text font-weight-bold">400</h1>
          <h4><i class="fa fa-thumbs-down text-danger"></i> Solicitud incorrecta</h4>
          <p>Su solicitud resultó en un error</p>
        <div>
              <a class="btn btn-primary" href="{!! url('/panel'); !!}">Regresar al Inicio</a>
          </div>
      </div>
  </div>
@endsection
