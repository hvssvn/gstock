<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumerJournalier extends Model
{
    use HasFactory;
    
    protected $fillable = ['totalVente', 'totalDepense', 'mois', 'annee', 'etat', 'boutique_id'];

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }
}
