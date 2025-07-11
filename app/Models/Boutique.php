<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    use HasFactory;
    
    protected $fillable = ['nom', 'description', 'adresse', 'site_web', 'telephone', 'photo', 'etat'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function mvtStocks()
    {
        return $this->hasMany(MvtStock::class);
    }

    public function resumerJournaliers()
    {
        return $this->hasMany(ResumerJournalier::class);
    }
}
