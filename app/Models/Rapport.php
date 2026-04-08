<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    protected $table = 'rapports';

    protected $fillable = [
        'commercial_id',
        'date_rapport',
        'total_ventes',
        'total_versement',
        'observations'
    ];

    protected $casts = [
        'date_rapport' => 'date',
        'total_ventes' => 'decimal:2',
        'total_versement' => 'decimal:2'
    ];

    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function getTotalVentesFormattedAttribute()
    {
        return number_format($this->total_ventes, 0, ',', ' ') . ' FCFA';
    }
}
