<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    protected static array $marcas = [
        'Nike',
        'Adidas',
        'Samsung',
        'Apple',
        'Sony',
        'LG',
        'Puma',
        'Xiaomi',
        'Lenovo',
        'Dell',
        'HP',
        'Casio',
        'Philips',
        'Bosch',
        'Logitech',
        'Asus',
        'Reebok',
        'Canon',
        'JBL',
        'Huawei',
        'Under Armour',
        'GoPro',
        'Timberland',
        'Levis',
        'Zara',
        'Ray-Ban',
        'Garmin',
        'Fossil',
        'Tommy Hilfiger',
    ];

    protected static array $articulos = [
        'Zapatillas Deportivas',
        'Camiseta Dry-Fit',
        'Pantalón Cargo',
        'Smartwatch Deportivo',
        'Laptop Gamer',
        'Audífonos Inalámbricos Bluetooth',
        'Mochila Impermeable',
        'Teclado Mecánico RGB',
        'Mouse Ergonómico',
        'Monitor Curvo 27"',
        'Cafetera Express',
        'Aspiradora Robot',
        'Perfume Eau de Parfum 100ml',
        'Chaqueta Cortavientos',
        'Cámara Digital Mirrorless',
        'Silla Ergonómica Ejecutiva',
        'Taladro Percutor Inalámbrico',
        'Parlante Portátil Waterproof',
        'Tablet Touchscreen 10.5"',
        'Bicicleta de Montaña R29',
        'Smart TV 55" 4K UHD',
        'Reloj Cronógrafo Deportivo',
        'Gafas de Sol Polarizadas',
        'Sudadera con Capucha',
        'Freidora de Aire Digital',
        'Consola Portátil',
        'Disco SSD Externo 1TB',
        'Cargador Rápido GaN 65W',
        'Billetera de Cuero Genuino',
        'Set de Mancuernas Ajustables',
        'Termo de Acero Inoxidable 1L',
        'Lámpara LED de Escritorio',
        'Guitarra Acústica',
        'Microondas Digital',
        'Sandalias Cómodas de Cuero',
    ];

    protected static array $variantes = [
        'Pro Max',
        'Ultra Edition',
        'Sport Series',
        'V2 Black',
        'Air Lite',
        'Carbon Edition',
        'Plus 2026',
        'Elite',
        'Titanium',
        'Classic',
        'Studio Wireless',
        'Speed X',
        'Compact',
        'Signature',
        'Infinity',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $articulo = fake()->randomElement(self::$articulos);
        $marca = fake()->randomElement(self::$marcas);
        $variante = fake()->randomElement(self::$variantes);

        $nombre = "{$articulo} {$marca} {$variante}";
        $slug = Str::slug($nombre).'-'.fake()->unique()->numberBetween(1000, 99999);

        $precioCompra = fake()->randomFloat(2, 10, 350);
        // Margen de ganancia entre 25% y 75%
        $margen = fake()->randomFloat(2, 1.25, 1.75);
        $precioVenta = round($precioCompra * $margen, 2);

        return [
            'categoria_id' => Categoria::inRandomOrder()->value('id') ?? Categoria::factory(),
            'codigo' => 'SKU-'.strtoupper(fake()->bothify('??-####')),
            'nombre' => $nombre,
            'slug' => $slug,
            'descripcion' => fake()->paragraph(3),
            'precio_compra' => $precioCompra,
            'precio_venta' => $precioVenta,
            'stock' => fake()->numberBetween(0, 150),
            'estado' => fake()->boolean(92), // 92% activos
        ];
    }
}
