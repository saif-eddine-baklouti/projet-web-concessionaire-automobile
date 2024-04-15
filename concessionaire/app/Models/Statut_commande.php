<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statut_commande extends Model
{
    use HasFactory;
    protected $fillable = [
        'statut_en',
        'statut_fr'
    ];
}