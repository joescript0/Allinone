<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Page de réception pour le partage de position client
     */
    public function client_partager(Request $request)
    {
        $data = $request->input('data');
        $client = null;

        if ($data) {
            try {
                // Décoder les données base64
                $decoded = json_decode(base64_decode($data), true);
                if ($decoded && is_array($decoded)) {
                    $client = $decoded;
                }
            } catch (\Exception $e) {
                // En cas d'erreur de décodage
                $client = null;
            }
        }

        return view('interfaces.client_partage', ['client' => $client]);
    }
}
