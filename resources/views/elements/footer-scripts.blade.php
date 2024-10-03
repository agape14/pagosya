@if(!empty(config('dz.public.global.js')))
	@foreach(config('dz.public.global.js') as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
	@endforeach
@endif
@if(!empty(config('dz.public.pagelevel.js.'.$action)))
	@foreach(config('dz.public.pagelevel.js.'.$action) as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
	@endforeach
@endif
	<script src="{{ asset('js/custom.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/deznav-init.js') }}" type="text/javascript"></script>
<!--	{{-- Education Theme JS --}}-->
 @if(!empty(config('dz.public.education.pagelevel.js.'.$action)))
	@foreach(config('dz.public.education.pagelevel.js.'.$action) as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
	@endforeach
@endif
<script>

// Función para verificar el tamaño de la pantalla y controlar la visibilidad del sidebar
function handleSidebarVisibility() {
    if (window.innerWidth >= 769) {
        // Si la pantalla es grande (>= 769px), mostrar el sidebar
        document.getElementById('main-wrapper').classList.add('sidebar-open');
    } else {
        // Si la pantalla es pequeña (< 769px), ocultar el sidebar
        document.getElementById('main-wrapper').classList.remove('sidebar-open');
    }
}

// Ejecutar la función al cargar la página
handleSidebarVisibility();

// Ejecutar la función cada vez que se redimensiona la ventana
window.addEventListener('resize', function() {
    handleSidebarVisibility();
});

// Control para abrir/cerrar el sidebar en pantallas pequeñas
document.querySelector('.nav-control').addEventListener('click', function(event) {
    event.stopPropagation(); // Evitar que el clic en el botón cierre el menú inmediatamente
    document.getElementById('main-wrapper').classList.toggle('sidebar-open');
});

// Contraer el sidebar al hacer clic fuera de él en pantallas pequeñas
document.addEventListener('click', function(event) {
    var isClickInside = document.querySelector('.deznav').contains(event.target) ||
                        document.querySelector('.nav-control').contains(event.target);

    // Si el clic no fue en el sidebar ni en el botón del menú, ocultar el sidebar solo en pantallas pequeñas
    if (!isClickInside && window.innerWidth < 769) {
        document.getElementById('main-wrapper').classList.remove('sidebar-open');
    }
});

</script>
