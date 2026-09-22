@extends('layouts.admin')

@section('content')
    <div class="row">
        <!-- Tarjeta de Bienvenida -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title textprimary">¡Bienvenido de nuevo, {{ Auth::user()->name }}! </h5>
                            <p class="mb-0">
                                Este es el panel central de administración de tu plataforma SaaS. Desde aquí puedes
                                monitorear todas las tiendas online registradas y su actividad.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                alt="Bienvenida" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
