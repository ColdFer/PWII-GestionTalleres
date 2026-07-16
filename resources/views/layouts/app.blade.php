<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion de Talleres</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js']) 

</head>
<body>
        @include('partials.navbar')

        <div class="d-flex">

            @include('partials.sidebar')

            <div class="flex-grow-1 p-4">

                @yield('contenido')

            </div>

        </div>

        @include('partials.footer')
</body>
</html>