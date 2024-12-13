{{-- Extends layout --}}
@extends('layout.fullwidth')



{{-- Content --}}
@section('content')
	<div class="col-md-5">
      <div class="form-input-content text-center error-page">
          <h1 class="error-text font-weight-bold">404</h1>
          <h4><i class="fa fa-exclamation-triangle text-warning"></i> ¡La página que estabas buscando no se encuentra!</h4>
          <p>Es posible que haya escrito mal la dirección o que la página se haya movido.</p>
          <div>
              <a class="btn btn-primary" href="{!! url('/panel'); !!}">Regresar al Inicio</a>
          </div>
      </div>
  </div>
@endsection
