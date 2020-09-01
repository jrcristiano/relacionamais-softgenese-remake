<header class="sgi-content-header d-flex align-items-center">
    <button id="sgi-mobile-menu" class="btn btn btn-primary mr-2 rounded-0">
        <i class="fas fa-bars"></i>
    </button>
    <h2 class="sgi-content-title">{{ $title }}</h2>

    @php
        $pedidoId = $id ?? null;
    @endphp
    <a class="btn btn-primary sgi-btn-bold ml-auto mt-2" href="{{ route($route) }}">
        <i class="{{ $icon }}"></i> {{ $buttonTitle }}
    </a>
</header>
