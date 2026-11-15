<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Liste des notifications de l'utilisateur connecté (R7)
    public function index(Request $request)
    {
        return response()->json(
            Notification::where('utilisateur_id', $request->user()->id)
                ->orderBy('date_creation', 'desc')
                ->get()
        );
    }

    // Nombre de notifications non lues (pour la pastille de la barre)
    public function nonLues(Request $request)
    {
        return response()->json([
            'total' => Notification::where('utilisateur_id', $request->user()->id)->where('lu', false)->count(),
        ]);
    }

    // Marquer une notification comme lue
    public function marquerLue(Request $request, $id)
    {
        $notif = Notification::where('utilisateur_id', $request->user()->id)->findOrFail($id);
        $notif->update(['lu' => true]);

        return response()->json($notif);
    }

    // Tout marquer comme lu
    public function toutLire(Request $request)
    {
        Notification::where('utilisateur_id', $request->user()->id)->update(['lu' => true]);

        return response()->json(['message' => 'ok']);
    }
}