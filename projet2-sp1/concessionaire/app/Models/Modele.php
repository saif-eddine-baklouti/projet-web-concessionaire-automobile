<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;
    protected $fillable = [
        'modele_en',
        'modele_fr',
        'modele_marque_id'
      
    ];
    
}
