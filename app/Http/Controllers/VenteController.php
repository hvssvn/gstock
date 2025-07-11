<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Vente;
use Exception;

use Illuminate\Support\Facades\Log;

class VenteController extends Controller
{
    use GenerateApiResponse;

        /**
     * List the specified resource in view.
     *
     * returns a view
     */
    public function list()
    {
        $boutiques = \App\Models\Boutique::where('etat', 'Activer')->get();
        return view('ventes.list', compact('boutiques'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Vente::with(['boutique'])->get();
            return $this->successResponse($data, 'Récupération réussie');
        } catch (Exception $e) {
            Log::error('Erreur récupération vente : ' . $e->getMessage());
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
            $vente = new Vente();
            $vente->numero = $request->numero;
            $vente->qte = $request->qte;
            $vente->date = $request->date;
            $vente->boutique_id = $request->boutique_id;
            $vente->etat = 'Activer';
            if ($vente->save()) {
                return $this->successResponse($vente, 'Insertion réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur insertion vente : ' . $e->getMessage());
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
            $vente = Vente::findOrFail($id);
            $vente->numero = $request->numero;
            $vente->qte = $request->qte;
            $vente->date = $request->date;
            $vente->boutique_id = $request->boutique_id;
            $vente->etat = 'Activer';
            if ($vente->save()) {
                return $this->successResponse($vente, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour vente : ' . $e->getMessage());
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
            $vente = Vente::findOrFail($id);
            $vente->etat = 'Activer';
            if ($vente->save()) {
                return $this->successResponse($vente, 'Activation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur activation vente : ' . $e->getMessage());
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
            $vente = Vente::findOrFail($id);
            $vente->etat = 'Désactiver';
            if ($vente->save()) {
                return $this->successResponse($vente, 'Désctivation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur désactivation vente : ' . $e->getMessage());
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
            $vente = Vente::findOrFail($id);
            if ($vente->delete()) {
                return $this->successResponse($vente, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression vente : ' . $e->getMessage());
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
            $vente = Vente::findOrFail($id);
             return $this->successResponse($vente, 'Ressource trouvée');
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