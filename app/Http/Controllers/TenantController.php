<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->latest()->get();

        return view('admin.tenants.index', compact('tenants'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Verifica la disponibilidad del subdominio en tiempo real
     */
    public function checkSubdomain(Request $request)
    {
        $subdomain = strtolower(trim($request->query('subdomain', '')));

        if (empty($subdomain)) {
            return response()->json([
                'status' => 'empty',
                'message' => 'Solo letras minúsculas, números y guiones.',
            ]);
        }

        if (strlen($subdomain) < 3) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'El subdominio debe tener al menos 3 caracteres.',
            ]);
        }

        if (! preg_match('/^[a-z0-9-]+$/', $subdomain)) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Solo se permiten letras minúsculas, números y guiones (sin espacios).',
            ]);
        }

        $reserved = ['admin', 'api', 'www', 'mail', 'root', 'superadmin', 'app', 'dashboard', 'login', 'register'];
        if (in_array($subdomain, $reserved)) {
            return response()->json([
                'status' => 'reserved',
                'message' => '⚠️ Este subdominio es una palabra reservada del sistema.',
            ]);
        }

        $exists = Tenant::where('id', $subdomain)->exists();

        if ($exists) {
            return response()->json([
                'status' => 'taken',
                'message' => "❌ El subdominio '{$subdomain}' ya está ocupado.",
            ]);
        }

        return response()->json([
            'status' => 'available',
            'message' => "✅ !El subdominio '{$subdomain}' está disponible!",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validaciones estrictas
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subdomain' => [
            'required',
            'string',
            'min:3',
            'max:50',
            'regex:/^[a-z0-9-]+$/',
            'unique:tenants,id',
            Rule::notIn(['admin', 'api', 'www', 'mail', 'root', 'superadmin']), 
            ],
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'subdomain.regex' => 'El subdominio solo puede contener letras minúsculas, números y guiones.',
            'subdomain.not_in' => 'Este subdominio es una palabra reservada del sistema.',
            'subdomain.unique' => 'Este dubdominio ya está registrado por otra tienda.',
        ]);

        // 2. Crear el Tenant (Inicia el pipeline automático de Stancl Tenancy)
        $tenant = Tenant::create([
            'id' => $request->subdomain,
            'name' => $request->name,
        ]);

        // 3. Crear el Dominio asociado (ej: zapateria.mitienda.test)
        $centralDomain = env('CENTRAL_DOMAIN', 'mitienda.test');
        $tenant->domains()->create([
            'domain' => $request->subdomain . '.' . $centralDomain,
        ]);

        // 4. Crear el usuario Administrador DENTRO de la base de datos de esta tienda
        $tenant->run(function () use ($request) {
            User::create([
                'name' => $request->owner_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        });

        return redirect()->route('admin.tenants.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Tienda Creada!',
                'text' => "La tienda '{$request->name}' se ha creado exitosamente con su base de datos propia.",
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $tenant->load('domains');

        // Obtener el usuario Administrador dentro de la base de datos del tenant
        $adminUser = $tenant->run(function () {
            return User::first();
        });

        return view('admin.tenants.edit', compact('tenant', 'adminUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // 1. Actualizar nombre de la tienda
        $tenant->update([
            'name' => $request->name,
        ]);

        // 2. Actualizar el Administrador dentro de la Base de Datos del Tenant
        $tenant->run(function () use ($request) {
            $admin = User::first();
            if ($admin) {
                $data = [
                    'name' => $request->owner_name,
                    'email' => $request->email,
                ];

                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                $admin->update($data);
            }
        });

        return redirect()->route('admin.tenants.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Tienda Actualizada!',
                'text' => "La tienda '{$tenant->name}' y los datos de su administrador fueron actualizados correctamente.",
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $storeName = $tenant->name ?? $tenant->id;
        $tenant->delete(); // Elimina tenant, dominios y la base de datos asociada

        return redirect()->route('admin.tenants.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => '¡Tienda Eliminada!',
                'text' => "La tienda '{$storeName}' y su base de datos fueron eliminadas.",
            ]);
    }
}
