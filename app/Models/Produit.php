<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    
    protected $fillable = ['nom', 'code', 'pa', 'pu', 'qte', 'photo', 'etat', 'categorie_id', 'boutique_id'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function lignesVente()
    {
        return $this->hasMany(LigneVente::class);
    }

    public function mvtStocks()
    {
        return $this->hasMany(MvtStock::class);
    }
}
