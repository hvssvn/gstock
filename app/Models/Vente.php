<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Vente extends Model
{
    use HasFactory;
    
    protected $fillable = ['numero', 'qte', 'date', 'etat'];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function lignesVente()
    {
        return $this->hasMany(LigneVente::class);
    }
}
