<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;

class Notifier
{
    // Envoie une notification à un utilisateur
    public static function vers($utilisateurId, $message, $type)
    {
        Notification::create([
            'utilisateur_id' => $utilisateurId,
            'message'        => $message,
            'type'           => $type,
        ]);
    }

    // Envoie une notification à tous les administrateurs (F24)
    public static function aLAdmin($message, $type)
    {
        $roleAdmin = Role::where('nom', 'admin')->first();
        foreach (User::where('role_id', $roleAdmin->id)->get() as $admin) {
            self::vers($admin->id, $message, $type);
        }
    }
}