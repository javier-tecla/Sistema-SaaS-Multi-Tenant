@extends('layouts.admin')

@section('title', 'Listado de Productos')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-package me-2 text-primary"></i> Catálogo de Productos
            </h5>
            <a href="{{ url('/productos/create') }}" class="btn btn-primary">
                <i class="bx bx-plus-circle me-1"></i> Nuevo Producto
            </a>
        </div>
        <hr class="m-0" />

        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 90px;" class="text-center">Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th style="width: 110px;">Estado</th>
                        <th style="width: 160px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($productos as $producto)
                        @php
                            $foto = $producto->foto_principal;
                            $divisa = $ajuste->divisa ?? '$';
                            $totalFotos = $producto->imagenes->count();
                        @endphp
                        <tr>
                            <td class="fw-bold text-muted">{{ $productos->firstItem() + $loop->index }}</td>
                            <td class="text-center">
                                <div class="position-relative d-inline-block">
                                    @if ($foto)
                                        <img src="{{ tenant_asset($foto->ruta) }}" alt="{{ $producto->nombre }}"
                                            class="rounded border shadow-sm"
                                            style="width: 54px; height: 54px; object-fit: cover;" />
                                    @else
                                        <div class="rounded border bg-light d-inline-flex align-items-center justify-content-center text-muted"
                                            style="width: 54px; height: 54px;">
                                            <i class="bx bx-image fs-3 text-secondary"></i>
                                        </div>
                                    @endif

                                    @if ($totalFotos > 1)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"
                                            style="font-size: 0.65rem;" title="{{ $totalFotos }} imágenes en galería">
                                            +{{ $totalFotos }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark fs-6">{{ $producto->nombre }}</span>
                                    @if ($producto->codigo)
                                        <small class="text-muted"><i class="bx bx-barcode me-1"></i><code>{{ $producto->codigo }}</code></small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($producto->categoria)
                                    <span class="badge bg-label-primary fw-semibold">
                                        <i class="bx bx-category-alt me-1"></i> {{ $producto->categoria->nombre }}
                                    </span>
                                @else
                                    <span class="badge bg-label-secondary">Sin categoría</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success fs-6">
                                    {{ $divisa }} {{ number_format($producto->precio_venta, 2) }}
                                </span>
                                @if ($producto->precio_compra > 0)
                                    <br>
                                    <small class="text-muted">Costo: {{ $divisa }} {{ number_format($producto->precio_compra, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($producto->stock <= 0)
                                    <span class="badge bg-label-danger fw-bold">
                                        <i class="bx bx-error-circle me-1"></i> Agotado (0)
                                    </span>
                                @elseif($producto->stock <= 5)
                                    <span class="badge bg-label-warning fw-bold">
                                        <i class="bx bx-time-five me-1"></i> Bajo ({{ $producto->stock }})
                                    </span>
                                @else
                                    <span class="badge bg-label-success fw-bold">
                                        <i class="bx bx-check-circle me-1"></i> {{ $producto->stock }} unid.
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($producto->estado)
                                    <span class="badge bg-label-success">
                                        <i class="bx bx-check me-1"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-label-danger">
                                        <i class="bx bx-x me-1"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/productos/' . $producto->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info me-1" title="Ver Detalles">
                                    <i class="bx bx-show-alt"></i>
                                </a>

                                <a href="{{ url('/productos/' . $producto->id . '/edit') }}"
                                    class="btn btn-sm btn-icon btn-outline-warning me-1" title="Editar Producto">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                <form action="{{ url('/productos/' . $producto->id) }}" method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                        title="Eliminar Producto">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bx bx-package display-4 d-block mb-2 text-secondary"></i>
                                <p class="mb-0 fs-6">No hay productos registrados en esta tienda.</p>
                                <small>Haz clic en "Nuevo Producto" para agregar tu primer artículo al catálogo.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($productos->hasPages())
            <div class="card-footer py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Mostrando <strong>{{ $productos->firstItem() }}</strong> a
                    <strong>{{ $productos->lastItem() }}</strong> de <strong>{{ $productos->total() }}</strong>
                    productos
                </div>
                <div>
                    {{ $productos->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "El producto y todas sus fotos asociadas serán eliminados de forma permanente.",
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
        });
    </script>
@endpush
