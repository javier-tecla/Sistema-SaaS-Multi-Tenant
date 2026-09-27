<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Categorías predefinidas para la tienda del tenant.
     */
    protected array $categorias = [
        ['nombre' => 'Calzado Deportivo', 'descripcion' => 'Zapatillas, botines y calzados para running, entrenamiento y deportes.'],
        ['nombre' => 'Ropa para Hombre', 'descripcion' => 'Camisetas, pantalones, camisas, chaquetas y moda masculina contemporánea.'],
        ['nombre' => 'Ropa para Mujer', 'descripcion' => 'Vestidos, blusas, faldas, abrigos y últimas tendencias de moda femenina.'],
        ['nombre' => 'Smartphones y Celulares', 'descripcion' => 'Teléfonos móviles inteligentes de última generación y accesorios.'],
        ['nombre' => 'Computación y Laptops', 'descripcion' => 'Notebooks, PCs de escritorio, componentes, monitores y almacenamiento.'],
        ['nombre' => 'Relojes y Joyería', 'descripcion' => 'Relojes análogos, digitales, pulseras, anillos y accesorios de lujo.'],
        ['nombre' => 'Hogar y Decoración', 'descripcion' => 'Artículos decorativos, iluminación, cuadros, textil y confort del hogar.'],
        ['nombre' => 'Electrodomésticos', 'descripcion' => 'Cafeteras, microondas, licuadoras, freidoras de aire y cocina moderna.'],
        ['nombre' => 'Belleza y Cuidado Personal', 'descripcion' => 'Perfumería, cosméticos, cuidado facial, capilar y bienestar.'],
        ['nombre' => 'Deportes y Fitness', 'descripcion' => 'Mancuernas, colchonetas, bandas elásticas y equipamiento deportivo.'],
        ['nombre' => 'Audio y Auriculares', 'descripcion' => 'Audífonos inalámbricos, parlantes Bluetooth y barras de sonido.'],
        ['nombre' => 'Videojuegos y Gaming', 'descripcion' => 'Consolas, controles, periféricos gamer, teclados mecánicos y mouses.'],
        ['nombre' => 'Muebles y Oficina', 'descripcion' => 'Sillas ergonómicas, escritorios modernos y estanterías organizadoras.'],
        ['nombre' => 'Juguetes y Niños', 'descripcion' => 'Juegos de mesa, didácticos, figuras de acción y entretenimiento infantil.'],
        ['nombre' => 'Herramientas y Ferretería', 'descripcion' => 'Taladros, destornilladores, kits de herramientas y bricolaje.'],
        ['nombre' => 'Alimentos y Bebidas', 'descripcion' => 'Snacks gourmet, cafés especiales, bebidas y productos de despensa.'],
        ['nombre' => 'Mascotas y Accesorios', 'descripcion' => 'Alimentos, juguetes, correas, camas y accesorios para perros y gatos.'],
        ['nombre' => 'Bolsos y Mochilas', 'descripcion' => 'Mochilas ejecutivas, bolsos urbanos, maletines y billeteras.'],
        ['nombre' => 'Fotografía y Cámaras', 'descripcion' => 'Cámaras mirrorless, lentes, trípodes y accesorios de producción audiovisual.'],
        ['nombre' => 'Accesorios para Autos', 'descripcion' => 'Soportes de celular, ambientadores, cargadores y herramientas viales.'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categoriaIds = [];

        foreach ($this->categorias as $catData) {
            $cat = Categoria::firstOrCreate(
                ['nombre' => $catData['nombre']],
                [
                    'slug' => Str::slug($catData['nombre']),
                    'descripcion' => $catData['descripcion'],
                    'estado' => true,
                ]
            );
            $categoriaIds[] = $cat->id;
        }

        for ($i = 1; $i <= 200; $i++) {
            $catId = fake()->randomElement($categoriaIds);
            Producto::factory()->create([
                'categoria_id' => $catId,
            ]);
        }
    }
}
