@extends('layouts.admin')

@section('title', 'Listado de Categorías')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bx bx-category me-2 text-primary"></i> Categorías de la Tienda
            </h5>
            <a href="{{ url('/categorias/create') }}" class="btn btn-primary">
                <i class="bx bx-plus-circle me-1"></i> Nueva Categoría
            </a>
        </div>
        <hr class="m-0" />

        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Nombre</th>
                        <th>Slug (URL)</th>
                        <th>Descripción</th>
                        <th style="width: 120px;">Estado</th>
                        <th style="width: 170px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($categorias as $categoria)
                        <tr>
                            <td class="fw-bold">{{ $categorias->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="badge bg-label-primary fs-6 fw-semibold">
                                    {{ $categoria->nombre }}
                                </span>
                            </td>
                            <td><code>{{ $categoria->slug }}</code></td>
                            <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                            <td>
                                @if ($categoria->estado)
                                    <span class="badge bg-label-success">
                                        <i class="bx bx-check-circle me-1"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-label-danger">
                                        <i class="bx bx-x-circle me-1"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/categorias/' . $categoria->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info me-1" title="Ver Detalles">
                                    <i class="bx bx-show-alt"></i>
                                </a>

                                <a href="{{ url('/categorias/' . $categoria->id . '/edit') }}"
                                    class="btn btn-sm btn-icon btn-outline-warning me-1" title="Editar Categoría">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                <form action="{{ url('/categorias/' . $categoria->id) }}" method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                        title="Eliminar Categoría">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bx bx-folder-open display-4 d-block mb-2 text-secondary"></i>
                                <p class="mb-0 fs-6">No hay categorías registradas en esta tienda.</p>
                                <small>Haz clic en "Nueva Categoría" para registrar la primera.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categorias->hasPages())
            <div class="card-footer py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Mostrando <strong>{{ $categorias->firstItem() }}</strong> a
                    <strong>{{ $categorias->lastItem() }}</strong> de <strong>{{ $categorias->total() }}</strong>
                    categorías
                </div>
                <div>
                    {{ $categorias->links() }}
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
                    text: "Esta categoría será eliminada de forma permanente.",
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