<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    BoutiqueController,
    CategorieController,
    DepenseController,
    HistoriqueController,
    LigneVenteController,
    MvtStockController,
    ProduitController,
    ResumerJournalierController,
    RoleController,
    UserController,
    VenteController
};

// Accueil
Route::get('/', fn () => view('welcome'));

// Dashboard (protégé)
Route::get('/dashboard', fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

// Profil utilisateur
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes CRUD protégées
Route::middleware('auth')->group(function () {
    $entities = [
        'boutiques' => BoutiqueController::class,
        'categories' => CategorieController::class,
        'depenses' => DepenseController::class,
        'historiques' => HistoriqueController::class,
        'ligneventes' => LigneVenteController::class,
        'mvtstocks' => MvtStockController::class,
        'produits' => ProduitController::class,
        'profiles' => ProfileController::class,
        'resumerjournaliers' => ResumerJournalierController::class,
        'roles' => RoleController::class,
        'users' => UserController::class,
        'ventes' => VenteController::class,
    ];

    foreach ($entities as $uri => $controller) {
        $prefix = str_replace('-', '_', $uri);

        Route::get("/$uri", [$controller, 'list'])->name("$prefix.list");
        Route::get("/$uri/index", [$controller, 'index'])->name("$prefix.index");
        Route::post("/$uri", [$controller, 'store']);
        Route::put("/$uri/{id}", [$controller, 'update']);
        Route::put("/$uri/{id}/active", [$controller, 'active']);
        Route::put("/$uri/{id}/inactive", [$controller, 'inactive']);
        Route::delete("/$uri/{id}", [$controller, 'destroy']);
        Route::get("/$uri/{id}", [$controller, 'show'])->where('id', '[0-9]+');
        Route::get("/$uri/getformdetails", [$controller, 'getformdetails']);
    }

});

require __DIR__.'/auth.php';
