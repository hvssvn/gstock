@extends('layouts.app')

@section('title')
    Mouvements de stocks
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Mouvements de stocks</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des mouvements de stocks</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Mouvements de stocks</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addMvtStockModal">
                    <i class="fa fa-plus"></i> Ajouter un mouvement de stock
                </button>

                <table id="mvtstocks-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Quantité</th>
                            <th>Type</th>
                            <th>Motif</th>
                            <th>Produit</th>
                            <th>Boutique</th>
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
    <div class="modal fade" id="addMvtStockModal" tabindex="-1" role="dialog" aria-labelledby="addMvtStockModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addMvtStockForm">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion mouvement de stock</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="mvtstock_id" id="mvtstock_id">
                <div class="form-group">
                    <label>Quantité</label>
                    <input type="text" name="qte" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control" required>
                        <option value="">Sélectionnez le type</option>
                        <option value="Entrer">Entrée</option>
                        <option value="Sortir">Sortie</option>
                        <option value="Correction_pos">Correction positive</option>
                        <option value="Correction_neg">Correction négative</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Motif</label>
                    <select name="motif" class="form-control" required>
                        <option value="">Sélectionnez le motif</option>
                        <option value="cmde_client">Commande client</option>
                        <option value="vente">Vente</option>
                        <option value="autre">Autre motif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Produit</label>
                    <select name="produit_id" class="form-control" required>
                        <option value="">Sélectionnez un produit</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Boutique</label>
                    <select name="boutique_id" class="form-control" required>
                        <option value="">Sélectionnez une boutique</option>
                        @foreach($boutiques as $boutique)
                            <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
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
            $('#mvtstocks-table').DataTable({
                ajax: {
                    url: '{{ route("mvtstocks.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'qte' },
                    { data: 'type' },
                    { data: 'motif' },
                    {
                        data: 'produit.nom',
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
                        data: null,
                        render: function(data, type, row) {
                            let active_action = "";
                            return `
                                <button class="btn btn-warning btn-sm edit-mvt-stock" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-danger btn-sm delete-mvt-stock" data-id="${row.id}">
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

        $('[data-target="#addMvtStockModal"]').click(function (e) {
            $('#addMvtStockForm')[0].reset();
            $('#mvtstock_id').val('');
            $('#password-group').show();
            $('#addMvtStockModal .modal-title').text("Ajouter un mouvement de stock");
        });

        $('#addMvtStockForm').submit(function(e) {
            e.preventDefault();

            const id = $('#mvtstock_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/mvtstocks/${id}` : `/mvtstocks`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addMvtStockModal').modal('hide');
                    $('#addMvtStockForm')[0].reset();
                    $('#mvtstock_id').val('');
                    $('#mvtstocks-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un mouvement de stock!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-mvt-stock', function() {
            const mvtStockId = $(this).data('id');

            $.ajax({
                url: `/mvtstocks/${mvtStockId}`,
                type: 'GET',
                success: function(response) {
                    const mvtStock = response.data;

                    // Remplir le formulaire
                    $('#mvtstock_id').val(mvtStock.id);
                    $('input[name="qte"]').val(mvtStock.qte);
                    $('select[name="type"]').val(mvtStock.type);
                    $('select[name="motif"]').val(mvtStock.motif);
                    $('select[name="produit_id"]').val(mvtStock.produit_id);
                    $('select[name="boutique_id"]').val(mvtStock.boutique_id);
                    
                    $('#addMvtStockModal .modal-title').text("Modifier le mouvement de stock");
                    $('#addMvtStockModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un mouvement de stock!',
                        text: "Erreur lors du chargement de le mouvement de stock"
                    });
                }
            });
        });

        $(document).on('click', '.delete-mvt-stock', function () {
            const mvtStockId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer ce mouvement de stock?',
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
                        url: `/mvtstocks/${mvtStockId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#mvtstocks-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer le mouvement de stock.', 'error');
                        }
                    });
                }
            });;
        });

    </script>
@endsection
