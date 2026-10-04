<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        $ajuste = Ajuste::first();

        // Obtenemos solo categorías activas con conteo de productos
        $categorias = Categoria::where('estado', true)
            ->withCount(['productos' => function ($query) {
                $query->where('estado', true);
            }])
            ->orderBy('nombre')
            ->get();

        // 3 Productos para el Carrusel de Portada (priorizando los que tienen imágenes)
        $carruselProductos = Producto::with(['categoria', 'imagenes'])
            ->where('estado', true)
            ->whereHas('imagenes')
            ->inRandomOrder()
            ->take(3)
            ->get();

        if ($carruselProductos->count() < 3) {
            $faltantes = 3 - $carruselProductos->count();
            $otros = Producto::with(['categoria', 'imagenes'])
                ->where('estado', true)
                ->whereNotIn('id', $carruselProductos->pluck('id'))
                ->take($faltantes)
                ->get();
            $carruselProductos = $carruselProductos->merge($otros);
        }

        $query = Producto::with(['categoria', 'imagenes'])
            ->where('estado', true);

        // Filtro por término de búsqueda (Nombre, SKU O Descripción)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('codigo', 'LIKE', "%{$buscar}%")
                    ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            });
        }

        // Filtro por Categoria
        if ( $request->filled('categoria')) {
            $query->whereHas('categoria', function ($q) use ($request) {
                    $q->where('slug', $request->categoria);
            });
        }

        // Si estamos en la portada principal, excluimos los 3 del carrusel para que no se repitan abajo
        if (!$request->filled('buscar') && !$request->filled('categoria') && $carruselProductos->isNotEmpty()) {
            $query->whereNotIn('id', $carruselProductos->pluck('id'));
        }

        // Ordenamiento
        if ( $request->order === 'precio_menor') {
            $query->orderBy('precio_venta', 'asc');
        }elseif ($request->orden === 'precio_mayor') {
            $query->orderBy('precio_venta', 'desc');
        }elseif ($request->orden === 'nombre') {
            $query->orderBy('nombre', 'asc');
        }else {
            $query->latest();
        }

        $productos = $query->paginate(12)->withQueryString();

        $categoriaActual = $request->filled('categoria')
            ?Categoria::where('slug', $request->categoria)->first()
            :null;

        return view('tenant.tienda.index', compact('ajuste', 'categorias', 'productos', 'categoriaActual', 'carruselProductos'));
    }

    /**
     * Ficha de detalle de un producto especifico.
     */
    public function show(string $slug)
    {
        $ajuste = Ajuste::first();
        $producto = Producto::with(['categoria', 'imagenes'])
            ->where('slug', $slug)
            ->where('estado', true)
            ->firstOrFail();

        // Productos relacionados de la misma categoría
        $relacionados = Producto::with(['categoria', 'imagenes'])
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('estado', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('tenant.tienda.show', compact('ajuste', 'producto', 'relacionados'));

    }
}
