<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recept extends Model
{
    use HasFactory, Searchable;

    protected $table = 'recepti';

    protected $fillable = ['naziv_recepta', 'opis', 'vrstajela', 'slika', 'korisnik_id'];

    public function korisnik()
    {
        return $this->belongsTo(Korisnik::class, 'korisnik_id');
    }
    protected $primaryKey = 'recept_id';

    public function komentari()
    {
        return $this->hasMany(Komentar::class, 'idrecepta');
    }

    public function ocjene()
    {
        return $this->hasMany(Ocjena::class, 'recept_id');
    }
}
