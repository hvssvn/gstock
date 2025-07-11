<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\LigneVente;
use Exception;

use Illuminate\Support\Facades\Log;

class LigneVenteController extends Controller
{
    use GenerateApiResponse;

        /**
     * List the specified resource in view.
     *
     * returns a view
     */
    public function list()
    {
        $ventes = \App\Models\Vente::where('etat', 'Activer')->get();
        $produits = \App\Models\Produit::where('etat', 'Activer')->get();
        return view('ligneventes.list', compact('ventes', 'produits'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = LigneVente::with(['vente', 'produit'])->get();
            return $this->successResponse($data, 'Insertion réussie');
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
            $ligneVente = new LigneVente();
            $ligneVente->qte = $request->qte;
            $ligneVente->vente_id = $request->vente_id;
            $ligneVente->produit_id = $request->produit_id;
            if ($ligneVente->save()) {
                return $this->successResponse($ligneVente, 'Récupération réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur ajout ligneVente : ' . $e->getMessage());
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
            $ligneVente = LigneVente::findOrFail($id);
            $ligneVente->qte = $request->qte;
            $ligneVente->vente_id = $request->vente_id;
            $ligneVente->produit_id = $request->produit_id;
            if ($ligneVente->save()) {
                return $this->successResponse($ligneVente, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour ligneVente : ' . $e->getMessage());
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
            $ligneVente = LigneVente::findOrFail($id);
            if ($ligneVente->delete()) {
                return $this->successResponse($ligneVente, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression ligneVente : ' . $e->getMessage());
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
            $ligneVente = LigneVente::findOrFail($id);
             return $this->successResponse($ligneVente, 'Ressource trouvée');
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