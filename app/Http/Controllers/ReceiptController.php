<?php

namespace App\Http\Controllers;
use App\Models\Ocjena;
use App\Models\Recept;
use App\Models\Komentar;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'naziv_recepta' => 'required|max:60',
            'opis' => 'required|max:1000',
            'vrstajela' => 'required|in:Glavno jelo,Predjelo,Desert',
            'slika' => 'required|image'
        ]);

        // Spremanje slike na server i dobivanje putanje
        $putanjaSlike = $request->file('slika')->store('slike', 'public');

        // Stvaranje novog recepta
        $recept = new Recept;
        $recept->naziv_recepta = $request->naziv_recepta;
        $recept->opis = $request->opis;
        $recept->vrstajela = $request->vrstajela;
        $recept->slika = 'storage/' . $putanjaSlike;

        $recept->korisnik_id = auth()->id(); // Dohvaćanje trenutnog ID korisnika
        $recept->save();

        // Vraćanje odgovora
        return response()->json([
            'message' => 'Recept je uspješno spremljen.',
            'recept' => $recept
        ]);
    }


    public function index(Request $request)
    {
        $recepti = Recept::where('vrstajela', 'Desert')
            ->with('korisnik:korisnik_id,korisnicko_ime') // Dohvaćanje samo korisnik_id i korisnicko_ime od povezanog korisnika
            ->orderBy('created_at', 'desc')
            ->paginate(12);


        // Transformacija kolekcije da uključi korisnicko_ime umjesto cijelog korisnika
        $recepti->getCollection()->transform(function ($recept) {
            $recept->korisnicko_ime = $recept->korisnik->korisnicko_ime;
            unset($recept->korisnik);
            return $recept;
        });

        return response()->json([
            'recepti' => $recepti
        ]);
    }

    public function glavno_jelo(Request $request)
    {
        $recepti = Recept::where('vrstajela', 'Glavno jelo')
            ->with('korisnik:korisnik_id,korisnicko_ime') // Dohvaćanje samo korisnik_id i korisnicko_ime od povezanog korisnika
            ->orderBy('created_at', 'desc')
            ->paginate(12);


        // Transformacija kolekcije da uključi korisnicko_ime umjesto cijelog korisnika
        $recepti->getCollection()->transform(function ($recept) {
            $recept->korisnicko_ime = $recept->korisnik->korisnicko_ime;
            unset($recept->korisnik);
            return $recept;
        });

        return response()->json([
            'recepti' => $recepti
        ]);
    }

    public function pred_jelo(Request $request)
    {
        $recepti = Recept::where('vrstajela', 'Predjelo')
            ->with('korisnik:korisnik_id,korisnicko_ime') // Dohvaćanje samo korisnik_id i korisnicko_ime od povezanog korisnika
            ->orderBy('created_at', 'desc')
            ->paginate(12);


        // Transformacija kolekcije da uključi korisnicko_ime umjesto cijelog korisnika
        $recepti->getCollection()->transform(function ($recept) {
            $recept->korisnicko_ime = $recept->korisnik->korisnicko_ime;
            unset($recept->korisnik);
            return $recept;
        });

        return response()->json([
            'recepti' => $recepti
        ]);
    }

    public function prikaziRecept($id)
    {
        $recept = Recept::where('recept_id', $id)->first();
        $recept->opis = str_replace("\n", "", $recept->opis);


        // Fetching the first 10 comments
        $komentari = Komentar::with(['korisnik' => function ($query) {
            $query->select('korisnik_id', 'korisnicko_ime');
        }])
            ->where('idrecepta', $id)
            ->orderBy('datum_komentara', 'desc')
            ->take(10)
            ->get();

        // Transforming the collection to include korisnicko_ime instead of the whole user
        $komentari->transform(function ($komentar) {
            $komentar->korisnicko_ime = $komentar->korisnik->korisnicko_ime;
            unset($komentar->korisnik, $komentar->komentar_id, $komentar->idkorisnika);

            return $komentar;
        });

        // Adding comments to the response
        $recept->komentari = $komentari;

        // Fetching the average rating
        $prosjecnaOcjena = Ocjena::where('recept_id', $id)->avg('ocjena');
        $prosjecnaOcjena = round($prosjecnaOcjena, 1); // Rounding to one decimal place

        // Adding the average rating to the response
        $recept->prosjecnaOcjena = $prosjecnaOcjena;

        return response()->json($recept);
    }


    public function top_jela(Request $request)
    {
        $kategorije = ['Glavno jelo', 'Predjelo',  'Desert'];
        $top_jela = [];

        foreach ($kategorije as $kategorija) {
            $jela = Recept::where('vrstajela', $kategorija)
                ->with('korisnik:korisnik_id,korisnicko_ime')
                ->withCount(['ocjene as prosjecna_ocjena' => function ($query) {
                    $query->select(DB::raw('coalesce(round(avg(ocjena), 1),0)'));
                }])
                ->orderBy('prosjecna_ocjena', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            // Transformacija kolekcije da uključi korisnicko_ime umjesto cijelog korisnika
            $jela->transform(function ($jelo) {
                $jelo->korisnicko_ime = $jelo->korisnik->korisnicko_ime;
                unset($jelo->korisnik);
                return $jelo;
            });

            $top_jela[$kategorija] = $jela;
        }

        return response()->json([
            'top_jela' => $top_jela
        ]);
    }


    public function mojiRecepti(Request $request)
    {
        // Dohvati trenutno prijavljenog korisnika
        $korisnik = $request->user();

        // Dohvati sve recepte od korisnika, sortirane po datumu kreiranja u obrnutom redoslijedu
        $recepti = Recept::where('korisnik_id', $korisnik->korisnik_id)
            ->orderBy('created_at', 'desc') // Sortiraj recepte po datumu kreiranja u obrnutom redoslijedu
            ->paginate(9);

        return response()->json($recepti);
    }


    public function obrisiRecept(Request $request)
    {
        $id = $request->input('recept_id');
        $recept = Recept::find($id);

        if (!$recept) {
            return response()->json(['message' => 'Recept nije pronađen'], 404);
        }

        // Provjera dozvola - ako je trenutno prijavljeni korisnik vlasnik recepta ili admin
        if ($recept->korisnik_id == Auth::id() || Auth::user()->uloga_id == 2) {
            // Obriši recept
            $recept->delete();

            // Obriši sve ocjene vezane uz recept
            Ocjena::where('recept_id', $id)->delete();

            // Obriši sve komentare vezane uz recept
            Komentar::where('idrecepta', $id)->delete();

            return response()->json(['message' => 'Recept uspješno obrisan'], 200);
        } else {
            return response()->json(['message' => 'Nemate dozvolu za brisanje ovog recepta'], 403);
        }
    }

    public function getReceptById($recept_id) {
        $recept = Recept::find($recept_id);

        if ($recept) {
            unset($recept->created_at);
            unset($recept->updated_at);
            unset($recept->korisnik_id);
            unset($recept->recept_id);
            return response()->json($recept, 200);
        } else {
            return response()->json(['error' => 'Recept not found'], 404);
        }
    }



    public function updateRecept(Request $request)
    {
        $request->validate([
            'naziv_recepta' => 'required|string|max:255',
            'opis' => 'required|string|max:1000',
            'vrstajela' => 'required|string|max:255',
            'slika' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $receptId = $request->input('recept_id');
        $recept = Recept::find($receptId);

        if (!$recept) {
            return redirect()->back()->with('error', 'Recept nije pronađen.');
        }
        $korisnik = auth()->user();

        // Provjeri je li trenutni korisnik vlasnik recepta ili admin
        if ($korisnik->korisnik_id !== $recept->korisnik_id && $korisnik->uloga_id !== 2) {
            return response()->json(['error' => 'Nemate dozvolu za ažuriranje ovog recepta.'], 403);
        }

        $recept->update([
            'naziv_recepta' => $request->input('naziv_recepta'),
            'opis' => $request->input('opis'),
            'vrstajela' => $request->input('vrstajela'),
        ]);

        if ($request->hasFile('slika')) {
            $novaSlika = $request->file('slika')->store('slike', 'public');
            $novaSlikaPutanja = 'storage/' . $novaSlika;

            // Usporedi putanje slika
            if ($recept->slika !== $novaSlikaPutanja) {
                // Obriši staru sliku
                $staraSlikaPutanja = str_replace('storage/', '', $recept->slika);
                Storage::delete('public/' . $staraSlikaPutanja);
                // Ažuriraj putanju slike u bazi podataka
                $recept->update(['slika' => $novaSlikaPutanja]);
            }
        }

        return response()->json(['success' => 'Recept je uspješno ažuriran.']);
    }

    public function receptiKorisnika($korisnik_id)
    {
        // Dohvati trenutno prijavljenog korisnika
        $korisnik = Auth::user();

        // Provjeri da li korisnik nije prijavljen ili nema ulogu 2 (Admin)
        if (!$korisnik || $korisnik->uloga_id !== 2) {
            // Ako korisnik nije prijavljen ili nije admin, vrati odgovarajuću poruku
            return response()->json(['message' => 'Nemate pravo pristupa.']);
        }

        // Dohvati sve recepte od korisnika, sortirane po datumu kreiranja u obrnutom redoslijedu
        $recepti = Recept::where('korisnik_id', $korisnik_id) // korisnik_id se sada dohvaća iz URL-a
        ->orderBy('created_at', 'desc') // Sortiraj recepte po datumu kreiranja u obrnutom redoslijedu
        ->paginate(9);

        return response()->json($recepti);
    }


    public function search($naziv_recepta) {
        $recepti = Recept::search($naziv_recepta, function($algolia, $query, $options) {
            $options['advancedSyntax'] = true;
            return $algolia->search($query, $options);
        })->paginate(12);

        $recepti->getCollection()->transform(function ($recept) {
            $recept->korisnicko_ime = $recept->korisnik->korisnicko_ime;
            unset($recept->korisnik);
            return $recept;
        });

        return response()->json([
            'recepti' => $recepti
        ]);
    }







}
