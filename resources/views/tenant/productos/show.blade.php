@extends('layouts.admin')

@section('title', 'Detalles del Producto')

@section('content')
@php
    $divisa = $ajuste->divisa ?? '$';
    $fotoPrincipal = $producto->foto_principal;
    $ganancia = $producto->precio_venta - $producto->precio_compra;
    $margen = $producto->precio_compra > 0 ? ($ganancia / $producto->precio_compra) * 100 : 0;
@endphp

<div class="row">
    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bx bx-package text-primary me-2"></i> {{ $producto->nombre }}
            </h4>
            <span class="text-muted">
                Catálogo de Productos &rsaquo; Detalle de artículo
            </span>
        </div>
        <div>
            <a href="{{ url('/productos') }}" class="btn btn-outline-secondary me-1">
                <i class="bx bx-arrow-back me-1"></i> Volver al Catálogo
            </a>
            <a href="{{ url('/productos/' . $producto->id . '/edit') }}" class="btn btn-warning me-1">
                <i class="bx bx-edit-alt me-1"></i> Editar
            </a>
        </div>
    </div>

    <!-- Columna Izquierda: Galería de Imágenes Interactiva -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bx bx-images me-1 text-primary"></i> Galería de Fotos</h6>
                <span class="badge bg-label-primary">{{ $producto->imagenes->count() }} foto(s)</span>
            </div>
            <hr class="m-0" />
            <div class="card-body text-center p-3">
                <!-- Visor Principal de Foto Grande -->
                <div class="border rounded bg-light p-2 mb-3 d-flex align-items-center justify-content-center" 
                    style="height: 350px; background-color: #f8f9fa;">
                    @if ($fotoPrincipal)
                        <img id="main-product-img" src="{{ tenant_asset($fotoPrincipal->ruta) }}" 
                            alt="{{ $producto->nombre }}" class="img-fluid rounded" 
                            style="max-height: 100%; max-width: 100%; object-fit: contain;" />
                    @else
                        <div class="text-muted text-center">
                            <i class="bx bx-image display-1 text-secondary"></i>
                            <p class="mt-2 mb-0">Sin imágenes registradas</p>
                        </div>
                    @endif
                </div>

                <!-- Miniaturas de la Galería -->
                @if ($producto->imagenes->count() > 0)
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach ($producto->imagenes as $img)
                            <div class="thumbnail-item border rounded p-1 {{ $img->es_principal ? 'border-primary shadow-sm' : '' }}" 
                                style="width: 70px; height: 70px; cursor: pointer; transition: all 0.2s;"
                                onclick="changeMainImage('{{ tenant_asset($img->ruta) }}', this)">
                                <img src="{{ tenant_asset($img->ruta) }}" alt="Miniatura" 
                                    class="w-100 h-100 rounded" style="object-fit: cover;" />
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-2">Haz clic en una miniatura para ampliarla arriba.</small>
                @endif
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Información Detallada, Precios y Stock -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="bx bx-info-circle me-1 text-primary"></i> Información General</h6>
            </div>
            <hr class="m-0" />
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Categoría</small>
                        <span class="badge bg-label-primary fs-6 mt-1">
                            <i class="bx bx-category me-1"></i> {{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}
                        </span>
                    </div>

                    <div class="col-sm-3">
                        <small class="text-muted d-block">Código / SKU</small>
                        <span class="fw-semibold fs-6 mt-1 d-inline-block">
                            @if ($producto->codigo)
                                <code>{{ $producto->codigo }}</code>
                            @else
                                <span class="text-muted">No asignado</span>
                            @endif
                        </span>
                    </div>

                    <div class="col-sm-3">
                        <small class="text-muted d-block">Estado</small>
                        <div class="mt-1">
                            @if ($producto->estado)
                                <span class="badge bg-label-success"><i class="bx bx-check me-1"></i> Activo</span>
                            @else
                                <span class="badge bg-label-danger"><i class="bx bx-x me-1"></i> Inactivo</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tarjetas de Precios y Ganancia -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-lighter border p-3 text-center h-100">
                            <small class="text-muted fw-semibold">PRECIO DE VENTA</small>
                            <h4 class="fw-bold text-success mb-0 mt-1">
                                {{ $divisa }} {{ number_format($producto->precio_venta, 2) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-lighter border p-3 text-center h-100">
                            <small class="text-muted fw-semibold">PRECIO DE COSTO</small>
                            <h4 class="fw-bold text-secondary mb-0 mt-1">
                                {{ $divisa }} {{ number_format($producto->precio_compra, 2) }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-lighter border p-3 text-center h-100">
                            <small class="text-muted fw-semibold">MARGEN / GANANCIA</small>
                            <h5 class="fw-bold {{ $ganancia >= 0 ? 'text-primary' : 'text-danger' }} mb-0 mt-1">
                                +{{ $divisa }} {{ number_format($ganancia, 2) }}
                            </h5>
                            @if($producto->precio_compra > 0)
                                <small class="text-muted">({{ number_format($margen, 1) }}%)</small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock e Inventario -->
                <div class="p-3 bg-lighter rounded mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <span class="fw-bold d-block"><i class="bx bx-layer me-1 text-primary"></i> Disponibilidad en Stock</span>
                        <small class="text-muted">Unidades disponibles para venta en tienda</small>
                    </div>
                    <div>
                        @if ($producto->stock <= 0)
                            <span class="badge bg-danger fs-6 p-2">
                                <i class="bx bx-error-circle me-1"></i> Agotado (0 unidades)
                            </span>
                        @elseif($producto->stock <= 5)
                            <span class="badge bg-warning fs-6 p-2 text-dark">
                                <i class="bx bx-time-five me-1"></i> Stock Crítico: {{ $producto->stock }} unidades
                            </span>
                        @else
                            <span class="badge bg-success fs-6 p-2">
                                <i class="bx bx-check-circle me-1"></i> {{ $producto->stock }} unidades disponibles
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2"><i class="bx bx-detail me-1 text-primary"></i> Descripción del Producto</h6>
                    <div class="p-3 bg-light rounded border text-secondary" style="min-height: 80px; white-space: pre-line;">
                        {{ $producto->descripcion ?? 'Este producto no cuenta con descripción detallada.' }}
                    </div>
                </div>

                <!-- Metadatos de Auditoría -->
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 small">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Slug (URL de tienda)</th>
                                <td><code>/producto/{{ $producto->slug }}</code></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Fecha de Registro</th>
                                <td>{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Última Actualización</th>
                                <td>{{ $producto->updated_at ? $producto->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                    <a href="{{ url('/productos') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Volver
                    </a>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ url('/productos/' . $producto->id . '/edit') }}" class="btn btn-warning">
                            <i class="bx bx-edit-alt me-1"></i> Editar
                        </a>

                        <form action="{{ url('/productos/' . $producto->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger btn-delete">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function changeMainImage(url, element) {
        const mainImg = document.getElementById('main-product-img');
        if (mainImg) {
            mainImg.src = url;
        }
        document.querySelectorAll('.thumbnail-item').forEach(el => {
            el.classList.remove('border-primary', 'shadow-sm');
        });
        element.classList.add('border-primary', 'shadow-sm');
    }

    document.querySelector('.btn-delete')?.addEventListener('click', function() {
        const form = this.closest('form');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El producto y todas sus imágenes serán eliminados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
