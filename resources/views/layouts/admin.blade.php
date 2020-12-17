<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @stack('styles')

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('/imgs/icon.png') }}" type="image/png">

    @stack('styles')
    @stack('show')

    <script src="https://kit.fontawesome.com/38fb0ce5b2.js"></script>
</head>
<body>
    @guest
        @include('components.navbar')
    @endguest

    <div id="app">
        <div class="p-0">
            <div id="new-loading" class="position-absolute w-100 d-none" style="z-index:99; top: 0;">
                <div class="w-100 vh-100 bg-white d-flex justify-content-center align-items-center flex-wrap" style="z-index:9;">

                    <div width="300" height="300">
                        <div class="d-flex flex-wrap">
                            <img width="200" height="105" src="{{ asset('/imgs/logo.png') }}" />
                        </div><br>

                        <div class="p-2 bg-white d-flex justify-content-center">
                            <div class="spinner-border text-primary ml-1 mr-1 mb-1" role="status">
                                <span style="font-size: 28px;" class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </div>

    @guest
        <div class="container-fluid position-absolute sgi-footer">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 p-3 sgi-copy-text">
                    <span class="sgi-copy font-weight-bold">Todos os direitos reservados ©
                        <strong>
                            <a href="{{ route('login') }}">
                                SoftGenese Inc.
                            </a>
                        </strong>
                        {{ now()->year }}
                    </span>
                </div>
                <div class="col-lg-6 d-flex justify-content-around p-3">
                    <a class="sgi-copy font-weight-bold" href="">Sobre nós</a>

                    <a class="sgi-copy font-weight-bold" href="">Termos de uso</a>

                    <a class="sgi-copy font-weight-bold" href="">Perguntas frequentes</a>

                    <a class="sgi-copy font-weight-bold" href="">Fale conosco</a>
                </div>
            </div>
        </div>
    @endguest

    <script src="{{ asset('/js/manifest.js') }}"></script>
    <script src="{{ asset('/js/vendor.js') }}"></script>
    <script src="{{ asset('/js/admin.js') }}"></script>
    <script src="{{ asset('/js/my-app-and-helpers.js') }}"></script>

    @if(\Request::get('tipo_premiacao') == null)
        <script src="{{ asset('/js/shipments/confirm.js') }}"></script>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    @stack('scripts')

</body>
</html>
