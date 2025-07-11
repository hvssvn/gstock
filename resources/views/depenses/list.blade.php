@extends('layouts.app')

@section('title')
    Dépenses
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Dépenses</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des dépenses</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Dépenses</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addDepenseModal">
                    <i class="fa fa-plus"></i> Ajouter une dépense
                </button>

                <table id="depenses-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Motif</th>
                            <th>Montant</th>
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
    <div class="modal fade" id="addDepenseModal" tabindex="-1" role="dialog" aria-labelledby="addDepenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addDepenseForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Gestion dépense</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="depense_id" id="depense_id">
                        <div class="form-group">
                            <label>Motif</label>
                            <input type="text" name="motif" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Montant</label>
                            <input type="number" name="montant" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Mois</label>
                            <select name="mois" class="form-control" required>
                                <option value="">Sélectionner un mois</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Année</label>
                            <input type="number" name="annee" class="form-control" min="1950" max="2030"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Boutique</label>
                            <select name="boutique_id" class="form-control">
                                <option value="">Aucune</option>
                                @foreach ($boutiques as $boutique)
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

        $(document).ready(function() {
            $('#depenses-table').DataTable({
                ajax: {
                    url: '{{ route('depenses.index') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'motif'
                    },
                    {
                        data: 'montant',
                        render: function(data, type, row) {
                            return new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'XOF'
                            }).format(data);
                        }
                    },
                    {
                        data: 'mois',
                        render: function(data, type, row) {
                            const mois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre',
                                'Décembre'
                            ];
                            return mois[data] || data;
                        }
                    },
                    {
                        data: 'annee'
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
                            if (data === "Activer")
                                return `<i class="fa fa-check-circle" style="color: green;"></i>`;
                            else if (data === "Désactiver")
                                return `<i class="fa fa-times-circle" style="color: red;"></i>`;
                            return '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let active_action = "";
                            if (data.etat === "Désactiver")
                                active_action = `<button class="btn btn-info btn-sm active-depense" tooltip="toggle" title="Activer" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i>
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-depense" tooltip="toggle" title="Désactiver" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i>
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-depense" tooltip="toggle" title="Modifier" data-id="${row.id}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-depense" tooltip="toggle" title="Supprimer" data-id="${row.id}">
                                    <i class="fa fa-trash"></i>
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

        $('[data-target="#addDepenseModal"]').click(function(e) {
            $('#addDepenseForm')[0].reset();
            $('#depense_id').val('');
            $('#addDepenseModal .modal-title').text("Ajouter une dépense");
            loadBoutiques();
        });

        $('#addDepenseForm').submit(function(e) {
            e.preventDefault();

            const id = $('#depense_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/depenses/${id}` : `/depenses`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addDepenseModal').modal('hide');
                    $('#addDepenseForm')[0].reset();
                    $('#depense_id').val('');
                    $('#depenses-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une Dépense!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-depense', function() {
            const depenseId = $(this).data('id');

            $.ajax({
                url: `/depenses/${depenseId}`,
                type: 'GET',
                success: function(response) {
                    const depense = response.data;

                    // Remplir le formulaire
                    $('#depense_id').val(depense.id);
                    $('input[name="motif"]').val(depense.motif);
                    $('input[name="montant"]').val(depense.montant);
                    $('select[name="mois"]').val(depense.mois);
                    $('input[name="annee"]').val(depense.annee);
                    $('select[name="boutique_id"]').val(depense.boutique_id);

                    $('#addDepenseModal .modal-title').text("Modifier une dépense");
                    $('#addDepenseModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une Dépense!',
                        text: "Erreur lors du chargement de la dépense"
                    });
                }
            });
        });

        $(document).on('click', '.delete-depense', function() {
            const depenseId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer cette dépense?',
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
            }).then(function(value) {
                if (value) {
                    $.ajax({
                        url: `/depenses/${depenseId}`,
                        type: 'DELETE',
                        success: function(response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#depenses-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            swal('Erreur', 'Impossible de supprimer la dépense.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.active-depense', function() {
            const depenseId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer cette dépense?',
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
            }).then(function(value) {
                if (value) {
                    $.ajax({
                        url: `/depenses/${depenseId}/active`,
                        type: 'PUT',
                        success: function(response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#depenses-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            swal('Erreur', 'Impossible d\'activer la dépense.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-depense', function() {
            const depenseId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver cette dépense?',
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
            }).then(function(value) {
                if (value) {
                    $.ajax({
                        url: `/depenses/${depenseId}/inactive`,
                        type: 'PUT',
                        success: function(response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#depenses-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            swal('Erreur', 'Impossible de désactiver la dépense.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endsection
