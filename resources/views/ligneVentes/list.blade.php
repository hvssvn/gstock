@extends('layouts.app')

@section('title')
    Lignes de vente
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Lignes de vente</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des lignes de vente</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Lignes de vente</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addLigneVenteModal">
                    <i class="fa fa-plus"></i> Ajouter une ligne de vente
                </button>

                <table id="ligneVentes-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Quantité</th>
                            <th>Vente</th>
                            <th>Produit</th>
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
    <div class="modal fade" id="addLigneVenteModal" tabindex="-1" role="dialog" aria-labelledby="addLigneVenteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addLigneVenteForm">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion ligne de vente</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="ligneVente_id" id="ligneVente_id">
                <div class="form-group">
                    <label>Quantité</label>
                    <input type="text" name="qte" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Vente</label>
                    <select name="vente_id" class="form-control" required>
                        <option value="">Sélectionnez une vente</option>
                        @foreach($ventes as $vente)
                            <option value="{{ $vente->id }}">{{ $vente->numero }}</option>
                        @endforeach
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
            $('#ligneVentes-table').DataTable({
                ajax: {
                    url: '{{ route("ligneventes.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'qte' },
                    {
                        data: 'vente.numero',
                        render: function(data) {
                            return data ?? '—';
                        }
                    },
                    {
                        data: 'produit.nom',
                        render: function(data) {
                            return data ?? '—';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let active_action = "";
                            return `
                                <button class="btn btn-warning btn-sm edit-ligne-vente" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-danger btn-sm delete-ligne-vente" data-id="${row.id}">
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

        $('[data-target="#addLigneVenteModal"]').click(function (e) {
            $('#addLigneVenteForm')[0].reset();
            $('#ligneVente_id').val('');
            $('#password-group').show();
            $('#addLigneVenteModal .modal-title').text("Ajouter une ligne de vente");
        });

        $('#addLigneVenteForm').submit(function(e) {
            e.preventDefault();

            const id = $('#ligneVente_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/ligneventes/${id}` : `/ligneventes`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addLigneVenteModal').modal('hide');
                    $('#addLigneVenteForm')[0].reset();
                    $('#ligneVente_id').val('');
                    $('#ligneVentes-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une ligne de vente!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-ligne-vente', function() {
            const ligneVenteId = $(this).data('id');

            $.ajax({
                url: `/ligneventes/${ligneVenteId}`,
                type: 'GET',
                success: function(response) {
                    const ligneVente = response.data;

                    // Remplir le formulaire
                    $('#ligneVente_id').val(ligneVente.id);
                    $('input[name="qte"]').val(ligneVente.qte);
                    $('select[name="vente_id"]').val(ligneVente.vente_id);
                    $('select[name="produit_id"]').val(ligneVente.produit_id);
                    
                    $('#addLigneVenteModal .modal-title').text("Modifier la ligne de vente");
                    $('#addLigneVenteModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une ligne de vente!',
                        text: "Erreur lors du chargement de la ligne de vente"
                    });
                }
            });
        });

        $(document).on('click', '.delete-ligne-vente', function () {
            const ligneVenteId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer cette ligne de vente?',
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
                        url: `/ligneventes/${ligneVenteId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#ligneVentes-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer la ligne de vente.', 'error');
                        }
                    });
                }
            });;
        });

    </script>
@endsection
