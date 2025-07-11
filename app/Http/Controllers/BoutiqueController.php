<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Boutique;
use Exception;

use Illuminate\Support\Facades\Log;

class BoutiqueController extends Controller
{
    use GenerateApiResponse;

        /**
     * List the specified resource in view.
     *
     * returns a view
     */
    public function list()
    {
        return view('boutiques.list');
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Boutique::all();
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
            $boutique = new Boutique();
            $boutique->nom = $request->nom;
            $boutique->description = $request->description;
            $boutique->adresse = $request->adresse;
            $boutique->site_web = $request->site_web;
            $boutique->telephone = $request->telephone;
            $boutique->photo = $request->photo;
            $boutique->etat = "Activer";
            if ($boutique->save()) {
                return $this->successResponse($boutique, 'Insertion réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur insertion boutique : ' . $e->getMessage());
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
            $boutique = Boutique::findOrFail($id);
            $boutique->nom = $request->nom;
            $boutique->description = $request->description;
            $boutique->adresse = $request->adresse;
            $boutique->site_web = $request->site_web;
            $boutique->telephone = $request->telephone;
            $boutique->photo = $request->photo;
            $boutique->etat = "Activer";
            if ($boutique->save()) {
                return $this->successResponse($boutique, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour boutique : ' . $e->getMessage());
            return $this->errorResponse('Mise à jour échouée', 500, $e->getMessage());
        }
    }

        /**
     * Activate the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function active(Request $request, $id)
    {
        try {
            $boutique = Boutique::findOrFail($id);
            $boutique->etat = 'Activer';
            if ($boutique->save()) {
                return $this->successResponse($boutique, 'Activation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur activation boutique : ' . $e->getMessage());
            return $this->errorResponse('Activation échouée', 500, $e->getMessage());
        }
    }

        /**
     * Unactivate the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function inactive(Request $request, $id)
    {
        try {
            $boutique = Boutique::findOrFail($id);
            $boutique->etat = 'Désactiver';
            if ($boutique->save()) {
                return $this->successResponse($boutique, 'Désctivation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur désactivation boutique : ' . $e->getMessage());
            return $this->errorResponse('Désctivation échouée', 500, $e->getMessage());
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
            $boutique = Boutique::findOrFail($id);
            if ($boutique->delete()) {
                return $this->successResponse($boutique, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression boutique : ' . $e->getMessage());
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
            $boutique = Boutique::findOrFail($id);
             return $this->successResponse($boutique, 'Ressource trouvée');
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