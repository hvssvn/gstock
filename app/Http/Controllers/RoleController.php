<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Role;
use Exception;

use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use GenerateApiResponse;

        /**
     * List the specified resource in view.
     *
     * returns a view
     */
    public function list()
    {
        return view('roles.list');
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = Role::all();
            return $this->successResponse($data, 'Récupération réussie');
        } catch (Exception $e) {
            Log::error('Erreur ajout role : ' . $e->getMessage());
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
            $role = new Role();
            $role->nom = $request->nom;
            $role->description = $request->description;
            $role->etat = 'Activer';
            if ($role->save()) {
                return $this->successResponse($role, 'Insertion réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur insertion role : ' . $e->getMessage());
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
            $role = Role::findOrFail($id);
            $role->nom = $request->nom;
            $role->description = $request->description;
            $role->etat = 'Activer';
            if ($role->save()) {
                return $this->successResponse($role, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour role : ' . $e->getMessage());
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
            $role = Role::findOrFail($id);
            $role->etat = 'Activer';
            if ($role->save()) {
                return $this->successResponse($role, 'Activation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur activation role : ' . $e->getMessage());
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
            $role = Role::findOrFail($id);
            $role->etat = 'Désactiver';
            if ($role->save()) {
                return $this->successResponse($role, 'Désctivation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur désactivation role : ' . $e->getMessage());
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
            $role = Role::findOrFail($id);
            if ($role->delete()) {
                return $this->successResponse($role, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression role : ' . $e->getMessage());
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
            $role = Role::findOrFail($id);
             return $this->successResponse($role, 'Ressource trouvée');
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