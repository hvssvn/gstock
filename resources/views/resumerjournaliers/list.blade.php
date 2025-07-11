@extends('layouts.app')

@section('title')
    Résumé Journalier
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active"> Résumé Journalier</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des résumés journaliers</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title"> Résumé Journalier</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addResumerJournalierModal">
                    <i class="fa fa-plus"></i> Ajouter un Résumé Journalier
                </button>

                <table id="resumejournaliers-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Total Vente</th>
                            <th>Total Dépense</th>
                            <th>Mois</th>
                            <th>Année</th>
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
    <div class="modal fade" id="addResumerJournalierModal" tabindex="-1" role="dialog" aria-labelledby="addResumerJournalierModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addResumerJournalierForm">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion Résumé Journalier </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="resumerjournalier_id" id="resumerjournalier_id">
                <div class="form-group">
                    <label>Total Vente</label>
                    <input type="text" name="totalVente" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Total Dépense</label>
                    <input type="text" name="totalDepense" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mois</label>
                    <select name="mois" class="form-control" required>
                        <option value="">Sélectionner un mois</option>
                        <option>Janvier</option>
                        <option>Février</option>
                        <option>Mars</option>
                        <option>Avril</option>
                        <option>Mai</option>
                        <option>Juin</option>
                        <option>Juillet</option>
                        <option>Août</option>
                        <option>Septembre</option>
                        <option>Octobre</option>
                        <option>Novembre</option>
                        <option>Décembre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Année</label>
                    <input type="annee" name="annee" class="form-control" min="1950" max="2025" required>
                </div>
                <div class="form-group">
                    <label>Boutique</label>
                    <select name="boutique_id" class="form-control">
                        <option value="">Aucune</option>
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
            $('#resumejournaliers-table').DataTable({
                ajax: {
                    url: '{{ route("resumerjournaliers.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'totalVente' },
                    { data: 'totalDepense' },
                    { data: 'mois' },
                    { data: 'annee'},
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
                                active_action = `<button class="btn btn-info btn-sm active-resumerjournalier" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i> Activer
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-resumerjournalier" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i> Désactiver
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-resumerjournalier" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-resumerjournalier" data-id="${row.id}">
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

        $('[data-target="#addResumerJournalierModal"]').click(function (e) {
            $('#addResumerJournalierForm')[0].reset();
            $('#resumerjournalier_id').val('');
            $('#addResumerJournalierModal .modal-title').text("Ajouter un résumé journalier");
        });

        $('#addResumerJournalierForm').submit(function(e) {
            e.preventDefault();

            const id = $('#resumerjournalier_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/resumerjournaliers/${id}` : `/resumerjournaliers`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addResumerJournalierModal').modal('hide');
                    $('#addResumerJournalierForm')[0].reset();
                    $('#resumerjournalier_id').val('');
                    $('#resumejournaliers-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un résumé journalier!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-resumerjournalier', function() {
            const resumerJournalierId = $(this).data('id');

            $.ajax({
                url: `/resumerjournaliers/${resumerJournalierId}`,
                type: 'GET',
                success: function(response) {
                    const resumerjournalier = response.data;

                    // Remplir le formulaire
                    $('#resumerjournalier_id').val(resumerjournalier.id);
                    $('input[name="totalVente"]').val(resumerjournalier.totalVente);
                    $('input[name="totalDepense"]').val(resumerjournalier.totalDepense);
                    $('input[name="mois"]').val(resumerjournalier.mois);
                    $('input[name="annee"]').val(resumerjournalier.annee);
                    $('select[name="boutique_id"]').val(resumerjournalier.boutique_id);

                    $('#addResumerJournalierModal .modal-title').text("Modifier le résumé journalier");
                    $('#addResumerJournalierModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un résumé journalier!',
                        text: "Erreur lors du chargement du résumé journalier"
                    });
                }
            });
        });

        $(document).on('click', '.delete-resumerjournalier', function () {
            const resumerJournalierId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer ce résumé journalier?',
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
                        url: `/resumerjournaliers/${resumerJournalierId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#resumejournaliers-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer le résumé journalier.', 'error');
                        }
                    });
                }
            });;
        });

        $(document).on('click', '.active-resumerjournalier', function () {
            const resumerJournalierId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer ce résumé journalier?',
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
                        url: `/resumerjournaliers/${resumerJournalierId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#resumejournaliers-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible d\'activer le résumé journalier.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-resumerjournalier', function () {
            const resumerJournalierId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver ce journalier résumé?',
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
                        url: `/resumerjournaliers/${resumerJournalierId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#resumejournaliers-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver le résumé journalier.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection