@extends('layouts.admin')

@section('title', 'Registrar Nueva Tienda')

@section('content')
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Información de la Nueva Tienda Online</h5>
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Volver al Listado
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.tenants.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-primary mb-3"><i class="bx bx-store me-1"></i> Datos de la Tienda</h6>

                        <!-- Nombre de la Tienda -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Nombre Comercial de la Tienda</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-store-alt"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Ej: Calzados Express" required autofocus />
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subdominio -->
                        <div class="mb-3">
                            <label class="form-label" for="subdomain">Subdominio Deseado</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-globe"></i></span>
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror"
                                    id="subdomain" name="subdomain" value="{{ old('subdomain') }}" placeholder="calzados"
                                    required autocomplete="off" />
                                <span class="input-group-text">.{{ env('CENTRAL_DOMAIN', 'mitienda.test') }}</span>
                            </div>
                            @error('subdomain')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="subdomain-feedback" class="mt-2">
                                <small class="text-muted"><i class="bx bx-info-circle me-1"></i> Solo letras minúsculas,
                                    números y guiones.</small>
                            </div>
                        </div>

                        <hr class="my-4" />

                        <h6 class="fw-bold text-primary mb-3"><i class="bx bx-user-circle me-1"></i> Administrador de la
                            Tienda</h6>

                        <!-- Nombre del Dueño -->
                        <div class="mb-3">
                            <label class="form-label" for="owner_name">Nombre del Administrador</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" class="form-control @error('owner_name') is-invalid @enderror"
                                    id="owner_name" name="owner_name" value="{{ old('owner_name') }}"
                                    placeholder="Ej: Juan Pérez" required />
                            </div>
                            @error('owner_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email del Dueño -->
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo Electrónico del Administrador</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="juan@ejemplo.com"
                                    required />
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Mínimo 8 caracteres" required />
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-check-shield"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Repite la contraseña" required />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="btn-submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Crear Tienda y Base de Datos
                            </button>
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subdomainInput = document.getElementById('subdomain');
            const feedbackEl = document.getElementById('subdomain-feedback');
            let debounceTimer = null;

            function checkAvailability() {
                const rawValue = subdomainInput.value.trim().toLowerCase();

                if (!rawValue) {
                    feedbackEl.innerHTML =
                        '<small class="text-muted"><i class="bx bx-info-circle me-1"></i> Solo letras minúsculas, números y guiones.</small>';
                    return;
                }

                feedbackEl.innerHTML =
                    '<span class="text-muted small"><i class="bx bx-loader-alt bx-spin me-1"></i> Verificando disponibilidad...</span>';

                fetch(`{{ route('admin.tenants.check-subdomain') }}?subdomain=${encodeURIComponent(rawValue)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'available') {
                            feedbackEl.innerHTML =
                                `<span class="badge bg-label-success py-2 px-3"><i class="bx bx-check-circle me-1"></i> ${data.message}</span>`;
                        } else if (data.status === 'taken') {
                            feedbackEl.innerHTML =
                                `<span class="badge bg-label-danger py-2 px-3"><i class="bx bx-x-circle me-1"></i> ${data.message}</span>`;
                        } else if (data.status === 'reserved') {
                            feedbackEl.innerHTML =
                                `<span class="badge bg-label-warning py-2 px-3"><i class="bx bx-error me-1"></i> ${data.message}</span>`;
                        } else if (data.status === 'invalid') {
                            feedbackEl.innerHTML =
                                `<span class="badge bg-label-secondary py-2 px-3"><i class="bx bx-info-circle me-1"></i> ${data.message}</span>`;
                        } else {
                            feedbackEl.innerHTML =
                                `<small class="text-muted"><i class="bx bx-info-circle me-1"></i> ${data.message || 'Solo letras minúsculas, números y guiones.'}</small>`;
                        }
                    })
                    .catch(err => {
                        feedbackEl.innerHTML =
                            '<span class="text-muted small"><i class="bx bx-error-circle me-1"></i> No se pudo verificar en este momento.</span>';
                    });
            }

            subdomainInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(checkAvailability, 350);
            });

            // Ejecutar al cargar si ya tiene valor previo (old input)
            if (subdomainInput.value.trim()) {
                checkAvailability();
            }
        });
    </script>
@endpush