<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\ResumerJournalier;
use Exception;

class ResumerJournalierController extends Controller
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
        return view('resumerjournaliers.list', compact('boutiques'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = ResumerJournalier::with(['boutique'])->get();
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
            $resumerJournalier = new ResumerJournalier();
            $resumerJournalier->totalVente = $request->totalVente;
            $resumerJournalier->totalDepense = $request->totalDepense;
            $resumerJournalier->mois = $request->mois;
            $resumerJournalier->annee = $request->annee;
            $resumerJournalier->etat = 'Activer';
            $resumerJournalier->boutique_id = $request->boutique_id;
            if ($resumerJournalier->save()) {
                return $this->successResponse($resumerJournalier, 'Récupération réussie');
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
            $resumerJournalier = ResumerJournalier::findOrFail($id);
            $resumerJournalier->totalVente = $request->totalVente;
            $resumerJournalier->totalDepense = $request->totalDepense;
            $resumerJournalier->mois = $request->mois;
            $resumerJournalier->annee = $request->annee;
            $resumerJournalier->etat = 'Activer';
            $resumerJournalier->boutique_id = $request->boutique_id;
            if ($resumerJournalier->save()) {
                return $this->successResponse($resumerJournalier, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
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
            $resumerJournalier = ResumerJournalier::findOrFail($id);
            $resumerJournalier->etat = 'Activer';
            if ($resumerJournalier->save()) {
                return $this->successResponse($resumerJournalier, 'Activation réussie');
            }
        } catch (Exception $e) {
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
            $resumerJournalier = ResumerJournalier::findOrFail($id);
            $resumerJournalier->etat = 'Désactiver';
            if ($resumerJournalier->save()) {
                return $this->successResponse($resumerJournalier, 'Désctivation réussie');
            }
        } catch (Exception $e) {
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
            $resumerJournalier = ResumerJournalier::findOrFail($id);
            if ($resumerJournalier->delete()) {
                return $this->successResponse($resumerJournalier, 'Suppression réussie');
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
            $resumerJournalier = ResumerJournalier::findOrFail($id);
             return $this->successResponse($resumerJournalier, 'Ressource trouvée');
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