<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'imagenes'])
            ->latest()
            ->paginate(10);

        $ajuste = Ajuste::first();

        return view('tenant.productos.index', compact('productos', 'ajuste'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::where('estado', true)->orderBy('nombre')->get();
        $ajuste = Ajuste::first();

        return view('tenant.productos.create', compact('categorias', 'ajuste'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['nullable', 'string', 'max:50', 'unique:productos,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'estado' => ['required', 'boolean'],
            'descripcion' => ['nullable', 'string'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ], [
            'codigo.unique' => 'Ya existe un producto con este código / SKU.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'stock.required' => 'El stock inicial es obligatorio.',
            'imagenes.*.image' => 'Los archivos deben ser imágenes válidas.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 2MB.',
        ]);

        $slugBase = Str::slug($request->nombre);
        $count = Producto::where('slug', 'LIKE', "{$slugBase}%")->count();
        $validated['slug'] = $count > 0 ? "{$slugBase}-".($count + 1) : $slugBase;
        $validated['precio_compra'] = $request->precio_compra ?? 0;

        $producto = Producto::create($validated);

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagenFile) {
                $path = $imagenFile->store('productos', 'public');
                ProductoImagen::create([
                    'producto_id' => $producto->id,
                    'ruta' => $path,
                    'es_principal' => ($index === 0),
                ]);
            }
        }

        return redirect()->route('productos.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Producto Creado!',
                'text' => "El producto '{$request->nombre}' fue registrado exitosamente.",
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'imagenes']);
        $ajuste = Ajuste::first();

        return view('tenant.productos.show', compact('producto', 'ajuste'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $producto->load(['categoria', 'imagenes']);
        $categorias = Categoria::orderBy('nombre')->get();
        $ajuste = Ajuste::first();

        return view('tenant.productos.edit', compact('producto', 'categorias', 'ajuste'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('productos', 'codigo')->ignore($producto->id),
            ],
            'nombre' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'estado' => ['required', 'boolean'],
            'descripcion' => ['nullable', 'string'],
            'imagen_principal_id' => ['nullable', 'exists:producto_imagenes,id'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ], [
            'codigo.unique' => 'Ya existe otro producto con este código / SKU.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'imagenes.*.image' => 'Los archivos deben ser imágenes válidas.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 2MB.',
        ]);

        if ($request->nombre !== $producto->nombre) {
            $slugBase = Str::slug($request->nombre);
            $count = Producto::where('slug', 'LIKE', "{$slugBase}%")
                ->where('id', '!=', $producto->id)
                ->count();
            $validated['slug'] = $count > 0 ? "{$slugBase}-".($count + 1) : $slugBase;
        }

        $validated['precio_compra'] = $request->precio_compra ?? 0;
        $producto->update($validated);

        // Si se seleccionó una imagen principal existente
        if ($request->filled('imagen_principal_id')) {
            $producto->imagenes()->update(['es_principal' => false]);
            $producto->imagenes()->where('id', $request->imagen_principal_id)->update(['es_principal' => true]);
        }

        // Si se subieron nuevas imágenes adicionales
        if ($request->hasFile('imagenes')) {
            $hasExistingImages = $producto->imagenes()->count() > 0;
            $hasPrincipal = $producto->imagenes()->where('es_principal', true)->exists();

            foreach ($request->file('imagenes') as $index => $imagenFile) {
                $path = $imagenFile->store('productos', 'public');
                ProductoImagen::create([
                    'producto_id' => $producto->id,
                    'ruta' => $path,
                    'es_principal' => (! $hasPrincipal && ! $hasExistingImages && $index === 0),
                ]);
            }
        }

        return redirect()->route('productos.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Producto Actualizado!',
                'text' => 'Los cambios del producto fueron guardados exitosamente.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $nombre = $producto->nombre;

        // Eliminar archivos físicos de imágenes
        foreach ($producto->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Producto Eliminado!',
                'text' => "El producto '{$nombre}' y sus imágenes asociadas fueron eliminados.",
            ]);
    }

    /**
     * Eliminar una imagen individual de la galería del producto.
     */
    public function eliminarImagen(ProductoImagen $imagen)
    {
        $productoId = $imagen->producto_id;
        $wasPrincipal = $imagen->es_principal;

        // Eliminar del almacenamiento físico
        if (Storage::disk('public')->exists($imagen->ruta)) {
            Storage::disk('public')->delete($imagen->ruta);
        }

        $imagen->delete();

        // Si la imagen eliminada era la principal, reasignar a la primera restante
        if ($wasPrincipal) {
            $otra = ProductoImagen::where('producto_id', $productoId)->first();
            if ($otra) {
                $otra->update(['es_principal' => true]);
            }
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada correctamente',
            ]);
        }

        return back()->with('swal', [
            'icon' => 'success',
            'title' => '¡Imagen Eliminada!',
            'text' => 'La imagen fue removida de la galería.',
        ]);
    }
}
