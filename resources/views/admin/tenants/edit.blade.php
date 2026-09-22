@extends('layouts.admin')

@section('title', 'Editar Tienda')

@section('content')
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Tienda: {{ $tenant->name ?? $tenant->id }}</h5>
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Volver al Listado
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="fw-bold text-primary mb-3"><i class="bx bx-store me-1"></i> Datos de la Tienda</h6>

                        <!-- Nombre de la Tienda -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Nombre Comercial de la Tienda</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-store-alt"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $tenant->name ?? $tenant->id) }}"
                                    placeholder="Ej: Calzados Express" required autofocus />
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subdominio (Solo lectura) -->
                        <div class="mb-3">
                            <label class="form-label" for="subdomain">Subdominio / Identificador</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-globe"></i></span>
                                <input type="text" class="form-control bg-light" id="subdomain"
                                    value="{{ $tenant->id }}" readonly />
                                <span class="input-group-text">.{{ env('CENTRAL_DOMAIN', 'mitienda.test') }}</span>
                            </div>
                            <small class="form-text text-muted">El subdominio es el identificador único del tenant y no se
                                puede modificar.</small>
                        </div>

                        <!-- Base de Datos (Informativo) -->
                        <div class="mb-3">
                            <label class="form-label">Base de Datos Asignada</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-data"></i></span>
                                <input type="text" class="form-control bg-light"
                                    value="{{ config('tenancy.database.prefix') }}{{ $tenant->id }}" readonly />
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
                                    id="owner_name" name="owner_name"
                                    value="{{ old('owner_name', $adminUser->name ?? '') }}" placeholder="Ej: Juan Pérez"
                                    required />
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
                                    id="email" name="email" value="{{ old('email', $adminUser->email ?? '') }}"
                                    placeholder="juan@ejemplo.com" required />
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nueva Contraseña (Opcional) -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">Nueva Contraseña (Opcional)</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Dejar en blanco para no cambiar" />
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password_confirmation">Confirmar Nueva Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-check-shield"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Repite la nueva contraseña" />
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mb-3">Si no deseas cambiar la contraseña del administrador, deja
                            ambos campos vacíos.</small>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection