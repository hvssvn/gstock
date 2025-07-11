<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Historique;
use Exception;

class HistoriqueController extends Controller
{
    use GenerateApiResponse;
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Historique::all();
            return $this->successResponse($data, 'Récupération réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Récupération échouée', 500, $e->getMessage());
        }
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $historique = new Historique();
            $historique->model_name = $request->model_name;
            $historique->model_id = $request->model_id;
            $historique->user_id = $request->user_id;
            $historique->action = $request->action;
            $historique->date = $request->date;
            $historique->valeur_avant = $request->valeur_avant;
            $historique->valeur_apres = $request->valeur_apres;
            if ($historique->save()) {
                return $this->successResponse($historique, 'Insertion réussie');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Insertion échouée', 500, $e->getMessage());
        }
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $historique = Historique::findOrFail($id);
            $historique->model_name = $request->model_name;
            $historique->model_id = $request->model_id;
            $historique->user_id = $request->user_id;
            $historique->action = $request->action;
            $historique->date = $request->date;
            $historique->valeur_avant = $request->valeur_avant;
            $historique->valeur_apres = $request->valeur_apres;
            if ($historique->save()) {
                return $this->successResponse($historique, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Mise à jour échouée', 500, $e->getMessage());
        }
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $historique = Historique::findOrFail($id);
            if ($historique->delete()) {
                return $this->successResponse($historique, 'Suppression réussie');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Suppression échouée', 500, $e->getMessage());
        }
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $historique = Historique::findOrFail($id);
             return $this->successResponse($historique, 'Ressource trouvée');
        } catch (Exception $e) {
            return $this->errorResponse('Ressource non trouvée', 404, $e->getMessage());
        }
    }

        /**
     * Get related form details for foreign keys.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getformdetails()
    {
        try {

            return $this->successResponse([
                
            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }
}