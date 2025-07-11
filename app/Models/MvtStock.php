<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvtStock extends Model
{
    use HasFactory;
    
    protected $fillable = ['qte', 'type', 'motif', 'produit_id', 'boutique_id'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }
}
