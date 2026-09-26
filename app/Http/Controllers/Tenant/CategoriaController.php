<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::latest()->paginate(10);

        return view('tenant.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['required', 'boolean'],
        ],[
            'nombre.unique' => 'Ya existe una categoría registrada con este nombre en tu tienda.',
        ]);

        $validated['slug'] = Str::slug($request->nombre);

        Categoria::create($validated);

        return redirect()->route('categorias.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Categoría Creada!',
                'text' => "La cetagoría '{$request->nombre}' fue registrada exitosamente.",
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        return view('tenant.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('tenant.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'estado' => ['required', 'boolean'],
        ],[
            'nombre.unique' => 'Ya existe otra categoría con este nombre en tu tienda.',
        ]);

        $validated['slug'] = Str::slug($request->nombre);

        $categoria->update($validated);

        return redirect()->route('categorias.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Categoría Actualizada!',
                'text' => 'Los cambios fueron guardados exitosamente.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $nombre = $categoria->nombre;
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Categoría Eliminada!',
                'text' => "La categoría '{$nombre}' fue eliminada.",
            ]);
    }
}
