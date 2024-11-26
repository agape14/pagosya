<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de  {{ config('dz.name') }} | @yield('title', $page_title ?? '')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }
        .footer {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <body>
        <div class="container">
            <!-- <img src="{{ public_path('images/logo-text.png') }}" alt="Logo PagosYA" class="logo"> Asegúrate de tener el logo en 'public/images/logo.png' -->
            @if(!empty($logo))
                <img class="logo-abbr" src="{{ asset($logo) }}" alt="Logo PagosYA">
            @else
                <img class="logo-abbr" src="{{ asset('images/logo-text.png') }}" alt="Logo PagosYA">
            @endif
            <div class="content">
                <h1>Estimado {{ $nombre }} {{ $apellido }}</h1>
                <p>A continuación, se encuentran sus datos de acceso:</p>
                <p><strong>Usuario:</strong> {{ $usuario }}</p>
                <p><strong>Contraseña:</strong> {{ $clave }}</p>
            </div>
            <div class="footer">
                <p>Puedes acceder al sistema usando el siguiente enlace:</p>
                <p>
                    <a href="https://torregsiete.delacruzdev.tech" target="_blank" style="color: #007bff; text-decoration: none;">
                        Ir al sistema de PagosYA
                    </a>
                </p>
                <p>PAGOSYA</p>
                <p>Gracias por usar nuestra plataforma.</p>
            </div>
        </div>
    </body>

</body>
</html>
