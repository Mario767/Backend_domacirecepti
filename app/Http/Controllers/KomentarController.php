<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Komentar;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function dodajkomentar(Request $request)
    {
        // Provjerite je li korisnik prijavljen
        if ($user = $request->user()) {
            $request->validate([
                'idrecepta' => 'required',
                'sadržaj' => 'required|max:500',
            ]);

            $komentar = new Komentar;
            $komentar->idkorisnika = $user->korisnik_id; // Koristite ID autentificiranog korisnika
            $komentar->idrecepta = $request->idrecepta;
            $komentar->sadržaj = $request->sadržaj;
            $komentar->save();

            return response()->json(['message' => 'Komentar uspješno dodan!'], 201);
        } else {
            return response()->json(['error' => 'Morate biti prijavljeni da biste dodali komentar.'], 403);
        }
    }



    public function provjera(Request $request)
    {
        if ($user = $request->user()) {
            $userId = $user->korisnik_id;
            return response()->json(['message' => 'Uspješna provjera! Korisnik je autentificiran.', 'userId' => $userId], 200);
        } else {
            return response()->json(['error' => 'Neuspješna provjera. Korisnik nije autentificiran.'], 403);
        }
    }

    public function prikaziKomentare($id)
    {
        $komentari = Komentar::where('idrecepta', $id)
            ->orderBy('datum_Komentara', 'desc')
            ->take(10) // Ograničava dohvat na prvih 10 komentara
            ->get();
        return response()->json($komentari);
    }


}
