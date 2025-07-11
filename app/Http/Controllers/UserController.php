<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\GenerateApiResponse;
use App\Models\User;
use Exception;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
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
        return view('users.list', compact('roles', 'boutiques'));
    }
    
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = User::with(['boutique', 'role'])->get();
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
            $user = new User();
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->adresse = $request->adresse;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->date_naissance = $request->date_naissance;
            $user->telephone = $request->telephone;
            if ($request->hasFile('photo')) $user->photo = $request->file('photo')->store('photos', 'public');
            $user->cni = $request->cni;
            $user->etat = 'Activer';
            $user->boutique_id = $request->boutique_id;
            $user->role_id = $request->role_id;
            if ($user->save()) {
                return $this->successResponse($user, 'Insertion réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur ajout utilisateur : ' . $e->getMessage());
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
            $user = User::findOrFail($id);
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->adresse = $request->adresse;
            $user->email = $request->email;
            if ($request->filled('password')) $user->password = Hash::make($request->password);
            $user->date_naissance = $request->date_naissance;
            $user->telephone = $request->telephone;
            if ($request->hasFile('photo')) $user->photo = $request->file('photo')->store('photos', 'public');
            $user->cni = $request->cni;
            $user->etat = 'Activer';
            $user->boutique_id = $request->boutique_id;
            $user->role_id = $request->role_id;
            if ($user->save()) {
                return $this->successResponse($user, 'Mise à jour réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur mise à jour utilisateur : ' . $e->getMessage());
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
            $user = User::findOrFail($id);
            $user->etat = 'Activer';
            if ($user->save()) {
                return $this->successResponse($user, 'Activation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur activation utilisateur : ' . $e->getMessage());
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
            $user = User::findOrFail($id);
            $user->etat = 'Désactiver';
            if ($user->save()) {
                return $this->successResponse($user, 'Désctivation réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur désactivation utilisateur : ' . $e->getMessage());
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
            $user = User::findOrFail($id);
            if ($user->delete()) {
                return $this->successResponse($user, 'Suppression réussie');
            }
        } catch (Exception $e) {
            Log::error('Erreur suppression utilisateur : ' . $e->getMessage());
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
            $user = User::findOrFail($id);
             return $this->successResponse($user, 'Ressource trouvée');
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