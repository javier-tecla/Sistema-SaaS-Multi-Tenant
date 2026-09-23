<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AjusteController extends Controller
{
    public function index()
    {
        $ajuste = Ajuste::first();

        $divisasPath = public_path('divisas.json');
        $divisas = file_exists($divisasPath)
            ? json_decode(file_get_contents($divisasPath), true)
            : [];

        return view('tenant.ajustes.index', compact('ajuste', 'divisas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'divisa' => ['required', 'string', 'max:50'],
            'web' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ]);

        $ajuste = Ajuste::first();

        if ($request->hasFile('logo')) {
            if ($ajuste && $ajuste->logo && Storage::disk('public')->exists($ajuste->logo)) {
                Storage::disk('public')->delete($ajuste->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        if ($ajuste) {
            $ajuste->update($validated);
        } else {
            Ajuste::create($validated);
        }

        return redirect()->route('ajustes.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Ajustes Guardados!',
                'text' => 'Los datos de la tienda han sido guardados correctamente.',
            ]);
    }
}
