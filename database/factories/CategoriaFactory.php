<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    protected static array $nombresCategorias = [
        'Calzado Deportivo',
        'Ropa para Hombre',
        'Ropa para Mujer',
        'Smartphones y Celulares',
        'Computación y Laptops',
        'Relojes y Joyería',
        'Hogar y Decoración',
        'Electrodomésticos',
        'Belleza y Cuidado Personal',
        'Deportes y Fitness',
        'Audio y Auriculares',
        'Videojuegos y Gaming',
        'Muebles y Oficina',
        'Juguetes y Niños',
        'Herramientas y Ferretería',
        'Alimentos y Bebidas',
        'Mascotas y Accesorios',
        'Bolsos y Mochilas',
        'Fotografía y Cámaras',
        'Accesorios para Autos',
        'Lentes y Óptica',
        'Salud y Bienestar',
        'Instrumentos Musicales',
        'Moda Infantil',
        'Cuidado de la Piel',
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = fake()->unique()->randomElements(self::$nombresCategorias) ?? fake()->words(2, true);

        return [
            'nombre' => ucfirst($nombre),
            'slug' => Str::slug($nombre),
            'descripcion' => fake()->sentence(12),
            'estado' => fake()->boolean(90), // 90% activas
        ];
    }
}
