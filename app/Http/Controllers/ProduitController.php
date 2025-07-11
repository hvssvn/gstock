<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Produit;
use Exception;

use Illuminate\Support\Facades\Log;

class ProduitController extends Controller
{
    use GenerateApiResponse;

        /**
     * List the specified resource in view.
     *
     * returns a view
     */
    public function list()
    {
        $categories = \App\Models\Categorie::where('etat', 'Activer')->get();
        $boutiques = \App\Models\Boutique::where('etat', 'Activer')->get();
        return view('produits.list', compact('categories', 'boutiques'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Produit::with(['boutique', 'categorie'])->get();
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
            $produit = new Produit();
            $produit->nom = $request->nom;
            $produit->code = $request->code;
            $produit->pa = $request->pa;
            $produit->pu = $request->pu;
            $produit->qte = $request->qte;
            if ($request->hasFile('photo')) $produit->photo = $request->file('photo')->store('photos', 'public');
            $produit->etat = 'Activer';
            $produit->categorie_id = $request->categorie_id;
            $produit->boutique_id = $request->boutique_id;
            if ($produit->save()) {
                return $this->successResponse($produit, 'Récupération réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur ajout produit : ' . $e->getMessage());
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
            $produit = Produit::findOrFail($id);
            $produit->nom = $request->nom;
            $produit->code = $request->code;
            $produit->pa = $request->pa;
            $produit->pu = $request->pu;
            $produit->qte = $request->qte;
            if ($request->hasFile('photo')) $produit->photo = $request->file('photo')->store('photos', 'public');
            $produit->etat = 'Activer';
            $produit->categorie_id = $request->categorie_id;
            $produit->boutique_id = $request->boutique_id;
            if ($produit->save()) {
                return $this->successResponse($produit, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour produit : ' . $e->getMessage());
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
            $produit = Produit::findOrFail($id);
            $produit->etat = 'Activer';
            if ($produit->save()) {
                return $this->successResponse($produit, 'Activation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur activation produit : ' . $e->getMessage());
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
            $produit = Produit::findOrFail($id);
            $produit->etat = 'Désactiver';
            if ($produit->save()) {
                return $this->successResponse($produit, 'Désctivation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur désactivation produit : ' . $e->getMessage());
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
            $produit = Produit::findOrFail($id);
            if ($produit->delete()) {
                return $this->successResponse($produit, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression produit : ' . $e->getMessage());
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
            $produit = Produit::findOrFail($id);
             return $this->successResponse($produit, 'Ressource trouvée');
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