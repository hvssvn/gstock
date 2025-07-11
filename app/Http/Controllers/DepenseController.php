<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Depense;
use Exception;

class DepenseController extends Controller
{
    use GenerateApiResponse;

    /**
     * List the specified resource in view.
     *
     * returns a view
     */

    public function list()
    {
        $roles = \App\Models\Role::where('etat', 'Activer')->get();
        $boutiques = \App\Models\Boutique::where('etat', 'Activer')->get();
        return view('depenses.list', compact('roles', 'boutiques'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Depense::with(['boutique'])->get();
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
            $depense = new Depense();
            $depense->motif = $request->motif;
            $depense->montant = $request->montant;
            $depense->mois = $request->mois;
            $depense->annee = $request->annee;
            $depense->etat = 'Activer';
            $depense->boutique_id = $request->boutique_id;
            if ($depense->save()) {
                return $this->successResponse($depense, 'Insertion réussie');
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
            $depense = Depense::findOrFail($id);
            $depense->motif = $request->motif;
            $depense->montant = $request->montant;
            $depense->mois = $request->mois;
            $depense->annee = $request->annee;
            $depense->etat = 'Activer';
            $depense->boutique_id = $request->boutique_id;
            if ($depense->save()) {
                return $this->successResponse($depense, 'Mise à jour réussie');
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
            $depense = Depense::findOrFail($id);
            $depense->etat = 'Activer';
            if ($depense->save()) {
                return $this->successResponse($depense, 'Activation réussie');
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
            $depense = Depense::findOrFail($id);
            $depense->etat = 'Désactiver';
            if ($depense->save()) {
                return $this->successResponse($depense, 'Désactivation réussie');
            }
        } catch (Exception $e) {
            return $this->errorResponse('Désactivation échouée', 500, $e->getMessage());
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
            $depense = Depense::findOrFail($id);
            if ($depense->delete()) {
                return $this->successResponse($depense, 'Suppression réussie');
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
            $depense = Depense::findOrFail($id);
             return $this->successResponse($depense, 'Ressource trouvée');
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