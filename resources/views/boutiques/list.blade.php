@extends('layouts.app')

@section('title')
    Boutiques
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Boutiques</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des boutiques</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Boutiques</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addBoutiqueModal">
                    <i class="fa fa-plus"></i> Ajouter une boutique
                </button>

                <table id="boutiques-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Adresse</th>
                            <th>Site Web</th>
                            <th>N° de téléphone</th>
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
    <div class="modal fade" id="addBoutiqueModal" tabindex="-1" role="dialog" aria-labelledby="addBoutiqueModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addBoutiqueForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion boutique</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="boutique_id" id="boutique_id">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea class="form-control" name="adresse" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Site Web</label>
                    <input type="text" name="site_web" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo" class="form-control-file" accept="image/*">
                    <br><img id="boutique-photo-preview" src="" alt="Photo actuelle" style="max-width: 150px; display: none; border-radius: 8px;">
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
            $('#boutiques-table').DataTable({
                ajax: {
                    url: '{{ route("boutiques.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'nom' },
                    {
                        data: 'description',
                        render: function(data, type, row) {
                            if (!data) return '';
                            const short = data.length > 20 ? data.substring(0, 20) + '...' : data;
                            return `<span title="${data}">${short}</span>`;
                        }
                    },
                    {
                        data: 'adresse',
                        render: function(data, type, row) {
                            if (!data) return '';
                            const short = data.length > 20 ? data.substring(0, 20) + '...' : data;
                            return `<span title="${data}">${short}</span>`;
                        }
                    },
                    { data: 'site_web' },
                    { data: 'telephone' },
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
                                active_action = `<button class="btn btn-info btn-sm active-boutique" tooltip="toggle" title="Activer" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i>
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-boutique" tooltip="toggle" title="Désactiver" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i>
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-boutique" tooltip="toggle" title="Modifier" data-id="${row.id}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-boutique" tooltip="toggle" title="Supprimer" data-id="${row.id}">
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

        $('[data-target="#addBoutiqueModal"]').click(function (e) {
            $('#addBoutiqueForm')[0].reset();
            $('#boutique_id').val('');
            $('#boutique-photo-preview').hide();
            $('#addBoutiqueModal .modal-title').text("Ajouter une boutique");
        });

        $('#addBoutiqueForm').submit(function(e) {
            e.preventDefault();

            const id = $('#boutique_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/boutiques/${id}` : `/boutiques`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addBoutiqueModal').modal('hide');
                    $('#addBoutiqueForm')[0].reset();
                    $('#boutique_id').val('');
                    $('#boutiques-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une boutique!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-boutique', function() {
            const boutiqueId = $(this).data('id');

            $.ajax({
                url: `/boutiques/${boutiqueId}`,
                type: 'GET',
                success: function(response) {
                    const boutique = response.data;

                    // Remplir le formulaire
                    $('#boutique_id').val(boutique.id);
                    $('input[name="nom"]').val(boutique.nom);
                    $('textarea[name="description"]').val(boutique.description);
                    $('textarea[name="adresse"]').val(boutique.adresse);
                    $('input[name="site_web"]').val(boutique.site_web);
                    $('input[name="telephone"]').val(boutique.telephone);

                    if (boutique.photo) $('#boutique-photo-preview').attr('src', `/storage/${boutique.photo}`).show();
                    else $('#boutique-photo-preview').hide();

                    $('#addBoutiqueModal .modal-title').text("Modifier une boutique");
                    $('#addBoutiqueModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une Boutique!',
                        text: "Erreur lors du chargement de la boutique"
                    });
                }
            });
        });

        $(document).on('click', '.delete-boutique', function () {
            const boutiqueId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer cette boutique?',
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
                        url: `/boutiques/${boutiqueId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#boutiques-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer la boutique.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.active-boutique', function () {
            const boutiqueId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer cette boutique?',
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
                        url: `/boutiques/${boutiqueId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#boutiques-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible d\'activer la boutique.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-boutique', function () {
            const boutiqueId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver cette boutique?',
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
                        url: `/boutiques/${boutiqueId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#boutiques-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver la boutique.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
