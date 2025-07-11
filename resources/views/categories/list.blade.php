@extends('layouts.app')

@section('title')
    Categories
@endsection

@section('content')
    <!-- begin #content -->
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Catégories</li>
        </ol>
        <!-- end breadcrumb -->

        <!-- begin page-header -->
        <h1 class="page-header">Liste des catégories</h1>
        <!-- end page-header -->

        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Catégories</h4>
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
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addCategorieModal">
                    <i class="fa fa-plus"></i> Ajouter une catégorie
                </button>

                <table id="categories-table" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
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
    <div class="modal fade" id="addCategorieModal" tabindex="-1" role="dialog" aria-labelledby="addCategorieModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form id="addCategorieForm">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gestion catégorie</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="categorie_id" id="categorie_id">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" required>
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
            $('#categories-table').DataTable({
                ajax: {
                    url: '{{ route("categories.index") }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'nom' },
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
                                active_action = `<button class="btn btn-info btn-sm active-categorie" data-id="${row.id}">
                                    <i class="fa fa-toggle-off"></i> Activer
                                </button>`;
                            else
                                active_action = `<button class="btn btn-info btn-sm inactive-categorie" data-id="${row.id}">
                                    <i class="fa fa-toggle-on"></i> Désactiver
                                </button>`;
                            return `
                                <button class="btn btn-warning btn-sm edit-categorie" data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Modifier
                                </button>
                                ${active_action}
                                <button class="btn btn-danger btn-sm delete-categorie" data-id="${row.id}">
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

        $('[data-target="#addCategorieModal"]').click(function (e) {
            $('#addCategorieForm')[0].reset();
            $('#categorie_id').val('');
            $('#addCategorieModal .modal-title').text("Ajouter une catégorie");
        });

        $('#addCategorieForm').submit(function(e) {
            e.preventDefault();

            const id = $('#categorie_id').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/categories/${id}` : `/categories`;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addCategorieModal').modal('hide');
                    $('#addCategorieForm')[0].reset();
                    $('#categorie_id').val('');
                    $('#categories-table').DataTable().ajax.reload();
                    swal('Succès!', response.status_message, 'success');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une catégorie!',
                        text: xhr.responseJSON?.message || xhr.responseText
                    });
                }
            });
        });

        $(document).on('click', '.edit-categorie', function() {
            const categorieId = $(this).data('id');

            $.ajax({
                url: `/categories/${categorieId}`,
                type: 'GET',
                success: function(response) {
                    const categorie = response.data;

                    // Remplir le formulaire
                    $('#categorie_id').val(categorie.id);
                    $('input[name="nom"]').val(categorie.nom);

                    $('#addCategorieModal .modal-title').text("Modifier une catégorie");
                    $('#addCategorieModal').modal('show');
                },
                error: function(xhr) {
                    $.gritter.add({
                        title: 'Gestion d\'une catégorie!',
                        text: "Erreur lors du chargement de la catégorie"
                    });
                }
            });
        });

        $(document).on('click', '.delete-categorie', function () {
            const categorieId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment supprimer cette catégorie?',
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
                        url: `/categories/${categorieId}`,
                        type: 'DELETE',
                        success: function (response) {
                            swal('Supprimé!', response.status_message, 'success');
                            $('#categories-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de supprimer la catégorie.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.active-categorie', function () {
            const categorieId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment activer cette catégorie?',
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
                        url: `/categories/${categorieId}/active`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Activé!', response.status_message, 'success');
                            $('#categories-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            console.log(xhr);
                            swal('Erreur', 'Impossible d\'activer la catégorie.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.inactive-categorie', function () {
            const categorieId = $(this).data('id');
            swal({
                title: 'Voulez-vous vraiment désactiver cette catégorie?',
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
                        url: `/categories/${categorieId}/inactive`,
                        type: 'PUT',
                        success: function (response) {
                            swal('Désactivé!', response.status_message, 'success');
                            $('#categories-table').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            swal('Erreur', 'Impossible de désactiver la catégorie.', 'error');
                        }
                    });
                }
            });
        });

    </script>
@endsection
