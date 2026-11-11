<?php

use App\Http\Controllers\AdminAnnonceController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AuthController;
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

    // Les routes favoris, visites, offres et notifications viendront ici
});

// ---------- Routes réservées à l'administrateur ----------
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'Bienvenue administrateur']);
    });

    // CRUD des annonces
    Route::get('/annonces', [AdminAnnonceController::class, 'index']);
    Route::post('/annonces', [AdminAnnonceController::class, 'store']);
    Route::put('/annonces/{id}', [AdminAnnonceController::class, 'update']);
    Route::delete('/annonces/{id}', [AdminAnnonceController::class, 'destroy']);
    Route::patch('/annonces/{id}/statut', [AdminAnnonceController::class, 'changerStatut']);

    // Les routes visites, offres, clients et stats viendront ici
});