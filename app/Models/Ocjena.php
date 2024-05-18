<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocjena extends Model
{
    use HasFactory;

    protected $table = 'ocjene';

    protected $fillable = [
        'korisnik_id',
        'recept_id',
        'ocjena'
    ];


}
