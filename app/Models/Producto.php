<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'codigo',
        'nombre',
        'slug',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'estado'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'estado' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class);
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImagen::class)->where('es_principal', true);
    }

    /**
     * Retorna la imagen principal o la primera disponible.
     */
    public function getFotoPrincipalAttribute()
    {
        return $this->imagenes->firstWhere('es_principal', true) ?? $this->imagenes->first();
    }
}
