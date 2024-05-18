<?php

namespace App\Http\Controllers;
use App\Models\Ocjena;


use Illuminate\Http\Request;

class OcjenaController extends Controller
{
    public function dodajocjenu(Request $request)
    {
        if ($user = $request->user()) {
            $userId = $user->korisnik_id;
            $receptId = $request->input('recept_id');
            $novaOcjena = $request->input('ocjena');

            // Provjerite postoji li već ocjena za ovaj recept od ovog korisnika
            $ocjena = Ocjena::where('korisnik_id', $userId)->where('recept_id', $receptId)->first();

            if ($ocjena) {
                // Ako ocjena već postoji, ažurirajte je
                $ocjena->ocjena = $novaOcjena;
                $ocjena->save();
            } else {
                // Ako ocjena ne postoji, stvorite novu
                $ocjena = Ocjena::create([
                    'korisnik_id' => $userId,
                    'recept_id' => $receptId,
                    'ocjena' => $novaOcjena,
                ]);
            }

            return response()->json($ocjena, 201);
        } else {
            return response()->json(['error' => 'Korisnik nije autentificiran'], 401);
        }
    }

}
