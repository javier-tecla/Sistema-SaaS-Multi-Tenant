@extends('layouts.admin')

@section('title', 'Detalles de la Categoría')

@section('content')
<div class="row">
    <div class="col-xl-7 col-lg-9 mx-auto">
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bx bx-detail me-2 text-primary"></i> Detalle de la Categoría
                </h5>
                <a href="{{ url('/categorias') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Volver al Listado
                </a>
            </div>
            <hr class="m-0" />

            <div class="card-body pt-4">
                <div class="table-responsive">
                    <table class="table table-bordered mb-4">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">ID</th>
                                <td>{{ $categoria->id }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Nombre</th>
                                <td>
                                    <span class="badge bg-label-primary fs-6 fw-bold">
                                        {{ $categoria->nombre }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Slug (URL amigable)</th>
                                <td><code>{{ $categoria->slug }}</code></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Descripción</th>
                                <td>{{ $categoria->descripcion ?? 'Sin descripción registrada' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Estado</th>
                                <td>
                                    @if ($categoria->estado)
                                        <span class="badge bg-label-success">
                                            <i class="bx bx-check-circle me-1"></i> Activo (Visible)
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger">
                                            <i class="bx bx-x-circle me-1"></i> Inactivo (Pausado)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Fecha de Registro</th>
                                <td>{{ $categoria->created_at ? $categoria->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Última Actualización</th>
                                <td>{{ $categoria->updated_at ? $categoria->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ url('/categorias') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Regresar
                    </a>
                    <a href="{{ url('/categorias/' . $categoria->id . '/edit') }}" class="btn btn-warning">
                        <i class="bx bx-edit-alt me-1"></i> Editar Esta Categoría
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection