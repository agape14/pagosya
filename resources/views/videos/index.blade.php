{{-- Extends layout --}}
@extends('layout.fullwidth')



{{-- Content --}}
@section('content')
	<div class="col-md-10">
      <div class="authincation-content">
          <div class="row no-gutters">
              <div class="col-xl-12">
                <div class="text-center">
                    <a href="{{ route('showlogin') }}" class="btn btn-primary btn-block">Regresar</a>
                </div>
                    {{-- Blade Template --}}
                    <div class="container">
                        <h5>Video Tutoriales:</h5>
                        <ul>
                            <li>A continuación podrá visualizar los videos tutoriales del sistema PAGOSYA.</li>
                            <li>Seleccione el video que desea reproducir.</li>
                        </ul>
                        <hr>

                        <ul class="list-unstyled video-list-thumbs row">
                            @foreach($videos as $video)
                                <li class="col-lg-12 col-sm-12 col-xs-12  mb-5">
                                    {{-- Obtener la ID del video de la URL --}}
                                    @php
                                        // Extraer la ID de YouTube del enlace
                                        $videoId = last(explode('/', $video->video_link)); // Tomamos lo que sigue después de / en el link de YouTube
                                        $embedUrl = "https://www.youtube.com/embed/{$videoId}"; // URL en formato embed
                                    @endphp

                                    {{-- Mostrar el video como un iframe --}}
                                    <div class="video-thumbnail">
                                        <iframe width="100%" height="500" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <h2>{{ $video->titulo }}</h2>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

              </div>
          </div>
      </div>
  </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
     // Usar jQuery para cargar el enlace del video en el modal
     $('#videoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // El enlace que se hizo clic
        var videoUrl = button.data('video'); // Extraer el enlace del video (YouTube URL)

        // Extraer el video ID de la URL de YouTube (para ambos formatos: https://youtu.be/VIDEO_ID y https://www.youtube.com/watch?v=VIDEO_ID)
        var videoId;
        if (videoUrl.indexOf('youtu.be') !== -1) {
            videoId = videoUrl.split('youtu.be/')[1]; // Extraer ID del formato https://youtu.be/VIDEO_ID
        } else {
            videoId = videoUrl.split('v=')[1].split('&')[0]; // Extraer ID del formato https://www.youtube.com/watch?v=VIDEO_ID
        }

        // Crear la URL de YouTube en formato embed
        var embedUrl = 'https://www.youtube.com/embed/' + videoId;

        // Establecer el src del iframe con la URL embed
        var modal = $(this);
        modal.find('#videoFrame').attr('src', embedUrl); // Establecer el src del iframe con el formato correcto
    });

    // Limpiar el src del iframe cuando se cierra el modal
    $('#videoModal').on('hidden.bs.modal', function () {
        var modal = $(this);
        modal.find('#videoFrame').attr('src', ''); // Limpia el iframe para que el video no siga reproduciéndose
    });
</script>
@endsection
