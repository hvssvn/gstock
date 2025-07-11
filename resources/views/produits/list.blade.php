@extends('layouts.app')

@section('title')
    Produits
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Produits</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des produits</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Produits</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                            class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i
                            class="fa fa-times"></i></a>
                </div>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addProduitModal">
                    <i class="fa fa-plus"></i> Ajouter un produit
                </button>

                <table id="produits-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prix d'Achat</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th>Catégorie</th>
                            <th>Boutique</th>
                            <th>Etat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->
    </div>
    <!-- end #content -->

    <!-- Modal -->
    <div class="modal fade" id="addProduitModal" tabindex="-1" role="dialog" aria-labelledby="addProduitModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addProduitForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion produit</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="produit_id" id="produit_id">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Prix d'Achat</label>
                    <input type="text" name="pa" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Prix Unitaire</label>
                    <input type="text" name="pu" class="form-control">
                </div>
                <div class="form-group">
                    <label>Quantité</label>
                    <input type="text" name="qte" class="form-control">
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo" class="form-control-file" accept="image/*">
                    <br><img id="produit-photo-preview" src="" alt="Photo actuelle" style="max-width: 150px; display: none; border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="categorie_id" class="form-control" required>
                        <option value="">Sélectionnez une boutique</option>
                        @foreach($boutiques as $boutique)
                            <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="boutique_id" class="form-control">
                        <option value="">Aucune</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Enregistrer</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            </div>
          </div>
        </form>
      </div>
    </div>

@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#produits-table').DataTable({
                ajax: {
                    url: '{{ route("produits.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'nom' },
                    { data: 'pa' },
                    { data: 'pu' },
                    { data: 'qte' },
                    {
                        data: 'categorie.nom',
                        render: function(data) {
                            return data ?? '—';
                        }
                    },
                    {
                        data: 'boutique.nom',
                        render: function(data) {
                            return data ?? '—';
                        }
                    },
                    {
                        data: 'etat',
                        render: function(data, type, row) {
                            if (data === "Activer") return `<i class="fa fa-check-circle" style="color: green;"></i>`;
                            else if (data === "Désactiver") return `<i class="fa fa-times-circle" style="color: red;"></i>`;
                            return '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let active_action = "";
                            if (data.etat === "Désactiver")
                                active_action = `<button class="btn btn-info btn-sm active-produit" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i> Activer
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-produit" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i> Désactiver
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-produit" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-produit" data-id="${row.id}">
                                    <i class="fa fa-trash"></i> Supprimer
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: "{{ asset('assets/datable-fr-FR.json') }}"
                }
            });
        });

        $('[data-target="#addProduitModal"]').click(function (e) {
            $('#addProduitForm')[0].reset();
            $('#produit_id').val('');
            $('#produit-photo-preview').hide();
            $('#addProduitModal .modal-title').text("Ajouter un produit");
        });

        $('#addProduitForm').submit(function(e) {
            e.preventDefault();

            const id = $('#produit_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/produits/${id}` : `/produits`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addProduitModal').modal('hide');
                    $('#addProduitForm')[0].reset();
                    $('#produit_id').val('');
                    $('#produits-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un produit!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-produit', function() {
            const produitId = $(this).data('id');

            $.ajax({
                url: `/produits/${produitId}`,
                type: 'GET',
                success: function(response) {
                    const produit = response.data;

                    // Remplir le formulaire
                    $('#produit_id').val(produit.id);
                    $('input[name="nom"]').val(produit.nom);
                    $('input[name="code"]').val(produit.code);
                    $('input[name="pa"]').val(produit.pa);
                    $('input[name="pu"]').val(produit.pu);
                    $('input[name="qte"]').val(produit.qte);
                    $('select[name="categorie_id"]').val(produit.categorie_id);
                    $('select[name="boutique_id"]').val(produit.boutique_id);
                    
                    if (produit.photo) $('#produit-photo-preview').attr('src', `/storage/${produit.photo}`).show();
                    else $('#produit-photo-preview').hide();

                    $('#addProduitModal .modal-title').text("Modifier le produit");
                    $('#addProduitModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un produit!',
                        text: "Erreur lors du chargement du produit"
                    });
                }
            });
        });

        $(document).on('click', '.delete-produit', function () {
            const produitId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer ce produit?',
                text: 'Cette action est irreversible!',
                icon: 'error',
                buttons: {
                    cancel: {
                        text: 'Annuler',
                        value: null,
                        visible: true,
                        className: 'btn btn-default',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Oui, Supprimer',
                        value: true,
                        visible: true,
                        className: 'btn btn-danger',
                        closeModal: true
                    }
                }
            }).then(function (value) {
                if (value) {
                    $.ajax({
                        url: `/produits/${produitId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#produits-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer le produit.', 'error');
                        }
                    });
                }
            });;
        });

        $(document).on('click', '.active-produit', function () {
            const produitId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer ce produit?',
                icon: 'error',
                buttons: {
                    cancel: {
                        text: 'Annuler',
                        value: null,
                        visible: true,
                        className: 'btn btn-default',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Oui, Activer',
                        value: true,
                        visible: true,
                        className: 'btn btn-warning',
                        closeModal: true
                    }
                }
            }).then(function (value) {
                if (value) {
                    $.ajax({
                        url: `/produits/${produitId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#produits-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible d\'activer le produit.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-produit', function () {
            const produitId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver ce produit?',
                icon: 'error',
                buttons: {
                    cancel: {
                        text: 'Annuler',
                        value: null,
                        visible: true,
                        className: 'btn btn-default',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Oui, Désactiver',
                        value: true,
                        visible: true,
                        className: 'btn btn-warning',
                        closeModal: true
                    }
                }
            }).then(function (value) {
                if (value) {
                    $.ajax({
                        url: `/produits/${produitId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#produits-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver le produit.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
