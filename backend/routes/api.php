<?php

use App\Http\Controllers\AdminAnnonceController;
use App\Http\Controllers\AdminOffreController;
use App\Http\Controllers\AdminVisiteController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\VisiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\StatsController;

// ---------- Routes publiques ----------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);

// ---------- Utilisateur connecté ----------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Favoris
    Route::get('/favoris', [FavoriController::class, 'index']);
    Route::get('/favoris/ids', [FavoriController::class, 'ids']);
    Route::post('/favoris/{annonceId}', [FavoriController::class, 'basculer']);

    // Visites
    Route::get('/visites', [VisiteController::class, 'index']);
    Route::post('/visites', [VisiteController::class, 'store']);
    Route::patch('/visites/{id}/annuler', [VisiteController::class, 'annuler']);

    // Offres
    Route::get('/offres', [OffreController::class, 'index']);
    Route::post('/offres', [OffreController::class, 'store']);

        // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/non-lues', [NotificationController::class, 'nonLues']);
    Route::patch('/notifications/tout-lire', [NotificationController::class, 'toutLire']);
    Route::patch('/notifications/{id}/lue', [NotificationController::class, 'marquerLue']);
    Route::get('/stats/client', [StatsController::class, 'client']);
});

// ---------- Administrateur ----------
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Annonces
    Route::get('/annonces', [AdminAnnonceController::class, 'index']);
    Route::get('/annonces/{id}', [AdminAnnonceController::class, 'show']);
    Route::post('/annonces', [AdminAnnonceController::class, 'store']);
    Route::put('/annonces/{id}', [AdminAnnonceController::class, 'update']);
    Route::delete('/annonces/{id}', [AdminAnnonceController::class, 'destroy']);
    Route::patch('/annonces/{id}/statut', [AdminAnnonceController::class, 'changerStatut']);

    // Visites
    Route::get('/visites', [AdminVisiteController::class, 'index']);
    Route::patch('/visites/{id}', [AdminVisiteController::class, 'traiter']);

    // Offres
    Route::get('/offres', [AdminOffreController::class, 'index']);
    Route::patch('/offres/{id}', [AdminOffreController::class, 'traiter']);


    Route::get('/visites/aujourdhui', [AdminVisiteController::class, 'aujourdhui']);
        // Clients
    Route::get('/clients', [AdminClientController::class, 'index']);
    Route::put('/clients/{id}', [AdminClientController::class, 'update']);
    Route::patch('/clients/{id}/actif', [AdminClientController::class, 'basculerActif']);

        Route::get('/stats', [StatsController::class, 'admin']);
});