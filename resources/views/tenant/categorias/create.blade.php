@extends('layouts.admin')

@section('title', 'Registrar Categoría')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bx bx-plus-circle me-2 text-primary"></i> Registrar Nueva Categoría
                </h5>
                <a href="{{ url('/categorias') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Volver al Listado
                </a>
            </div>
            <hr class="m-0" />

            <div class="card-body pt-4">
                <form action="{{ url('/categorias') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="nombre">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-category"></i></span>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                placeholder="Ej: Calzados Deportivos, Bebidas, Ropa" required autofocus />
                        </div>
                        @error('nombre')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-detail"></i></span>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                id="descripcion" name="descripcion" rows="3" 
                                placeholder="Breve descripción de los productos de esta categoría">{{ old('descripcion') }}</textarea>
                        </div>
                        @error('descripcion')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="estado">Estado de la Categoría <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-toggle-left"></i></span>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>🟢 Activo (Visible en tienda)</option>
                                <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>🔴 Inactivo (Pausado)</option>
                            </select>
                        </div>
                        @error('estado')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-save me-1"></i> Guardar Categoría
                        </button>
                        <a href="{{ url('/categorias') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection