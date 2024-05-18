<?php

namespace App\Models;
use App\Models\Korisnik;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentari';

    protected $fillable = ['sadržaj', 'datum_komentara', 'idrecepta', 'idkorisnika'];
    public function korisnik()
    {
        return $this->belongsTo(Korisnik::class, 'idkorisnika', 'korisnik_id');
    }

}
