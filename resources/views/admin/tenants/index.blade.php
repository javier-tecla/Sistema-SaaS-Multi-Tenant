@extends('layouts.admin')

@section('title', 'Listado de Tiendas (Tenants)')

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tiendas Registradas</h5>
                    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus-circle me-1"></i> Nueva Tienda
                    </a>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Nombre de la Tienda</th>
                                <th>Subdominio / URL</th>
                                <th>Base de Datos</th>
                                <th>Fecha de Creación</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($tenants as $tenant)
                                <tr>
                                    <!-- Número Correlativo -->
                                    <td class="text-center">
                                        <span class="badge badge-center rounded-pill bg-label-secondary fw-bold">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <!-- Nombre de la Tienda con Avatar -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <i class="bx bx-store-alt"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $tenant->name ?? $tenant->id }}</h6>
                                                <small class="text-muted">ID: {{ $tenant->id }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Subdominio / URL -->
                                    <td>
                                        @foreach ($tenant->domains as $domain)
                                            <a href="http://{{ $domain->domain }}" target="_blank"
                                                class="badge bg-label-info text-decoration-none py-2 px-3">
                                                <i class="bx bx-link-external me-1"></i> {{ $domain->domain }}
                                            </a>
                                        @endforeach
                                    </td>

                                    <!-- Base de Datos -->
                                    <td>
                                        <span class="badge bg-label-dark py-2 px-3">
                                            <i class="bx bx-data me-1"></i>
                                            {{ config('tenancy.database.prefix', 'tenant') }}{{ $tenant->id }}
                                        </span>
                                    </td>

                                    <!-- Fecha de Creación -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium text-heading">
                                                {{ $tenant->created_at ? $tenant->created_at->format('d/m/Y') : 'N/A' }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="bx bx-time-five me-1"></i>
                                                {{ $tenant->created_at ? $tenant->created_at->format('H:i A') : '' }}
                                            </small>
                                        </div>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            @if ($tenant->domains->first())
                                                <a href="http://{{ $tenant->domains->first()->domain }}" target="_blank"
                                                    class="btn btn-icon btn-sm btn-outline-primary" title="Visitar Tienda">
                                                    <i class="bx bx-globe"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('admin.tenants.edit', $tenant->id) }}"
                                                class="btn btn-icon btn-sm btn-outline-warning" title="Editar Tienda">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>

                                            <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST"
                                                class="d-inline form-delete-tenant">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-outline-danger btn-delete-tenant"
                                                    data-name="{{ $tenant->name ?? $tenant->id }}" title="Eliminar Tienda">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bx bx-info-circle bx-md mb-2 d-block text-secondary"></i>
                                        <p class="mb-0">No hay tiendas registradas aún.</p>
                                        <small>Haz clic en <strong>"Nueva Tienda"</strong> para crear la primera.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-tenant').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const tenantName = this.getAttribute('data-name');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Se eliminará la tienda "${tenantName}" y toda su base de datos de forma permanente.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff3e1d',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'Sí, eliminar tienda',
                    cancelButtonText: 'Cancelar',
                    scrollbarPadding: false,
                    heightAuto: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush