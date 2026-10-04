<?php

namespace App\Models;

use App\Models\Cliente;
use App\Models\PedidoDetalle;
use Illuminate\Database\Eloquent\Model;




class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'codigo',
        'tipo_entrega',
        'direccion_entrega',
        'metodo_pago',
        'estado',
        'subtotal',
        'total',
        'notas',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function detalles()
    {
       return $this->hasMany(PedidoDetalle::class, 'pedido_id'); 
    }

    public static function generarCodigo(): string
    {
        $ultimoId = self::max('id') ?? 0;
        return 'PED-' . str_pad((string) ($ultimoId + 1), 4, '0', STR_PAD_LEFT);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => '<span class="badge bg-warning text-dark"><i class="bx bx-time me-1"></i> Pendiente</span>',
            'en_proceso' => '<span class="badge bg-primary"><i class="bx bx-loader-alt me-1"></i> En Proceso</span>',
            'completado' => '<span class="badge bg-success"><i class="bx bx-check-circle me-1"></i> Completado</span>',
            'cancelado' => '<span class="badge bg-danger"><i class="bx bx-x-circle me-1"></i> Cancelado</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->estado) . '</span>',
        };
    }

    public function getEstadoTextoAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En Proceso',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->estado),
        };
    }
}
