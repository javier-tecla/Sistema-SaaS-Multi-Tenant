@extends('layouts.admin')

@section('title', 'Ajustes de la Tienda')

@section('content')
    <div class="row">
        <div class="col-xl-9 col-lg-11 mx-auto">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bx bx-cog me-2 text-primary"></i> Configuración General de la Tienda
                    </h5>
                    <span class="badge bg-label-primary fs-6">
                        <i class="bx bx-store me-1"></i> {{ tenant('name') ?? tenant('id') }}
                    </span>
                </div>
                <hr class="m-0" />

                <div class="card-body pt-4">
                    <form action="{{ url('/ajustes') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Nombre de la Tienda -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="nombre">Nombre de la Tienda <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-store"></i></span>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre"
                                        value="{{ old('nombre', $ajuste->nombre ?? (tenant('name') ?? '')) }}"
                                        placeholder="Ej: Calzados Express" required />
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Divisa (Select desde divisas.json) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="divisa">Divisa / Moneda <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-dollar-circle"></i></span>
                                    <select class="form-select @error('divisa') is-invalid @enderror" id="divisa"
                                        name="divisa" required>
                                        <option value="">-- Selecciona una divisa --</option>
                                        @foreach ($divisas as $code => $currency)
                                            <option value="{{ $code }}"
                                                {{ old('divisa', $ajuste->divisa ?? 'USD') == $code ? 'selected' : '' }}>
                                                {{ $code }} - {{ $currency['name'] }}
                                                ({{ $currency['symbol_native'] ?? $currency['symbol'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('divisa')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label class="form-label" for="descripcion">Descripción</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-detail"></i></span>
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                                    id="descripcion" name="descripcion"
                                    value="{{ old('descripcion', $ajuste->descripcion ?? '') }}"
                                    placeholder="Breve descripción o eslogan de la tienda" />
                            </div>
                            @error('descripcion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label class="form-label" for="direccion">Dirección Física <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-map"></i></span>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion"
                                    value="{{ old('direccion', $ajuste->direccion ?? '') }}"
                                    placeholder="Ej: Av. 6 de Agosto #456" required />
                            </div>
                            @error('direccion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="telefono">Teléfono / WhatsApp <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono"
                                        value="{{ old('telefono', $ajuste->telefono ?? '') }}"
                                        placeholder="Ej: +591 70000000" required />
                                </div>
                                @error('telefono')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">Correo Electrónico <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $ajuste->email ?? '') }}"
                                        placeholder="contacto@mitienda.com" required />
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sitio Web -->
                        <div class="mb-3">
                            <label class="form-label" for="web">Sitio Web (URL)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-globe"></i></span>
                                <input type="url" class="form-control @error('web') is-invalid @enderror"
                                    id="web" name="web" value="{{ old('web', $ajuste->web ?? url('/')) }}"
                                    placeholder="https://mitienda.com" />
                            </div>
                            @error('web')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo y Previsualización -->
                        <div class="row align-items-center mt-3 mb-4 p-3 bg-lighter rounded">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label class="form-label fw-bold" for="logo">
                                    <i class="bx bx-image me-1"></i> Logo de la Tienda
                                </label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                    id="logo" name="logo" accept="image/*" onchange="previewLogo(event)" />
                                <small class="text-muted">Formatos: JPG, PNG, WEBP, SVG (Máximo 2MB)</small>
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 text-center">
                                <label class="form-label d-block text-muted small mb-1">Vista Previa del Logo</label>
                                <div class="border rounded p-2 bg-white shadow-sm d-inline-flex align-items-center justify-content-center"
                                    style="min-width: 140px; height: 85px; background-color: #fcfdfd;">
                                    @if (!empty($ajuste->logo))
                                        <img id="logo-preview" src="{{ tenant_asset($ajuste->logo) }}" alt="Logo"
                                            class="img-fluid rounded"
                                            style="max-height: 70px; max-width: 130px; object-fit: contain;" />
                                    @else
                                        <img id="logo-preview"
                                            src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='60' viewBox='0 0 120 60' fill='none'><rect width='120' height='60' rx='6' fill='%23f5f5f9'/><path d='M38 38L48 26L58 34L68 22L82 38H38Z' fill='%23696cff' fill-opacity='0.25'/><circle cx='46' cy='22' r='4' fill='%23696cff'/><text x='60' y='52' font-family='sans-serif' font-size='9' fill='%238592a3' text-anchor='middle'>Subir Logo</text></svg>"
                                            alt="Sin Logo" class="img-fluid rounded"
                                            style="max-height: 70px; max-width: 130px; object-fit: contain;" />
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-save me-1"></i> Guardar Ajustes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewLogo(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logo-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
