<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'model_name', 'model_id', 'user_id', 'action',
        'date', 'valeur_avant', 'valeur_apres'
    ];

    protected $casts = [
        'valeur_avant' => 'array',
        'valeur_apres' => 'array',
        'date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
