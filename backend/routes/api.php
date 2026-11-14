<?php

use App\Http\Controllers\AdminAnnonceController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriController;
use Illuminate\Support\Facades\Route;

// ---------- Routes publiques (sans token) ----------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);

// ---------- Routes pour un utilisateur connecté (token obligatoire) ----------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Favoris (les ids doivent être déclarés avant la route avec paramètre)
    Route::get('/favoris', [FavoriController::class, 'index']);
    Route::get('/favoris/ids', [FavoriController::class, 'ids']);
    Route::post('/favoris/{annonceId}', [FavoriController::class, 'basculer']);

    // Les routes visites, offres et notifications viendront ici
});

// ---------- Routes réservées à l'administrateur ----------
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'Bienvenue administrateur']);
    });

    // CRUD des annonces
    Route::get('/annonces', [AdminAnnonceController::class, 'index']);
    Route::get('/annonces/{id}', [AdminAnnonceController::class, 'show']);
    Route::post('/annonces', [AdminAnnonceController::class, 'store']);
    Route::put('/annonces/{id}', [AdminAnnonceController::class, 'update']);
    Route::delete('/annonces/{id}', [AdminAnnonceController::class, 'destroy']);
    Route::patch('/annonces/{id}/statut', [AdminAnnonceController::class, 'changerStatut']);

    // Les routes visites, offres, clients et stats viendront ici
});