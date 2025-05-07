<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Update the user's location coordinates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request)
    {
        try {
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            // Converter para float para garantir que são valores numéricos
            $latitude = (float) $validated['latitude'];
            $longitude = (float) $validated['longitude'];

            // Validar se os valores estão dentro de limites razoáveis
            if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coordenadas inválidas. Latitude deve estar entre -90 e 90, e longitude entre -180 e 180.'
                ], 422);
            }

            $user->latitude = $latitude;
            $user->longitude = $longitude;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Localização atualizada com sucesso',
                'data' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar localização: ' . $e->getMessage()
            ], 500);
        }
    }
}
