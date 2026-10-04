@extends('tenant.tienda.layout')

@section('title', ($categoriaActual ? $categoriaActual->nombre . ' - ' : '') . ($ajuste->nombre ?? (tenant('name') ??
    'Catálogo Oficial')))

@section('content')
    <div class="container">

        <!-- 1. Gran Carrusel de Productos Destacados (3 Slides con Imágenes) -->
        @php
            $divisa = $ajuste->divisa ?? '$';
            $telLimpio = !empty($ajuste->telefono) ? preg_replace('/[^0-9]/', '', $ajuste->telefono) : '';
            $nombreTienda = $ajuste->nombre ?? (tenant('name') ?? 'la tienda');
            $badgesSlide = [
                ['badge' => '🔥 PRODUCTO DESTACADO', 'color' => 'bg-danger'],
                ['badge' => '⭐ OFERTA DE TEMPORADA', 'color' => 'bg-warning text-dark'],
                ['badge' => '✨ NOVEDAD EXCLUSIVA', 'color' => 'bg-info text-dark'],
            ];
        @endphp

        @if ($carruselProductos->count() > 0)
            <div id="heroStoreCarousel" class="carousel slide carousel-fade hero-banner mb-5 p-0" data-bs-ride="carousel"
                data-bs-interval="5000">
                <!-- Indicadores -->
                <div class="carousel-indicators mb-3">
                    @foreach ($carruselProductos as $index => $item)
                        <button type="button" data-bs-target="#heroStoreCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"
                            style="width: 32px; height: 6px; border-radius: 4px;"></button>
                    @endforeach
                </div>

                <!-- Slides del Carrusel -->
                <div class="carousel-inner p-4 p-md-5">
                    @foreach ($carruselProductos as $index => $item)
                        @php
                            $fotoSlide = $item->foto_principal;
                            $badgeInfo = $badgesSlide[$index % count($badgesSlide)];
                            $mensajeSlideWa =
                                "¡Hola {$nombreTienda}! Vi este producto destacado en el carrusel y deseo comprarlo:\n\n" .
                                "🛍️ *{$item->nombre}*\n" .
                                ($item->codigo ? "🏷️ *Código:* {$item->codigo}\n" : '') .
                                "💰 *Precio:* {$divisa} " .
                                number_format($item->precio_venta, 2) .
                                "\n\n" .
                                '¿Tienen stock disponible?';
                            $linkSlideWa = "https://wa.me/{$telLimpio}?text=" . urlencode($mensajeSlideWa);
                            $imgSlideUrl = $fotoSlide ? tenant_asset($fotoSlide->ruta) : '';
                        @endphp

                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="row align-items-center g-4 position-relative"
                                style="z-index: 2; min-height: 340px;">
                                <!-- Columna Texto y CTA -->
                                <div class="col-lg-7 text-white text-center text-lg-start">
                                    <div
                                        class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-20 px-3 py-1 rounded-pill mb-3">
                                        <span class="badge {{ $badgeInfo['color'] }} rounded-pill fw-bold"
                                            style="font-size: 0.72rem;">
                                            {{ $badgeInfo['badge'] }}
                                        </span>
                                        @if ($item->categoria)
                                            <small class="text-light fw-semibold">{{ $item->categoria->nombre }}</small>
                                        @endif
                                    </div>

                                    <h1 class="display-6 fw-extrabold mb-3 text-white lh-sm">
                                        {{ $item->nombre }}
                                    </h1>

                                    <p class="lead text-light text-opacity-75 mb-4 text-truncate-2"
                                        style="max-width: 560px; font-size: 1rem;">
                                        {{ $item->descripcion ?? 'Artículo de primera calidad disponible con entrega rápida y garantía oficial en nuestra tienda.' }}
                                    </p>

                                    <!-- Precio Destacado en Slide -->
                                    <div
                                        class="d-flex align-items-baseline gap-2 justify-content-center justify-content-lg-start mb-4">
                                        <span class="text-white-50 small">Precio Especial:</span>
                                        <span class="fs-2 fw-extrabold text-warning">
                                            {{ $divisa }} {{ number_format($item->precio_venta, 2) }}
                                        </span>
                                        @if ($item->stock > 0)
                                            <span class="badge rounded-pill px-2 py-1 small ms-2"
                                                style="background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.5);">
                                                <i class="bx bx-check-circle"></i> En Stock
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Botones de Acción del Slide -->
                                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                                        <button type="button"
                                            class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-lg d-inline-flex align-items-center gap-2"
                                            onclick="tenantCart.add({ id: {{ $item->id }}, name: '{{ addslashes($item->nombre) }}', price: {{ $item->precio_venta }}, code: '{{ $item->codigo }}', image: '{{ $imgSlideUrl }}', slug: '{{ $item->slug }}' })">
                                            <i class="bx bx-cart-add fs-4"></i>
                                            <span>Agregar al Carrito</span>
                                        </button>

                                        @if (!empty($telLimpio))
                                            <a href="{{ $linkSlideWa }}" target="_blank"
                                                class="btn btn-whatsapp-pill px-4 py-2 shadow-lg">
                                                <i class="bx bxl-whatsapp fs-4"></i>
                                                <span>WhatsApp</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('tienda.producto', $item->slug) }}"
                                            class="btn btn-light rounded-pill px-4 py-2 fw-semibold text-dark border">
                                            <i class="bx bx-show me-1"></i> Ver Detalle
                                        </a>
                                    </div>
                                </div>

                                <!-- Columna Imagen Destacada del Producto -->
                                <div class="col-lg-5 text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="bg-white p-3 rounded-4 shadow-xl border border-white border-opacity-20 d-inline-flex align-items-center justify-content-center"
                                            style="width: 280px; height: 280px; max-width: 100%;">
                                            @if ($fotoSlide)
                                                <img src="{{ tenant_asset($fotoSlide->ruta) }}" alt="{{ $item->nombre }}"
                                                    class="img-fluid rounded-3"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                            @else
                                                <div class="text-center text-muted">
                                                    <i class="bx bx-image display-1 text-primary"></i>
                                                    <small class="d-block text-secondary">{{ $item->nombre }}</small>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Tag Flotante sobre la Imagen -->
                                        <span
                                            class="position-absolute bottom-0 start-50 translate-middle-x badge bg-dark text-white px-3 py-2 rounded-pill shadow"
                                            style="font-size: 0.8rem;">
                                            🛍️ {{ $item->categoria->nombre ?? 'Exclusivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Controles Anterior / Siguiente -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroStoreCarousel"
                    data-bs-slide="prev" style="width: 5%;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroStoreCarousel"
                    data-bs-slide="next" style="width: 5%;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        @else
            <!-- Fallback si no hay productos aún -->
            <div class="hero-banner mb-5">
                <h1 class="display-5 fw-extrabold text-white mb-2">
                    {{ $ajuste->nombre ?? (tenant('name') ?? 'Bienvenido a Nuestra Tienda') }}</h1>
                <p class="lead text-light text-opacity-75 mb-0">
                    {{ $ajuste->descripcion ?? 'Catálogo online con atención personalizada.' }}</p>
            </div>
        @endif

        <!-- 2. Tarjetas de Beneficios y Confianza (Trust Badges) -->
        <div class="row g-3 mb-5">
            <div class="col-6 col-lg-3">
                <div class="trust-feature-card h-100">
                    <div class="trust-icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bx bx-package"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Envíos Rápidos</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">Despacho directo y seguro</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="trust-feature-card h-100">
                    <div class="trust-icon-box bg-success bg-opacity-10 text-success">
                        <i class="bx bxl-whatsapp"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Atención 24/7</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">Compras vía WhatsApp</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="trust-feature-card h-100">
                    <div class="trust-icon-box bg-info bg-opacity-10 text-info">
                        <i class="bx bx-shield-quarter"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Garantía Total</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">Calidad 100% verificada</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="trust-feature-card h-100">
                    <div class="trust-icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="bx bx-credit-card"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Precios Claros</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">Sin cargos ocultos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Barra de Categorías (Chips Interactivos) -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bx bx-category text-primary"></i> Categorías de Productos
                </h5>
                <small class="text-muted">Desliza para ver más &rarr;</small>
            </div>

            <div class="d-flex gap-2 overflow-auto pb-2" style="scrollbar-width: thin;">
                <a href="{{ route('tienda.index', request()->only(['buscar', 'orden'])) }}"
                    class="category-chip {{ !request('categoria') ? 'active' : '' }}">
                    <i class="bx bx-grid-alt"></i> Todos los Productos
                </a>
                @foreach ($categorias as $cat)
                    <a href="{{ route('tienda.index', array_merge(request()->only(['buscar', 'orden']), ['categoria' => $cat->slug])) }}"
                        class="category-chip {{ request('categoria') === $cat->slug ? 'active' : '' }}">
                        <span>{{ $cat->nombre }}</span>
                        <span
                            class="badge {{ request('categoria') === $cat->slug ? 'bg-white text-primary' : 'bg-light text-secondary' }} rounded-pill"
                            style="font-size: 0.72rem;">
                            {{ $cat->productos_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 4. Barra de Filtro Activo y Ordenamiento -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 bg-white p-3 rounded-4 border">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-dark">
                    {{ $productos->total() }} productos encontrados
                </span>
                @if (request('buscar') || request('categoria'))
                    <span class="badge bg-label-primary px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                        @if (request('categoria'))
                            Categoría: <strong>{{ $categoriaActual->nombre ?? request('categoria') }}</strong>
                        @endif
                        @if (request('buscar'))
                            Búsqueda: <strong>"{{ request('buscar') }}"</strong>
                        @endif
                        <a href="{{ route('tienda.index') }}" class="text-danger ms-2" title="Quitar filtro">
                            <i class="bx bx-x-circle fs-6"></i>
                        </a>
                    </span>
                @endif
            </div>

            <!-- Selector de Ordenamiento -->
            <form method="GET" action="{{ route('tienda.index') }}" class="d-flex align-items-center gap-2">
                @if (request('categoria'))
                    <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                @endif
                @if (request('buscar'))
                    <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                @endif
                <label class="small text-muted text-nowrap fw-semibold" for="orden">Ordenar:</label>
                <select name="orden" id="orden" class="form-select form-select-sm rounded-pill px-3"
                    onchange="this.form.submit()">
                    <option value="recientes" {{ request('orden') === 'recientes' ? 'selected' : '' }}>✨ Más Recientes
                    </option>
                    <option value="precio_menor" {{ request('orden') === 'precio_menor' ? 'selected' : '' }}>💵 Menor
                        Precio</option>
                    <option value="precio_mayor" {{ request('orden') === 'precio_mayor' ? 'selected' : '' }}>💎 Mayor
                        Precio</option>
                    <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>🔤 Nombre (A - Z)
                    </option>
                </select>
            </form>
        </div>

        <!-- 5. Grilla de Productos Super Estilizada -->
        <div class="row g-3 g-md-4 mb-5">
            @forelse ($productos as $producto)
                @php
                    $foto = $producto->foto_principal;
                    $imgUrl = $foto ? tenant_asset($foto->ruta) : '';
                    $mensajeWa =
                        "¡Hola {$nombreTienda}! Deseo realizar el pedido de este producto:\n\n" .
                        "📦 *{$producto->nombre}*\n" .
                        ($producto->codigo ? "🏷️ *Código:* {$producto->codigo}\n" : '') .
                        "💰 *Precio:* {$divisa} " .
                        number_format($producto->precio_venta, 2) .
                        "\n\n" .
                        '¿Tienen stock disponible para coordinar la entrega?';
                    $linkWa = "https://wa.me/{$telLimpio}?text=" . urlencode($mensajeWa);
                @endphp

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card card-ecommerce">
                        <!-- Imagen y Badges Flotantes -->
                        <div class="img-wrapper">
                            <a href="{{ route('tienda.producto', $producto->slug) }}" class="d-block w-100 h-100">
                                @if ($foto)
                                    <img src="{{ tenant_asset($foto->ruta) }}" alt="{{ $producto->nombre }}"
                                        loading="lazy">
                                @else
                                    <div
                                        class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted bg-light">
                                        <i class="bx bx-image fs-1 text-secondary"></i>
                                        <small style="font-size: 0.7rem;">Sin foto</small>
                                    </div>
                                @endif
                            </a>

                            <!-- Badge de Categoría -->
                            @if ($producto->categoria)
                                <span class="badge-category-floating">
                                    {{ $producto->categoria->nombre }}
                                </span>
                            @endif

                            <!-- Badge de Stock -->
                            @if ($producto->stock <= 0)
                                <span class="badge bg-danger badge-stock-floating">Agotado</span>
                            @elseif ($producto->stock <= 5)
                                <span class="badge bg-warning text-dark badge-stock-floating">¡Últimos
                                    {{ $producto->stock }}!</span>
                            @endif
                        </div>

                        <!-- Datos del Producto -->
                        <div class="card-body p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                @if ($producto->codigo)
                                    <small class="text-muted d-block fw-semibold mb-1" style="font-size: 0.75rem;">
                                        SKU: <code>{{ $producto->codigo }}</code>
                                    </small>
                                @endif
                                <h6 class="fw-bold mb-2">
                                    <a href="{{ route('tienda.producto', $producto->slug) }}"
                                        class="text-dark text-decoration-none d-block text-truncate"
                                        title="{{ $producto->nombre }}">
                                        {{ $producto->nombre }}
                                    </a>
                                </h6>
                            </div>

                            <div class="mt-3">
                                <!-- Precio Destacado -->
                                <div class="d-flex align-items-baseline gap-1 mb-3">
                                    <small class="text-muted" style="font-size: 0.85rem;">{{ $divisa }}</small>
                                    <span class="fs-4 fw-extrabold text-primary lh-1">
                                        {{ number_format($producto->precio_venta, 2) }}
                                    </span>
                                </div>

                                <!-- Botones de Acción: Carrito, WhatsApp y Detalle -->
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn-card-add-cart"
                                        onclick="tenantCart.add({ id: {{ $producto->id }}, name: '{{ addslashes($producto->nombre) }}', price: {{ $producto->precio_venta }}, code: '{{ $producto->codigo }}', image: '{{ $imgUrl }}', slug: '{{ $producto->slug }}' })">
                                        <i class="bx bx-cart-add fs-5"></i>
                                        <span>Agregar</span>
                                    </button>

                                    <div class="d-flex gap-1">
                                        @if (!empty($telLimpio))
                                            <a href="{{ $linkWa }}" target="_blank"
                                                class="btn-card-wa flex-fill shadow-sm" title="Comprar directo">
                                                <i class="bx bxl-whatsapp fs-5"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('tienda.producto', $producto->slug) }}"
                                            class="btn-card-detail flex-fill" title="Ver Detalle">
                                            <i class="bx bx-show"></i> Detalle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center bg-white rounded-4 border shadow-sm my-3">
                    <div class="p-4">
                        <i class="bx bx-search-alt display-2 text-primary mb-3"></i>
                        <h4 class="fw-bold text-dark">No encontramos productos con ese criterio</h4>
                        <p class="text-muted" style="max-width: 480px; margin: 0 auto 1.5rem auto;">
                            No hay artículos que coincidan con la búsqueda o categoría seleccionada. Prueba con otros
                            términos.
                        </p>
                        <a href="{{ route('tienda.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                            <i class="bx bx-grid-alt me-1"></i> Ver Catálogo Completo
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- 6. Paginación y Resumen de Productos -->
        @if ($productos->hasPages())
            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-5 bg-white p-3 px-4 rounded-4 border shadow-sm">
                <div class="text-secondary small fw-semibold">
                    <i class="bx bx-package text-primary me-1 fs-5 align-middle"></i>
                    Mostrando <strong class="text-dark">{{ $productos->firstItem() }}</strong> a
                    <strong class="text-dark">{{ $productos->lastItem() }}</strong> de
                    <span class="badge bg-primary rounded-pill px-2 ms-1">{{ $productos->total() }}</span> productos
                </div>
                <div>
                    {{ $productos->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection