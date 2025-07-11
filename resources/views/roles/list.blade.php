@extends('layouts.app')

@section('title')
    Rôles
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Rôles</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des rôles</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Rôles</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addRoleModal">
                    <i class="fa fa-plus"></i> Ajouter un rôle
                </button>

                <table id="roles-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Description</th>
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
    <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addRoleForm">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion rôle</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="role_id" id="role_id">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="2"></textarea>
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
            $('#roles-table').DataTable({
                ajax: {
                    url: '{{ route("roles.index") }}',
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
                                active_action = `<button class="btn btn-info btn-sm active-role" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i> Activer
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-role" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i> Désactiver
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-role" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-role" data-id="${row.id}">
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

        $('[data-target="#addRoleModal"]').click(function (e) {
            $('#addRoleForm')[0].reset();
            $('#role_id').val('');
            $('#addRoleModal .modal-title').text("Ajouter un rôle");
        });

        $('#addRoleForm').submit(function(e) {
            e.preventDefault();

            const id = $('#role_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/roles/${id}` : `/roles`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addRoleModal').modal('hide');
                    $('#addRoleForm')[0].reset();
                    $('#role_id').val('');
                    $('#roles-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un rôle!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-role', function() {
            const roleId = $(this).data('id');

            $.ajax({
                url: `/roles/${roleId}`,
                type: 'GET',
                success: function(response) {
                    const role = response.data;

                    // Remplir le formulaire
                    $('#role_id').val(role.id);
                    $('input[name="nom"]').val(role.nom);
                    $('textarea[name="description"]').val(role.description);

                    $('#addRoleModal .modal-title').text("Modifier un rôle");
                    $('#addRoleModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un rôle!',
                        text: "Erreur lors du chargement du rôle"
                    });
                }
            });
        });

        $(document).on('click', '.delete-role', function () {
            const roleId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer ce rôle?',
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
                        url: `/roles/${roleId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#roles-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer le rôle.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.active-role', function () {
            const roleId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer ce rôle?',
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
                        url: `/roles/${roleId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#roles-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible d\'activer le rôle.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-role', function () {
            const roleId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver ce rôle?',
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
                        url: `/roles/${roleId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#roles-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver le rôle.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
