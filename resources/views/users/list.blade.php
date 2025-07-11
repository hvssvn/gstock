@extends('layouts.app')

@section('title')
    Utilisateurs
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Utilisateurs</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des utilisateurs</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Utilisateurs</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addUserModal">
                    <i class="fa fa-user-plus"></i> Ajouter un utilisateur
                </button>

                <table id="users-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Prénoms</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Boutique</th>
                            <th>Rôle</th>
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
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addUserForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion utilisateur</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="user_id" id="user_id">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group" id="password-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control">
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea class="form-control" name="adresse" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo" class="form-control-file" accept="image/*">
                    <br><img id="user-photo-preview" src="" alt="Photo actuelle" style="max-width: 150px; display: none; border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label>CNI</label>
                    <input type="text" name="cni" class="form-control">
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
                <div class="form-group">
                    <label>Rôle</label>
                    <select name="role_id" class="form-control" required>
                        <option value="">Sélectionnez un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nom }}</option>
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
            $('#users-table').DataTable({
                ajax: {
                    url: '{{ route("users.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'prenom' },
                    { data: 'nom' },
                    { data: 'email' },
                    {
                        data: 'boutique.nom',
                        render: function(data) {
                            return data ?? '—';
                        }
                    },
                    {
                        data: 'role.nom',
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
                                active_action = `<button class="btn btn-info btn-sm active-user" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i> Activer
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-user" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i> Désactiver
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-user" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-user" data-id="${row.id}">
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

        $('[data-target="#addUserModal"]').click(function (e) {
            $('#addUserForm')[0].reset();
            $('#user_id').val('');
            $('#password-group').show();
            $('input[name="password"]').prop('required', true);
            $('#user-photo-preview').hide();
            $('#addUserModal .modal-title').text("Ajouter un utilisateur");
        });

        $('#addUserForm').submit(function(e) {
            e.preventDefault();

            const id = $('#user_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/users/${id}` : `/users`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addUserModal').modal('hide');
                    $('#addUserForm')[0].reset();
                    $('#user_id').val('');
                    $('#users-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un utilisateur!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-user', function() {
            const userId = $(this).data('id');

            $.ajax({
                url: `/users/${userId}`,
                type: 'GET',
                success: function(response) {
                    const user = response.data;

                    // Remplir le formulaire
                    $('#user_id').val(user.id);
                    $('input[name="nom"]').val(user.nom);
                    $('input[name="prenom"]').val(user.prenom);
                    $('input[name="email"]').val(user.email);
                    $('input[name="telephone"]').val(user.telephone);
                    $('input[name="date_naissance"]').val(user.date_naissance);
                    $('textarea[name="adresse"]').val(user.adresse);
                    $('input[name="cni"]').val(user.cni);
                    $('select[name="role_id"]').val(user.role_id);
                    $('select[name="boutique_id"]').val(user.boutique_id);
                    
                    $('#password-group').hide();
                    $('input[name="password"]').prop('required', false);

                    if (user.photo) $('#user-photo-preview').attr('src', `/storage/${user.photo}`).show();
                    else $('#user-photo-preview').hide();

                    $('#addUserModal .modal-title').text("Modifier l'utilisateur");
                    $('#addUserModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'un utilisateur!',
                        text: "Erreur lors du chargement de l'utilisateur"
                    });
                }
            });
        });

        $(document).on('click', '.delete-user', function () {
            const userId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer cet utilisateur?',
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
                        url: `/users/${userId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer l’utilisateur.', 'error');
                        }
                    });
                }
            });;
        });

        $(document).on('click', '.active-user', function () {
            const userId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer cet utilisateur?',
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
                        url: `/users/${userId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible d\'activer l\'utilisateur.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-user', function () {
            const userId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver cet utilisateur?',
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
                        url: `/users/${userId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#users-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver l\'utilisateur.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
