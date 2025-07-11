<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\MvtStock;
use Exception;

use Illuminate\Support\Facades\Log;

class MvtStockController extends Controller
{
    use GenerateApiResponse;

        /**
     * List the specified resource in view.
     *
     * returns a view
     */
    public function list()
    {
        $produits = \App\Models\Produit::where('etat', 'Activer')->get();
        $boutiques = \App\Models\Boutique::where('etat', 'Activer')->get();
        return view('mvtstocks.list', compact('produits', 'boutiques'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = MvtStock::with(['produit', 'boutique'])->get();
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
            $mvtStock = new MvtStock();
            $mvtStock->qte = $request->qte;
            $mvtStock->type = $request->type;
            $mvtStock->motif = $request->motif;
            $mvtStock->produit_id = $request->produit_id;
            $mvtStock->boutique_id = $request->boutique_id;
            if ($mvtStock->save()) {
                return $this->successResponse($mvtStock, 'Insertion réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur ajout mvtStock : ' . $e->getMessage());
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
            $mvtStock = MvtStock::findOrFail($id);
            $mvtStock->qte = $request->qte;
            $mvtStock->type = $request->type;
            $mvtStock->motif = $request->motif;
            $mvtStock->produit_id = $request->produit_id;
            $mvtStock->boutique_id = $request->boutique_id;
            if ($mvtStock->save()) {
                return $this->successResponse($mvtStock, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour mvtStock : ' . $e->getMessage());
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
            $mvtStock = MvtStock::findOrFail($id);
            if ($mvtStock->delete()) {
                return $this->successResponse($mvtStock, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression mvtStock : ' . $e->getMessage());
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
            $mvtStock = MvtStock::findOrFail($id);
             return $this->successResponse($mvtStock, 'Ressource trouvée');
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