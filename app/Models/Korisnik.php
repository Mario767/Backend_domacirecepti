<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Carbon;

class Korisnik extends Model implements AuthenticatableContract
{
    use HasApiTokens, HasFactory, Authenticatable;

    protected $table = 'korisnici';
    protected $primaryKey = 'korisnik_id';

    protected $fillable = [
        'korisnicko_ime',
        'lozinka',
        'email',
        'uloga_id'
    ];

    // Dodaj accessor za created_at atribut


    public function getAuthPassword()
    {
        return $this->lozinka;
    }

    public function komentari()
    {
        return $this->hasMany(Komentar::class, 'idkorisnika');
    }
}

