<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KorisnikController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\OcjenaController;











Route::get('/', function () {
    return view('welcome');
});




Route::post('/registerUser', [KorisnikController::class, 'registracijaKorisnika']);
Route::middleware('auth:sanctum')->post('/recepti', [ReceiptController::class, 'store']);
Route::get('/dohvatirec', [ReceiptController::class, 'index']);
Route::middleware('auth:sanctum')->post('/ocjena',  [OcjenaController::class, 'dodajocjenu']);
// U vašem api.php datoteci
Route::get('/recept/{id}',  [ReceiptController::class, 'prikaziRecept']);
Route::get('/dohvatiglavno', [ReceiptController::class, 'glavno_jelo']);
Route::get('/dohvatipredjelo', [ReceiptController::class, 'pred_jelo']);
Route::get('/topjela', [ReceiptController::class, 'top_jela']);
Route::middleware('auth:sanctum')->get('/mojirecepti', [ReceiptController::class, 'mojiRecepti']);
Route::middleware('auth:sanctum')->post('/obrisirec', [ReceiptController::class, 'obrisiRecept']);
Route::get('/dohvatirec/{id}',  [ReceiptController::class, 'getReceptById']);
Route::middleware('auth:sanctum')->post('/updaterec', [ReceiptController::class, 'updateRecept']);
Route::middleware('auth:sanctum')->get('/korisnici', [KorisnikController::class, 'getUsersByRole']);
Route::middleware('auth:sanctum')->get('/korisniciadmin', [KorisnikController::class, 'getUsersByRole2']);
Route::middleware('auth:sanctum')->post('/adminuloge', [KorisnikController::class, 'promijeniUlogu']);
Route::middleware('auth:sanctum')->post('/delkorisnik', [KorisnikController::class, 'obrisiKorisnika']);
Route::middleware('auth:sanctum')->get('/receptikorisnika/{korisnik_id}', [ReceiptController::class, 'receptiKorisnika']);
Route::middleware('auth:sanctum')->post('/komentari', [KomentarController::class, 'dodajkomentar']);
Route::middleware('auth:sanctum')->get('/provjera', [KomentarController::class, 'provjera']);
Route::middleware('auth:sanctum')->get('/provjerakorinsika', [ReceiptController::class, 'dohvatiUlogaId']);
Route::get('/recept/{id}/komentari', [KomentarController::class, 'prikaziKomentare']);
Route::middleware('auth:sanctum')->get('/pronadikorinsika/{korisnicko_ime}', [KorisnikController::class, 'podaciKorisnika']);
Route::post('/sendcode', [KorisnikController::class, 'sendConfirmationCode']);
Route::post('/confirmcode', [KorisnikController::class, 'confirmCode']);
Route::post('/prijavi',[KorisnikController::class,'prijavaKorisnika']);
Route::middleware('auth:sanctum')->post('/odjavi',[KorisnikController::class,'odjavaKorisnika']);
Route::get('/search/{naziv_recepta}/',[ReceiptController::class,'search']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request ){
    return $request->user();
});

Route::get('/getUsers',[KorisnikController::class,'dohvatiKorisnike']);
Route::post('/promoteUser/{id}',[KorisnikController::class,'promovirajKorisnika']);
Route::get('/isUserLogged',[KorisnikController::class,'isUserLogged']);



