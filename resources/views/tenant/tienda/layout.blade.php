<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $ajuste->nombre ?? (tenant('name') ?? 'Tienda Online Oficial'))</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 & Boxicons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --secondary: #0ea5e9;
            --whatsapp: #25D366;
            --whatsapp-dark: #1ea952;
            --dark: #0f172a;
            --bg-page: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --card-border: #e2e8f0;
            --shadow-card: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            --shadow-hover: 0 20px 30px -10px rgba(15, 23, 42, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* 1. Barra Superior */
        .top-notification-bar {
            background: linear-gradient(90deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            color: #e0e7ff;
            font-size: 0.82rem;
            letter-spacing: 0.3px;
        }

        /* 2. Navbar Principal */
        .navbar-main {
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        }

        .store-logo-img {
            max-height: 46px;
            max-width: 150px;
            object-fit: contain;
        }

        .store-icon-placeholder {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        /* Buscador */
        .search-container {
            max-width: 520px;
            width: 100%;
        }

        .search-input-group {
            background: #f8fafc;
            border: 2px solid #cbd5e1;
            border-radius: 50px;
            padding: 4px 6px 4px 16px;
            transition: all 0.3s ease;
        }

        .search-input-group:focus-within {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        .search-input-group input {
            border: none;
            background: transparent;
            font-size: 0.92rem;
            outline: none;
            color: var(--dark);
        }

        /* Botón de WhatsApp */
        .btn-whatsapp-pill {
            background: linear-gradient(135deg, #25D366 0%, #1ea952 100%);
            color: white !important;
            font-weight: 700;
            border-radius: 50px;
            padding: 9px 20px;
            border: none;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-whatsapp-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 211, 102, 0.5);
            background: linear-gradient(135deg, #28e06c 0%, #1fae54 100%);
        }

        /* Botón de Carrito en Navbar */
        .btn-cart-nav {
            background: #f1f5f9;
            color: var(--dark);
            border: 2px solid #cbd5e1;
            border-radius: 50px;
            padding: 8px 18px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .btn-cart-nav:hover {
            background: #ffffff;
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }

        .btn-cart-nav .badge-count {
            background: #ef4444;
            color: white;
            border-radius: 50px;
            padding: 3px 8px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        /* Botón Flotante Fijo WhatsApp */
        .floating-whatsapp {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 1050;
            width: 60px;
            height: 60px;
            background: #25D366;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }

        .floating-whatsapp:hover {
            transform: scale(1.12) rotate(8deg);
            color: white;
        }

        .whatsapp-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(37, 211, 102, 0.6);
            animation: pulse-border 2s infinite;
            z-index: -1;
        }

        /* Botón Flotante Fijo del Carrito */
        .floating-cart {
            position: fixed;
            bottom: 95px;
            right: 25px;
            z-index: 1050;
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.45);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            cursor: pointer;
        }

        .floating-cart:hover {
            transform: scale(1.12) translateY(-2px);
            background: var(--primary-dark);
            color: white;
        }

        .floating-cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            min-width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        @keyframes pulse-border {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        /* 3. Hero Banner / Carrusel */
        .hero-banner {
            background: radial-gradient(circle at 10% 20%, #1e1b4b 0%, #0f172a 90%) !important;
            border-radius: 24px !important;
            position: relative;
            overflow: hidden;
            color: #ffffff !important;
            box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-banner .carousel-item {
            background: transparent !important;
        }

        /* 4. Categorías en Chips */
        .category-chip {
            background: #ffffff !important;
            border: 2px solid #cbd5e1 !important;
            color: #334155 !important;
            padding: 9px 20px !important;
            border-radius: 50px !important;
            text-decoration: none !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
        }

        .category-chip:hover {
            color: var(--primary) !important;
            border-color: var(--primary) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(79, 70, 229, 0.15) !important;
        }

        .category-chip.active {
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35) !important;
        }

        /* 5. Tarjetas de Beneficios (Trust Badges) */
        .trust-feature-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 1.25rem 1.5rem;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease;
        }

        .trust-feature-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-card);
        }

        .trust-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        /* 6. Product Cards */
        .card-ecommerce {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .card-ecommerce:hover {
            transform: translateY(-6px);
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
        }

        .card-ecommerce .img-wrapper {
            position: relative;
            height: 220px;
            background: #f8fafc;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-ecommerce .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .card-ecommerce:hover .img-wrapper img {
            transform: scale(1.08);
        }

        .badge-category-floating {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 700;
            z-index: 2;
        }

        .badge-stock-floating {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .btn-card-add-cart {
            background: var(--primary-light);
            color: var(--primary) !important;
            font-weight: 700;
            border-radius: 12px;
            padding: 9px 14px;
            border: 1px solid rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.88rem;
            cursor: pointer;
        }

        .btn-card-add-cart:hover {
            background: var(--primary);
            color: white !important;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-card-wa {
            background: #25D366;
            color: white !important;
            font-weight: 700;
            border-radius: 12px;
            padding: 9px 14px;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.88rem;
            text-decoration: none;
        }

        .btn-card-wa:hover {
            background: #1ea952;
            transform: scale(1.02);
            color: white;
        }

        .btn-card-detail {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            border-radius: 12px;
            padding: 9px 14px;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
            font-size: 0.88rem;
            text-decoration: none;
            text-align: center;
        }

        .btn-card-detail:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* 7. Ficha de Detalle */
        .breadcrumb-custom {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            border-radius: 14px;
            padding: 12px 20px;
        }

        .product-detail-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 24px;
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .main-img-box {
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 20px;
            height: 430px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .main-img-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.08));
        }

        .thumbnail-click {
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            width: 76px;
            height: 76px;
            padding: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .thumbnail-click:hover,
        .thumbnail-click.active-thumb {
            border-color: var(--primary) !important;
            transform: scale(1.06);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .price-highlight-box {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-radius: 18px;
            padding: 1.5rem 1.75rem;
            color: white;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .price-currency-tag {
            background: rgba(255, 255, 255, 0.15);
            color: #93c5fd;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .description-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid var(--primary);
            border-radius: 14px;
            padding: 16px 20px;
            color: #1e293b;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .benefit-badge {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 0.85rem;
            color: #1e293b;
        }

        /* 8. Offcanvas Carrito */
        .offcanvas-cart {
            width: 420px !important;
            max-width: 90vw !important;
            border-left: 2px solid #e2e8f0;
        }

        .cart-item-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px;
            transition: all 0.2s ease;
        }

        .cart-item-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }

        .cart-item-img {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            object-fit: cover;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .qty-control-group {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 50px;
            padding: 2px 4px;
        }

        .qty-btn {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: none;
            background: #ffffff;
            color: #0f172a;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.15s ease;
        }

        .qty-btn:hover {
            background: var(--primary);
            color: white;
        }

        .qty-number {
            width: 32px;
            text-align: center;
            font-weight: 700;
            font-size: 0.88rem;
        }

        /* Footer */
        .footer-luxury {
            background: #0f172a;
            color: #94a3b8;
            border-top: 1px solid #1e293b;
            margin-top: auto;
        }

        .footer-luxury h6 {
            color: #ffffff;
            font-weight: 700;
        }

        .footer-luxury a {
            color: #94a3b8;
            text-decoration: none;
        }

        .footer-luxury a:hover {
            color: #ffffff;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- 1. Barra Superior -->
    <div class="top-notification-bar py-2 text-center">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="d-inline-flex align-items-center gap-1 mx-auto mx-md-0">
                <i class="bx bxs-zap text-warning"></i>
                <strong>¡Catálogo Oficial Activo!</strong> Compra rápida con atención inmediata vía WhatsApp.
            </span>
            <div class="d-none d-md-flex align-items-center gap-3">
                @if (!empty($ajuste->telefono))
                    <span><i class="bx bxs-phone me-1 text-success"></i> {{ $ajuste->telefono }}</span>
                @endif
                @if (!empty($ajuste->email))
                    <span><i class="bx bxs-envelope me-1 text-info"></i> {{ $ajuste->email }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. Navbar Principal -->
    <nav class="navbar navbar-expand-lg navbar-main sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('tienda.index') }}">
                @if (!empty($ajuste->logo))
                    <img src="{{ tenant_asset($ajuste->logo) }}" alt="Logo" class="store-logo-img">
                @else
                    <div class="store-icon-placeholder">
                        <i class="bx bx-store-alt fs-3"></i>
                    </div>
                @endif
                <div class="d-flex flex-column">
                    <span class="fw-extrabold text-dark fs-5 lh-1">
                        {{ $ajuste->nombre ?? (tenant('name') ?? 'Mi Tienda') }}
                    </span>
                    <small class="text-muted fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">TIENDA
                        OFICIAL</small>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarStoreContent">
                <i class="bx bx-menu fs-1 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarStoreContent">
                <div class="search-container mx-auto my-3 my-lg-0 px-lg-3">
                    <form action="{{ route('tienda.index') }}" method="GET"
                        class="search-input-group d-flex align-items-center">
                        @if (request('categoria'))
                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                        @endif
                        <i class="bx bx-search fs-5 text-muted me-2"></i>
                        <input type="text" name="buscar" class="w-100"
                            placeholder="Buscar en el catálogo (ej: Nike, Celular, SKU)..."
                            value="{{ request('buscar') }}">
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold">
                            Buscar
                        </button>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2 justify-content-end">
                    <!-- Botón del Carrito en Navbar -->
                    <button type="button" class="btn-cart-nav" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasCart">
                        <i class="bx bx-cart fs-5 text-primary"></i>
                        <span class="d-none d-sm-inline">Carrito</span>
                        <span class="badge-count cart-counter-badge">0</span>
                    </button>

                    @if (!empty($ajuste->telefono))
                        @php
                            $telLimpio = preg_replace('/[^0-9]/', '', $ajuste->telefono);
                        @endphp
                        <a href="https://wa.me/{{ $telLimpio }}?text={{ urlencode('¡Hola! Deseo información de su catálogo.') }}"
                            target="_blank" class="btn-whatsapp-pill">
                            <i class="bx bxl-whatsapp fs-4"></i>
                            <span class="d-none d-lg-inline">WhatsApp</span>
                        </a>
                    @endif

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="btn btn-dark rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
                            <i class="bx bx-cog fs-5"></i>
                            <span>Admin</span>
                        </a>
                    @else
                        <a href="{{ url('/login') }}"
                            class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
                            <i class="bx bx-user-circle fs-5"></i>
                            <span>Ingresar</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. Contenido Principal -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- 4. Botón Flotante Fijo del Carrito -->
    <button type="button" class="floating-cart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
        title="Ver Carrito de Compras">
        <i class="bx bx-cart"></i>
        <span class="floating-cart-badge cart-counter-badge">0</span>
    </button>

    <!-- 5. Botón Flotante Fijo de WhatsApp -->
    @if (!empty($ajuste->telefono))
        <a href="https://wa.me/{{ $telLimpio }}?text={{ urlencode('¡Hola! Deseo realizar un pedido de su tienda.') }}"
            target="_blank" class="floating-whatsapp" title="Escríbenos por WhatsApp">
            <span class="whatsapp-pulse"></span>
            <i class="bx bxl-whatsapp"></i>
        </a>
    @endif

    <!-- 6. Offcanvas / Drawer del Carrito Lateral -->
    <div class="offcanvas offcanvas-end offcanvas-cart shadow-lg" tabindex="-1" id="offcanvasCart"
        aria-labelledby="offcanvasCartLabel">
        <div class="offcanvas-header bg-light border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bx-shopping-bag fs-3 text-primary"></i>
                <div>
                    <h5 class="offcanvas-title fw-bold text-dark mb-0" id="offcanvasCartLabel">Mi Carrito</h5>
                    <small class="text-muted fw-semibold"><span class="cart-counter-badge">0</span> artículos
                        seleccionados</small>
                </div>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"
                aria-label="Cerrar"></button>
        </div>

        <div class="offcanvas-body p-3 d-flex flex-column justify-content-between" style="overflow-y: auto;">
            <!-- Contenedor de Items del Carrito -->
            <div id="cartItemsContainer" class="d-flex flex-column gap-3">
                <!-- Se renderiza vía JavaScript -->
            </div>

            <!-- Estado Vacío -->
            <div id="cartEmptyState" class="text-center py-5 d-none">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="bx bx-cart fs-1 text-secondary opacity-50"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Tu carrito está vacío</h6>
                <p class="text-muted small mb-4">Agrega los productos que desees comprar de nuestro catálogo.</p>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="offcanvas">
                    <i class="bx bx-search me-1"></i> Explorar Catálogo
                </button>
            </div>
        </div>

        <!-- Footer del Carrito con Resumen y Botón de Checkout -->
        <div class="offcanvas-footer border-top bg-light p-3" id="cartFooterSection">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted fw-semibold">Subtotal:</span>
                <span class="fw-bold text-dark fs-6" id="cartSubtotalDisplay">{{ $ajuste->divisa ?? '$' }}
                    0.00</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fs-5 fw-extrabold text-dark">Total:</span>
                <span class="fs-4 fw-extrabold text-primary" id="cartTotalDisplay">{{ $ajuste->divisa ?? '$' }}
                    0.00</span>
            </div>

            <div class="d-grid gap-2">
                <button type="button"
                    class="btn btn-success btn-lg rounded-pill fw-bold py-3 shadow-sm d-flex align-items-center justify-content-center gap-2"
                    onclick="tenantCart.openCheckout()">
                    <i class="bx bxl-whatsapp fs-4"></i> Continuar con el Pedido
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill fw-semibold"
                    onclick="tenantCart.confirmClear()">
                    <i class="bx bx-trash me-1"></i> Vaciar Carrito
                </button>
            </div>
        </div>
    </div>

    <!-- 7. Modal de Checkout / Datos del Cliente -->
    <div class="modal fade" id="modalCheckout" tabindex="-1" aria-labelledby="modalCheckoutLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header bg-dark text-white p-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                            style="width: 38px; height: 38px;">
                            <i class="bx bxl-whatsapp text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="modalCheckoutLabel">Finalizar Pedido
                            </h5>
                            <small class="text-white-50">Confirma tus datos para enviarlo por WhatsApp</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="formCheckout" onsubmit="tenantCart.submitOrder(event)">
                    <div class="modal-body p-4">
                        <!-- Resumen Compacto -->
                        <div
                            class="p-3 bg-light rounded-3 border mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-semibold">Artículos en pedido:</small>
                                <span class="fw-bold text-dark" id="checkoutItemsSummary">0 productos</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block fw-semibold">Monto Total:</small>
                                <span class="fs-5 fw-extrabold text-primary"
                                    id="checkoutTotalSummary">{{ $ajuste->divisa ?? '$' }} 0.00</span>
                            </div>
                        </div>

                        <!-- Nombre Completo -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bx bx-user text-primary me-1"></i> Nombre Completo <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="text" id="checkoutName" class="form-control rounded-3 py-2"
                                placeholder="Ej: Carlos Ramírez" required>
                        </div>

                        <!-- Teléfono / WhatsApp -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bx bx-phone text-success me-1"></i> Tu Número de Teléfono / WhatsApp <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="tel" id="checkoutPhone" class="form-control rounded-3 py-2"
                                placeholder="Ej: +591 70000000" required>
                        </div>

                        <!-- Tipo de Entrega -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bx bx-package text-warning me-1"></i> Método de Entrega
                            </label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="deliveryType"
                                        id="deliveryHome" value="Envío a Domicilio" checked
                                        onchange="tenantCart.toggleAddressField(true)">
                                    <label class="form-check-label fw-semibold" for="deliveryHome">
                                        Envío a Domicilio
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="deliveryType"
                                        id="deliveryStore" value="Retiro en Tienda / Local"
                                        onchange="tenantCart.toggleAddressField(false)">
                                    <label class="form-check-label fw-semibold" for="deliveryStore">
                                        Retiro en Tienda
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Dirección de Entrega -->
                        <div class="mb-3" id="addressFieldContainer">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bx bx-map-pin text-danger me-1"></i> Dirección de Entrega y Referencias <span
                                    class="text-danger">*</span>
                            </label>
                            <textarea id="checkoutAddress" class="form-control rounded-3" rows="2"
                                placeholder="Calle, número, barrio y alguna referencia de ubicación..."></textarea>
                        </div>

                        <!-- Forma de Pago -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bx bx-credit-card text-info me-1"></i> Método de Pago Preferido
                            </label>
                            <select id="checkoutPayment" class="form-select rounded-3 py-2 fw-semibold">
                                <option value="Efectivo contra entrega">💵 Efectivo contra entrega</option>
                                <option value="Transferencia Bancaria / QR">📱 Transferencia Bancaria / QR</option>
                                <option value="Tarjeta / Enlace de Pago">💳 Tarjeta de Crédito / Débito</option>
                                <option value="A convenir por WhatsApp">💬 A convenir por WhatsApp</option>
                            </select>
                        </div>

                        <!-- Notas Adicionales -->
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-dark">
                                <i class="bx bx-note text-secondary me-1"></i> Notas o Indicaciones Especiales
                                (Opcional)
                            </label>
                            <input type="text" id="checkoutNotes" class="form-control rounded-3 py-2"
                                placeholder="Ej: Entregar por la tarde, tocar timbre...">
                        </div>
                    </div>

                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit"
                            class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2"
                            style="background-color: var(--whatsapp); border: none;">
                            <i class="bx bxl-whatsapp fs-4"></i> Enviar Pedido a WhatsApp
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 8. Footer -->
    <footer class="footer-luxury pt-5 pb-4">
        <div class="container">
            <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @if (!empty($ajuste->logo))
                            <div class="bg-white p-2 rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm"
                                style="height: 48px; max-width: 150px;">
                                <img src="{{ tenant_asset($ajuste->logo) }}" alt="Logo" class="store-logo-img"
                                    style="max-height: 38px; width: auto; object-fit: contain;">
                            </div>
                        @else
                            <div class="store-icon-placeholder">
                                <i class="bx bx-store fs-4"></i>
                            </div>
                        @endif
                        <h4 class="fw-bold text-white mb-0">{{ $ajuste->nombre ?? (tenant('name') ?? 'Mi Tienda') }}
                        </h4>
                    </div>
                    <p class="text-secondary pe-lg-4 mb-3">
                        {{ $ajuste->descripcion ?? 'Nuestra plataforma te ofrece los mejores productos con atención rápida y personalizada directo a tu WhatsApp.' }}
                    </p>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="text-uppercase mb-3">Información de Contacto</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        @if (!empty($ajuste->direccion))
                            <li class="d-flex align-items-start gap-2">
                                <i class="bx bx-map-pin text-primary fs-5 mt-1"></i>
                                <span>{{ $ajuste->direccion }}</span>
                            </li>
                        @endif
                        @if (!empty($ajuste->telefono))
                            <li class="d-flex align-items-center gap-2">
                                <i class="bx bx-phone-call text-success fs-5"></i>
                                <span>{{ $ajuste->telefono }}</span>
                            </li>
                        @endif
                        @if (!empty($ajuste->email))
                            <li class="d-flex align-items-center gap-2">
                                <i class="bx bx-envelope text-info fs-5"></i>
                                <span>{{ $ajuste->email }}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase mb-3">Atención al Cliente</h6>
                    <p class="small text-secondary mb-2">
                        Despachos y entregas garantizadas con soporte en vivo.
                    </p>
                    @if (!empty($ajuste->telefono))
                        <a href="https://wa.me/{{ $telLimpio }}" target="_blank"
                            class="btn btn-sm btn-outline-success rounded-pill px-3 mt-2 w-100">
                            <i class="bx bxl-whatsapp me-1"></i> Chat de Atención Directa
                        </a>
                    @endif
                </div>
            </div>

            <div class="pt-4 text-center text-secondary small">
                &copy; {{ date('Y') }} <strong
                    class="text-white">{{ $ajuste->nombre ?? (tenant('name') ?? 'Mi Tienda') }}</strong>. Todos los
                derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Motor JavaScript del Carrito de Compras Multi-Tenant -->
    <script>
        const tenantCart = {
            storageKey: 'saas_cart_{{ tenant('id') }}',
            currency: '{{ $ajuste->divisa ?? '$' }}',
            storePhone: '{{ $telLimpio ?? '' }}',
            storeName: '{{ $ajuste->nombre ?? (tenant('name') ?? 'la tienda') }}',

            getItems() {
                try {
                    return JSON.parse(localStorage.getItem(this.storageKey)) || [];
                } catch (e) {
                    return [];
                }
            },

            saveItems(items) {
                localStorage.setItem(this.storageKey, JSON.stringify(items));
                this.renderUI();
            },

            add(product, quantity = 1, notify = true) {
                let items = this.getItems();
                let existing = items.find(item => item.id === product.id);

                if (existing) {
                    existing.quantity += quantity;
                } else {
                    items.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        code: product.code || '',
                        image: product.image || '',
                        slug: product.slug || '',
                        quantity: quantity
                    });
                }

                this.saveItems(items);

                if (notify) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `¡${product.name} agregado!`,
                        text: `${quantity} unidad(es) en el carrito`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            },

            updateQuantity(productId, newQty) {
                let items = this.getItems();
                let item = items.find(i => i.id === productId);

                if (item) {
                    if (newQty <= 0) {
                        this.remove(productId);
                        return;
                    }
                    item.quantity = newQty;
                    this.saveItems(items);
                }
            },

            remove(productId) {
                let items = this.getItems().filter(i => i.id !== productId);
                this.saveItems(items);
            },

            confirmClear() {
                Swal.fire({
                    title: '¿Vaciar el carrito?',
                    text: 'Se eliminarán todos los productos seleccionados.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, vaciar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.clear();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Carrito vaciado',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            },

            clear() {
                localStorage.removeItem(this.storageKey);
                this.renderUI();
            },

            getTotalCount() {
                return this.getItems().reduce((acc, item) => acc + item.quantity, 0);
            },

            getTotalAmount() {
                return this.getItems().reduce((acc, item) => acc + (item.price * item.quantity), 0);
            },

            renderUI() {
                const items = this.getItems();
                const totalCount = this.getTotalCount();
                const totalAmount = this.getTotalAmount();

                // 1. Actualizar insignias de conteo
                document.querySelectorAll('.cart-counter-badge').forEach(el => {
                    el.textContent = totalCount;
                });

                // 2. Elementos del Offcanvas
                const container = document.getElementById('cartItemsContainer');
                const emptyState = document.getElementById('cartEmptyState');
                const footerSection = document.getElementById('cartFooterSection');
                const subtotalDisplay = document.getElementById('cartSubtotalDisplay');
                const totalDisplay = document.getElementById('cartTotalDisplay');

                if (!container) return;

                if (items.length === 0) {
                    container.innerHTML = '';
                    emptyState.classList.remove('d-none');
                    footerSection.classList.add('d-none');
                } else {
                    emptyState.classList.add('d-none');
                    footerSection.classList.remove('d-none');

                    container.innerHTML = items.map(item => `
                        <div class="cart-item-row d-flex align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2" style="max-width: 58%;">
                                ${item.image ? `<img src="${item.image}" alt="${item.name}" class="cart-item-img">` : `
                                        <div class="cart-item-img d-flex align-items-center justify-content-center text-muted">
                                            <i class="bx bx-package fs-4"></i>
                                        </div>
                                    `}
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.88rem;" title="${item.name}">${item.name}</h6>
                                    <small class="text-muted d-block" style="font-size: 0.78rem;">${this.currency} ${item.price.toFixed(2)} c/u</small>
                                    <span class="fw-extrabold text-primary" style="font-size: 0.88rem;">${this.currency} ${(item.price * item.quantity).toFixed(2)}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                <div class="qty-control-group">
                                    <button type="button" class="qty-btn" onclick="tenantCart.updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                    <span class="qty-number">${item.quantity}</span>
                                    <button type="button" class="qty-btn" onclick="tenantCart.updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                </div>
                                <button type="button" class="btn btn-light text-danger btn-sm rounded-circle p-1" title="Eliminar" onclick="tenantCart.remove(${item.id})">
                                    <i class="bx bx-trash fs-5"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');

                    subtotalDisplay.textContent = `${this.currency} ${totalAmount.toFixed(2)}`;
                    totalDisplay.textContent = `${this.currency} ${totalAmount.toFixed(2)}`;
                }
            },

            openCheckout() {
                const items = this.getItems();
                if (items.length === 0) {
                    Swal.fire('Carrito Vacío', 'Agrega al menos un producto antes de continuar.', 'info');
                    return;
                }

                // Cerrar offcanvas y abrir modal
                const offcanvasEl = document.getElementById('offcanvasCart');
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if (offcanvas) offcanvas.hide();

                // Actualizar resumen en el modal
                document.getElementById('checkoutItemsSummary').textContent = `${this.getTotalCount()} producto(s)`;
                document.getElementById('checkoutTotalSummary').textContent =
                    `${this.currency} ${this.getTotalAmount().toFixed(2)}`;

                const modal = new bootstrap.Modal(document.getElementById('modalCheckout'));
                modal.show();
            },

            toggleAddressField(isDelivery) {
                const addressBox = document.getElementById('addressFieldContainer');
                const addressInput = document.getElementById('checkoutAddress');
                if (isDelivery) {
                    addressBox.classList.remove('d-none');
                    addressInput.required = true;
                } else {
                    addressBox.classList.add('d-none');
                    addressInput.required = false;
                }
            },

            submitOrder(event) {
                event.preventDefault();
                const items = this.getItems();
                if (items.length === 0) return;

                const name = document.getElementById('checkoutName').value.trim();
                const phone = document.getElementById('checkoutPhone').value.trim();
                const deliveryType = document.querySelector('input[name="deliveryType"]:checked').value;
                const address = document.getElementById('checkoutAddress').value.trim();
                const payment = document.getElementById('checkoutPayment').value;
                const notes = document.getElementById('checkoutNotes').value.trim();

                if (!this.storePhone) {
                    Swal.fire('Atención', 'La tienda no ha configurado un número de WhatsApp para recepción.',
                        'warning');
                    return;
                }

                // Construir mensaje estructurado para WhatsApp
                let message = `🛍️ *¡NUEVO PEDIDO - ${this.storeName.toUpperCase()}!*\n`;
                message += `━━━━━━━━━━━━━━━━━━━━━━━━\n`;
                message += `👤 *Cliente:* ${name}\n`;
                message += `📱 *Teléfono:* ${phone}\n`;
                message += `🚚 *Entrega:* ${deliveryType}\n`;
                if (deliveryType === 'Envío a Domicilio' && address) {
                    message += `📍 *Dirección:* ${address}\n`;
                }
                message += `💳 *Método de Pago:* ${payment}\n`;
                if (notes) {
                    message += `📝 *Notas:* ${notes}\n`;
                }
                message += `━━━━━━━━━━━━━━━━━━━━━━━━\n`;
                message += `📦 *DETALLE DEL PEDIDO:*\n`;

                items.forEach((item, index) => {
                    const subtotal = (item.price * item.quantity).toFixed(2);
                    message +=
                        `• *${item.quantity}x* ${item.name} (${this.currency} ${item.price.toFixed(2)} c/u) = *${this.currency} ${subtotal}*\n`;
                });

                message += `━━━━━━━━━━━━━━━━━━━━━━━━\n`;
                message += `💰 *TOTAL A PAGAR: ${this.currency} ${this.getTotalAmount().toFixed(2)}*\n`;
                message += `━━━━━━━━━━━━━━━━━━━━━━━━\n`;
                message += `_Pedido realizado desde el catálogo online_`;

                // URL de WhatsApp
                const waUrl = `https://wa.me/${this.storePhone}?text=${encodeURIComponent(message)}`;

                // Limpiar carrito y cerrar modal
                this.clear();
                const modalEl = document.getElementById('modalCheckout');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                // Abrir WhatsApp en nueva pestaña
                window.open(waUrl, '_blank');

                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido enviado a WhatsApp!',
                    text: 'Se ha abierto WhatsApp con el detalle de tu orden para coordinar la entrega.',
                    confirmButtonColor: '#25D366',
                    confirmButtonText: 'Entendido'
                });
            }
        };

        // Inicializar renderizado al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            tenantCart.renderUI();
        });
    </script>
    @stack('scripts')
</body>

</html>