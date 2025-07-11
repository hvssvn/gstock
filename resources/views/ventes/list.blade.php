@extends('layouts.app')

@section('title')
    Ventes
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Ventes</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des ventes</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Ventes</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addVenteModal">
                    <i class="fa fa-plus"></i> Ajouter une vente
                </button>

                <table id="ventes-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Numéro</th>
                            <th>Quantité</th>
                            <th>Date</th>
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
    <div class="modal fade" id="addVenteModal" tabindex="-1" role="dialog" aria-labelledby="addVenteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addVenteForm">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion vente</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="vente_id" id="vente_id">
                <div class="form-group">
                    <label>Numéro</label>
                    <input type="text" name="numero" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Quantité</label>
                    <input type="text" name="qte" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
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
            $('#ventes-table').DataTable({
                ajax: {
                    url: '{{ route("ventes.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'numero' },
                    { data: 'qte' },
                    {
                        data: 'date',
                        render: function(data, type, row) {
                            const date = new Date(data);
                            return date.toLocaleDateString('fr-FR');
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
                                active_action = `<button class="btn btn-info btn-sm active-vente" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i> Activer
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-vente" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i> Désactiver
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-vente" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-vente" data-id="${row.id}">
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

        $('[data-target="#addVenteModal"]').click(function (e) {
            $('#addVenteForm')[0].reset();
            $('#vente_id').val('');
            $('#addVenteModal .modal-title').text("Ajouter une vente");
        });

        $('#addVenteForm').submit(function(e) {
            e.preventDefault();

            const id = $('#vente_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/ventes/${id}` : `/ventes`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addVenteModal').modal('hide');
                    $('#addVenteForm')[0].reset();
                    $('#vente_id').val('');
                    $('#ventes-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une vente!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-vente', function() {
            const venteId = $(this).data('id');

            $.ajax({
                url: `/ventes/${venteId}`,
                type: 'GET',
                success: function(response) {
                    const vente = response.data;

                    // Remplir le formulaire
                    $('#vente_id').val(vente.id);
                    $('input[name="numero"]').val(vente.numero);
                    $('input[name="qte"]').val(vente.qte);
                    $('input[name="date"]').val(vente.date);
                    $('select[name="boutique_id"]').val(vente.boutique_id);
                    
                    $('#addVenteModal .modal-title').text("Modifier la vente");
                    $('#addVenteModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une vente!',
                        text: "Erreur lors du chargement de la vente"
                    });
                }
            });
        });

        $(document).on('click', '.delete-vente', function () {
            const venteId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer cette vente?',
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
                        url: `/ventes/${venteId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#ventes-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer la vente.', 'error');
                        }
                    });
                }
            });;
        });

        $(document).on('click', '.active-vente', function () {
            const venteId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer cette vente?',
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
                        url: `/ventes/${venteId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#ventes-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible d\'activer la vente.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-vente', function () {
            const venteId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver cette vente?',
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
                        url: `/ventes/${venteId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#ventes-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver la vente.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
