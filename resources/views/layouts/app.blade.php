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

                @yield('content')

            </div>

        </div>

        @include('partials.footer')
        @if ($errors->any())

            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    const primerCampoInvalido =
                        document.querySelector('.is-invalid');

                    if (!primerCampoInvalido) {
                        return;
                    }

                    setTimeout(function () {

                        primerCampoInvalido.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        primerCampoInvalido.focus({
                            preventScroll: true
                        });

                    }, 100);

                });
            </script>

        @endif
</body>
</html>