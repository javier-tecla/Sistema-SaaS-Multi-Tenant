@extends('tenant.tienda.layout')

@section('title', $producto->nombre . ' - ' . ($ajuste->nombre ?? (tenant('name') ?? 'Tienda Oficial')))

@section('content')
    @php
        $divisa = $ajuste->divisa ?? '$';
        $fotoPrincipal = $producto->foto_principal;
        $imgPrincipalUrl = $fotoPrincipal ? tenant_asset($fotoPrincipal->ruta) : '';
        $telLimpio = !empty($ajuste->telefono) ? preg_replace('/[^0-9]/', '', $ajuste->telefono) : '';
        $nombreTienda = $ajuste->nombre ?? (tenant('name') ?? 'la tienda');
        $mensajeWa =
            "¡Hola {$nombreTienda}! Deseo comprar este producto:\n\n" .
            "🛍️ *{$producto->nombre}*\n" .
            ($producto->codigo ? "🏷️ *Código SKU:* {$producto->codigo}\n" : '') .
            "💰 *Precio:* {$divisa} " .
            number_format($producto->precio_venta, 2) .
            "\n\n" .
            '¿Cómo podemos coordinar el pago y envío?';
        $linkWa = "https://wa.me/{$telLimpio}?text=" . urlencode($mensajeWa);
    @endphp

    <div class="container py-2">

        <!-- 1. Breadcrumbs con Alto Contraste -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb breadcrumb-custom mb-0 d-flex align-items-center">
                <li class="breadcrumb-item">
                    <a href="{{ route('tienda.index') }}" class="text-decoration-none text-primary fw-semibold">
                        <i class="bx bx-home-alt me-1"></i> Catálogo
                    </a>
                </li>
                @if ($producto->categoria)
                    <li class="breadcrumb-item">
                        <a href="{{ route('tienda.index', ['categoria' => $producto->categoria->slug]) }}"
                            class="text-decoration-none text-secondary fw-semibold">
                            {{ $producto->categoria->nombre }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active text-dark fw-bold text-truncate" style="max-width: 320px;"
                    aria-current="page">
                    {{ $producto->nombre }}
                </li>
            </ol>
        </nav>

        <!-- 2. Ficha Principal del Producto (Card con Elevación y Bordes Nítidos) -->
        <div class="product-detail-card p-4 p-lg-5 mb-5">
            <div class="row g-4 g-lg-5 align-items-start">

                <!-- Columna Izquierda: Galería de Fotos -->
                <div class="col-lg-6">
                    <div class="position-sticky" style="top: 100px;">
                        <!-- Visor de Foto Principal -->
                        <div class="main-img-box mb-3">
                            @if ($fotoPrincipal)
                                <img id="main-public-img" src="{{ tenant_asset($fotoPrincipal->ruta) }}"
                                    alt="{{ $producto->nombre }}" class="img-fluid rounded-3">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bx bx-image display-1 text-secondary"></i>
                                    <p class="mb-0 fw-semibold">Sin foto registrada</p>
                                </div>
                            @endif
                        </div>

                        <!-- Miniaturas de la Galería -->
                        @if ($producto->imagenes->count() > 1)
                            <div class="d-flex gap-2 overflow-auto pb-2 justify-content-center"
                                style="scrollbar-width: thin;">
                                @foreach ($producto->imagenes as $img)
                                    <div class="thumbnail-click {{ $img->es_principal ? 'active-thumb' : '' }}"
                                        onclick="document.getElementById('main-public-img').src = '{{ tenant_asset($img->ruta) }}'; document.querySelectorAll('.thumbnail-click').forEach(e => e.classList.remove('active-thumb')); this.classList.add('active-thumb');">
                                        <img src="{{ tenant_asset($img->ruta) }}" alt="Miniatura"
                                            class="w-100 h-100 rounded-2" style="object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block text-center mt-2 fw-semibold" style="font-size: 0.8rem;">
                                <i class="bx bx-pointer me-1"></i> Toca cualquier miniatura para ampliarla
                            </small>
                        @endif
                    </div>
                </div>

                <!-- Columna Derecha: Detalles, Precio y Botón de Compra -->
                <div class="col-lg-6 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Badges Superiores -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            @if ($producto->categoria)
                                <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill"
                                    style="font-size: 0.82rem;">
                                    <i class="bx bx-category-alt me-1"></i> {{ $producto->categoria->nombre }}
                                </span>
                            @endif
                            @if ($producto->codigo)
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-monospace"
                                    style="font-size: 0.8rem;">
                                    SKU: <strong>{{ $producto->codigo }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Título del Producto -->
                        <h1 class="h2 fw-extrabold text-dark mb-3">
                            {{ $producto->nombre }}
                        </h1>

                        <!-- Indicador de Stock con Badge -->
                        <div class="mb-4">
                            @if ($producto->stock > 5)
                                <div class="badge-status-pill d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border"
                                    style="background: #f0fdf4; color: #166534; border-color: #bbf7d0 !important; font-size: 0.88rem;">
                                    <i class="bx bx-check-circle fs-5 text-success"></i>
                                    <span>Disponible en Stock ({{ $producto->stock }} unidades listas para envío)</span>
                                </div>
                            @elseif ($producto->stock > 0)
                                <div class="badge-status-pill d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border"
                                    style="background: #fffbeb; color: #92400e; border-color: #fde68a !important; font-size: 0.88rem;">
                                    <i class="bx bx-time-five fs-5 text-warning"></i>
                                    <span>¡Pocas unidades disponibles! (Solo quedan {{ $producto->stock }})</span>
                                </div>
                            @else
                                <div class="badge-status-pill d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border"
                                    style="background: #fef2f2; color: #991b1b; border-color: #fecaca !important; font-size: 0.88rem;">
                                    <i class="bx bx-x-circle fs-5 text-danger"></i>
                                    <span>Agotado temporalmente</span>
                                </div>
                            @endif
                        </div>

                        <!-- 3. Caja de Precio de Alto Impacto (Fondo Oscuro Premium) -->
                        <div class="price-highlight-box mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-light text-opacity-75 small text-uppercase fw-bold"
                                    style="letter-spacing: 1px;">
                                    Precio Oficial de Venta
                                </span>
                                <span class="price-currency-tag">
                                    {{ $divisa }}
                                </span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="text-white-50 fs-4 fw-bold">{{ $divisa }}</span>
                                <span class="display-5 fw-extrabold text-white lh-1">
                                    {{ number_format($producto->precio_venta, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Selector de Cantidad -->
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3 border">
                            <label class="fw-bold text-dark small mb-0">Seleccionar Cantidad:</label>
                            <div class="qty-control-group p-1" style="background: #e2e8f0;">
                                <button type="button" class="qty-btn" style="width: 32px; height: 32px;"
                                    onclick="changeDetailQty(-1)">-</button>
                                <span id="detailQty" class="qty-number fs-6 px-2">1</span>
                                <button type="button" class="qty-btn" style="width: 32px; height: 32px;"
                                    onclick="changeDetailQty(1)">+</button>
                            </div>
                        </div>

                        <!-- Descripción con Borde de Acento -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-1">
                                <i class="bx bx-align-left text-primary"></i> Descripción del Producto
                            </h6>
                            <div class="description-box">
                                {{ $producto->descripcion ?? 'Este producto no cuenta con descripción detallada en este momento.' }}
                            </div>
                        </div>

                        <!-- Beneficios de Compra en Grilla -->
                        <div class="row g-2 mb-4">
                            <div class="col-sm-6">
                                <div class="benefit-badge d-flex align-items-center gap-2">
                                    <i class="bx bx-check-shield text-success fs-5"></i>
                                    <span>Producto 100% Original</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="benefit-badge d-flex align-items-center gap-2">
                                    <i class="bx bx-package text-primary fs-5"></i>
                                    <span>Despacho Seguro</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="benefit-badge d-flex align-items-center gap-2">
                                    <i class="bx bx-award text-warning fs-5"></i>
                                    <span>Garantía de Tienda</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="benefit-badge d-flex align-items-center gap-2">
                                    <i class="bx bxl-whatsapp text-success fs-5"></i>
                                    <span>Atención Directa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción y Pedido -->
                    <div class="pt-3 border-top">
                        <!-- Botón Agregar al Carrito -->
                        <button type="button"
                            class="btn btn-primary btn-lg rounded-pill w-100 py-3 fs-5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 mb-2"
                            onclick="tenantCart.add({ id: {{ $producto->id }}, name: '{{ addslashes($producto->nombre) }}', price: {{ $producto->precio_venta }}, code: '{{ $producto->codigo }}', image: '{{ $imgPrincipalUrl }}', slug: '{{ $producto->slug }}' }, parseInt(document.getElementById('detailQty').textContent))">
                            <i class="bx bx-cart-add fs-3"></i>
                            <span>Agregar al Carrito</span>
                        </button>

                        @if (!empty($telLimpio))
                            <a href="{{ $linkWa }}" target="_blank"
                                class="btn btn-whatsapp-pill w-100 justify-content-center py-2 fw-semibold mb-2">
                                <i class="bx bxl-whatsapp fs-4"></i>
                                <span>Comprar Solo Este Producto por WhatsApp</span>
                            </a>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('tienda.index') }}"
                                class="btn btn-light rounded-pill w-100 py-2 fw-bold text-secondary border">
                                <i class="bx bx-arrow-back me-1"></i> Seguir Explorando
                            </a>
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4 py-2"
                                onclick="navigator.clipboard.writeText(window.location.href); Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '¡Enlace copiado!', showConfirmButton: false, timer: 1500 });"
                                title="Copiar enlace">
                                <i class="bx bx-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Productos Relacionados -->
        @if ($relacionados->count() > 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-extrabold text-dark mb-0">
                        <i class="bx bx-layer text-primary me-1"></i> Productos Relacionados
                    </h4>
                    <a href="{{ route('tienda.index', ['categoria' => $producto->categoria->slug ?? '']) }}"
                        class="text-primary fw-bold text-decoration-none small">
                        Ver toda la categoría &rarr;
                    </a>
                </div>

                <div class="row g-3 g-md-4">
                    @foreach ($relacionados as $rel)
                        @php
                            $fotoRel = $rel->foto_principal;
                            $imgRelUrl = $fotoRel ? tenant_asset($fotoRel->ruta) : '';
                        @endphp
                        <div class="col-6 col-md-3">
                            <div class="card card-ecommerce h-100">
                                <div class="img-wrapper">
                                    <a href="{{ route('tienda.producto', $rel->slug) }}" class="d-block w-100 h-100">
                                        @if ($fotoRel)
                                            <img src="{{ tenant_asset($fotoRel->ruta) }}" alt="{{ $rel->nombre }}">
                                        @else
                                            <div
                                                class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-light">
                                                <i class="bx bx-image fs-1 text-secondary"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ route('tienda.producto', $rel->slug) }}"
                                                class="text-dark text-decoration-none d-block text-truncate">
                                                {{ $rel->nombre }}
                                            </a>
                                        </h6>
                                        <span class="fs-5 fw-extrabold text-primary">
                                            {{ $divisa }} {{ number_format($rel->precio_venta, 2) }}
                                        </span>
                                    </div>
                                    <div class="d-grid gap-1 mt-3">
                                        <button type="button" class="btn-card-add-cart"
                                            onclick="tenantCart.add({ id: {{ $rel->id }}, name: '{{ addslashes($rel->nombre) }}', price: {{ $rel->precio_venta }}, code: '{{ $rel->codigo }}', image: '{{ $imgRelUrl }}', slug: '{{ $rel->slug }}' })">
                                            <i class="bx bx-cart-add"></i> Agregar
                                        </button>
                                        <a href="{{ route('tienda.producto', $rel->slug) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-pill py-1 fw-semibold">
                                            Ver Ficha
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            function changeDetailQty(delta) {
                const el = document.getElementById('detailQty');
                let current = parseInt(el.textContent) || 1;
                current = Math.max(1, current + delta);
                el.textContent = current;
            }
        </script>
    @endpush
@endsection