<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Systematic LMS')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="bg-light">

    {{-- NAVBAR --}}
    @include('components.navbar')

    {{-- SIDEBAR MOBILE --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5>Menú</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body p-0">
            @include('components.sidebar')
        </div>
    </div>

    {{-- MAIN --}}
    <main class="container-fluid" style="padding-top: 70px;">
        <div class="row g-0">

            {{-- SIDEBAR DESKTOP --}}
            @unless(View::hasSection('noSidebar'))
                <aside class="col-lg-2 d-none d-lg-block border-end bg-white">
                    <div class="h-100">
                        @include('components.sidebar')
                    </div>
                </aside>
            @endunless

            {{-- CONTENIDO --}}
            <section class="@if(View::hasSection('noSidebar')) col-12 @else col-lg-10 col-12 @endif p-4">

                {{-- ALERTAS --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- CONTENIDO DINÁMICO --}}
                @yield('content')

            </section>

        </div>
    </main>

    {{-- FOOTER --}}
    @include('components.footer')

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>