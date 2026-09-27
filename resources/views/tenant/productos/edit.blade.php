@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto">
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bx bx-edit-alt me-2 text-primary"></i> Editar Producto: {{ $producto->nombre }}
                </h5>
                <a href="{{ url('/productos') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Volver al Catálogo
                </a>
            </div>
            <hr class="m-0" />

            <div class="card-body pt-4">
                <form action="{{ url('/productos/' . $producto->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Información Principal -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="codigo">Código / SKU <small class="text-muted">(Opcional)</small></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-barcode"></i></span>
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                    id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}" 
                                    placeholder="Ej: PROD-001" />
                            </div>
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="nombre">Nombre del Producto <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-package"></i></span>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                    id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" 
                                    required autofocus />
                            </div>
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="categoria_id">Categoría <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-category"></i></span>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                    id="categoria_id" name="categoria_id" required>
                                    <option value="">-- Seleccionar Categoría --</option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->id }}" 
                                            {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('categoria_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="estado">Estado del Producto <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-toggle-left"></i></span>
                                <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                    <option value="1" {{ old('estado', (string)$producto->estado) === '1' || old('estado', $producto->estado) == 1 ? 'selected' : '' }}>🟢 Activo (Visible para clientes)</option>
                                    <option value="0" {{ old('estado', (string)$producto->estado) === '0' || old('estado', $producto->estado) == 0 ? 'selected' : '' }}>🔴 Inactivo (Borrador / Oculto)</option>
                                </select>
                            </div>
                            @error('estado')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Precios e Inventario -->
                    <div class="card bg-lighter border-0 p-3 mb-3">
                        <h6 class="fw-bold mb-3 text-secondary">
                            <i class="bx bx-dollar-circle me-1"></i> Precios e Inventario (Moneda: {{ $ajuste->divisa ?? '$' }})
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="precio_compra">Precio de Costo / Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $ajuste->divisa ?? '$' }}</span>
                                    <input type="number" step="0.01" min="0" 
                                        class="form-control @error('precio_compra') is-invalid @enderror" 
                                        id="precio_compra" name="precio_compra" 
                                        value="{{ old('precio_compra', $producto->precio_compra) }}" />
                                </div>
                                @error('precio_compra')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="precio_venta">Precio de Venta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text fw-bold text-success">{{ $ajuste->divisa ?? '$' }}</span>
                                    <input type="number" step="0.01" min="0" 
                                        class="form-control fw-bold @error('precio_venta') is-invalid @enderror" 
                                        id="precio_venta" name="precio_venta" 
                                        value="{{ old('precio_venta', $producto->precio_venta) }}" required />
                                </div>
                                @error('precio_venta')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="stock">Stock <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-layer"></i></span>
                                    <input type="number" min="0" 
                                        class="form-control @error('stock') is-invalid @enderror" 
                                        id="stock" name="stock" 
                                        value="{{ old('stock', $producto->stock) }}" required />
                                </div>
                                @error('stock')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Galería Actual del Producto -->
                    <div class="mb-4">
                        <label class="form-label fw-bold d-block mb-2">
                            <i class="bx bx-images me-1 text-primary"></i> Galería Actual de Fotos
                        </label>

                        @if ($producto->imagenes->count() > 0)
                            <div class="row g-3 p-3 bg-lighter rounded mb-3">
                                @foreach ($producto->imagenes as $img)
                                    <div class="col-6 col-sm-4 col-md-3 col-lg-2" id="img-card-{{ $img->id }}">
                                        <div class="card h-100 border shadow-none text-center position-relative">
                                            <img src="{{ tenant_asset($img->ruta) }}" alt="Foto Producto" 
                                                class="card-img-top rounded-top" style="height: 110px; object-fit: cover;" />
                                            
                                            <div class="card-body p-2 d-flex flex-column justify-content-between">
                                                <div class="form-check form-check-inline justify-content-center mb-1">
                                                    <input class="form-check-input" type="radio" name="imagen_principal_id" 
                                                        id="radio_img_{{ $img->id }}" value="{{ $img->id }}" 
                                                        {{ $img->es_principal ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold" for="radio_img_{{ $img->id }}">
                                                        Principal
                                                    </label>
                                                </div>

                                                <button type="button" class="btn btn-xs btn-outline-danger w-100 mt-1" 
                                                    onclick="eliminarFoto({{ $img->id }})">
                                                    <i class="bx bx-trash me-1"></i> Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mb-3">
                                <i class="bx bx-info-circle"></i> Marca el botón de radio "Principal" para definir cuál será la foto de portada.
                            </small>
                        @else
                            <div class="alert alert-secondary text-center py-3 mb-3">
                                <i class="bx bx-image-alt fs-4 d-block mb-1"></i>
                                Este producto aún no tiene fotos en su galería.
                            </div>
                        @endif

                        <!-- Subir Nuevas Fotos Adicionales -->
                        <label class="form-label fw-semibold" for="imagenes">
                            <i class="bx bx-cloud-upload me-1"></i> Agregar Más Fotos a la Galería
                        </label>
                        <div class="border border-2 border-dashed rounded p-3 text-center bg-light">
                            <input type="file" class="form-control @error('imagenes') is-invalid @enderror @error('imagenes.*') is-invalid @enderror" 
                                id="imagenes" name="imagenes[]" multiple accept="image/*" onchange="previewNewImages(event)" />
                            <small class="text-muted d-block mt-2">Puedes seleccionar varias imágenes a la vez.</small>
                        </div>
                        @error('imagenes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('imagenes.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <!-- Preview de las nuevas imágenes añadidas -->
                        <div id="new-preview-container" class="row g-2 mt-3" style="display: none;">
                            <div class="col-12">
                                <span class="text-muted small fw-semibold" id="new-preview-count">0 fotos nuevas seleccionadas</span>
                            </div>
                            <div id="new-preview-grid" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label class="form-label" for="descripcion">Descripción Detallada</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-detail"></i></span>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                id="descripcion" name="descripcion" rows="4" 
                                placeholder="Descripción del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>
                        @error('descripcion')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-save me-1"></i> Guardar Cambios
                        </button>
                        <a href="{{ url('/productos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewNewImages(event) {
        const files = event.target.files;
        const container = document.getElementById('new-preview-container');
        const grid = document.getElementById('new-preview-grid');
        const countSpan = document.getElementById('new-preview-count');

        grid.innerHTML = '';

        if (files.length === 0) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';
        countSpan.textContent = `${files.length} nueva(s) foto(s) por subir:`;

        Array.from(files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'position-relative rounded border p-1 bg-white shadow-sm';
                col.style.width = '95px';
                col.style.height = '95px';

                col.innerHTML = `
                    <img src="${e.target.result}" alt="Nueva Foto" class="rounded w-100 h-100" style="object-fit: cover;" />
                    <span class="badge bg-success position-absolute top-0 start-0 m-1" style="font-size: 0.6rem;">Nueva</span>
                `;
                grid.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    function eliminarFoto(imagenId) {
        Swal.fire({
            title: '¿Eliminar imagen?',
            text: 'Esta foto se eliminará inmediatamente de la galería.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('/productos/imagen') }}/${imagenId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById(`img-card-${imagenId}`);
                        if (card) {
                            card.remove();
                        }
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminada!',
                            text: 'La imagen ha sido eliminada.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar la imagen.'
                    });
                });
            }
        });
    }
</script>
@endpush
