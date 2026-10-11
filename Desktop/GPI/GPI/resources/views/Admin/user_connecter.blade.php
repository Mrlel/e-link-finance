@extends('layouts.main-board')

@section('title', 'Gestion des utilisateurs')

@section('content')
<body>
@include('layouts.user-stat-card')
    <!-- Table Section -->
    <div class="uk-margin">
        <div id="exampleModal" uk-modal>
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h3 class="uk-modal-title">Ajouter un nouvel utilisateur</h3>
                </div>
                <div class="uk-modal-body">
                <form class="ui form" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-2@s">
                            <label for="nom" class="uk-form-label">Nom</label>
                            <input type="text" class="uk-input" id="nom" name="nom" placeholder="Entrer le nom" required>
                        </div>

                        <div class="uk-width-1-2@s">
                            <label for="prenom" class="uk-form-label">Prénom</label>
                            <input type="text" class="uk-input" id="prenom" name="prenom" placeholder="Entrer le prénom" required>
                        </div>

                        <div class="uk-width-1-2@s">
                            <label for="matricule" class="uk-form-label">Matricule</label>
                            <input type="text" class="uk-input" id="matricule" name="matricule" placeholder="Entrer le matricule" required>
                        </div>

                        <div class="uk-width-1-2@s">
                            <label for="email" class="uk-form-label">Email</label>
                            <input type="email" class="uk-input" id="email" name="email" placeholder="Entrer l'email" required>
                        </div>
                    </div>

                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-2@s">
                            <label for="contact" class="uk-form-label">Contact</label>
                            <input type="text" class="uk-input" id="contact" name="contact" placeholder="Entrer le contact" required>
                        </div>

                        <div class="uk-width-1-2@s">
                            <label for="emploie" class="uk-form-label">Emploi</label>
                            <input type="text" class="uk-input" id="emploie" name="emploie" placeholder="Entrer l'emploi" required>
                        </div>
                    </div>

                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-2@s">
                            <label for="fonction" class="uk-form-label">Fonction</label>
                            <input type="text" class="uk-input" id="fonction" name="fonction" placeholder="Entrer la fonction de l'utilisateur" required>
                        </div>

                        <div class="uk-width-1-2@s">
                            <label for="grade" class="uk-form-label">Grade</label>
                            <input type="text" class="uk-input" id="grade" name="grade" placeholder="Entrer le grade" required>
                        </div>
                    </div>

                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-2@s">
                            <label for="role" class="uk-form-label">Rôle</label>
                            <select class="uk-select" id="role" name="role" required>
                                <option value="user">Utilisateur</option>
                                <option value="chef_de_service">Chef de service</option>
                                <option value="sous_directeur">Sous directeur</option>
                                <option value="gestionnaire_parc">Gestionnaire de parc</option>
                                <option value="technicien">Chef de service maintenance</option>
                                <option value="directeur">Directeur</option>
                                <option value="admin">Administrateur</option>
                                <option value="point_focal">Point focal</option>
                                <option value="service_gestionnaire">Service gestionnaire</option>
                                <option value="ministre">Ministre</option>
                            </select>
                        </div>
                    </div>

                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Annuler</button>
                        <button class="ui primary button" type="submit">Enregistrer l'utilisateur</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div class="invoices-table">
                <div class="invoices-header">
                    <div class="invoices-title">Gestion des utilisateurs</div>
                    <!-- Barre de recherche -->
                    <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Rechercher un utilisateur...">
                        <i class="search icon"></i>
                    </div>
                    <div class="invoices-actions">
                    <button class="ui button text-white" style="background-color:#FF9900;" type="button" uk-toggle="target: #exampleModal">
                             Créer un utilisateur
                        </button>
                    </div>
                </div>
                @if($login_users->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Matricule</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Emploi</th>
                            <th>Grade</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($login_users as $login_user)
                    <tr>
                        @if($login_user->password_changed_at)
                        <td class="text-success"><ion-icon name="checkbox-outline"></ion-icon></td>
                        @else
                        <td class="text-danger"><ion-icon name="close-outline"></ion-icon></td>
                        @endif
                        <td>{{$login_user->nom}}</td>
                        <td>{{$login_user->prenom ?? 'non spécifié'}}</td>
                        <td>{{$login_user->matricule ?? 'non spécifié'}}</td>
                        <td><i class="mail icon"></i> {{$login_user->email}}</td>
                        <td>{{$login_user->contact}}</td>
                        <td>{{$login_user->emploie}}</td>
                        <td>{{$login_user->grade}}</td>
                        <td>
                            {{$login_user->role}}
                        </td>
                        <td>
                            <div class="ui right dropdown item">
                            <span style="color: #3498db; cursor: pointer;"><ion-icon name="chevron-down"></ion-icon></span>
                                <div class="menu">
                                <div class="item"><a href="/update_user/{{$user->id}}"><i class="pencil icon"></i> Modifier</a></div>
                                    <div class="item"><a href="/user_Delete/{{$user->id}}" style="color: #e74c3c;"><i class="trash icon"></i> Supprimer</a></div>
                                    <div class="item">
                                    <form action="{{ route('directions.reset-password-admin', $user->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment réinitialiser le mot de passe de ce utilisateur ?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-key me-1"></i>Réinitialiser le mot de passe</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="text-center py-3 mt-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun utilisateur connecté</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const toggleSidebar = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    toggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
    });

    setTimeout(() => {
        const messageDiv = document.getElementById('statusMessage');
        if (messageDiv) {
            messageDiv.style.transition = "opacity 0.5s ease";
            messageDiv.style.opacity = "0";
            setTimeout(() => messageDiv.remove(), 500);
        }
    }, 5000);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.16.3/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.16.3/js/uikit-icons.min.js"></script>

<script>
    $(document).ready(function() {
        $('.ui.dropdown').dropdown();
    });
    
    $('#searchInput').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
</body>
</html>
@endsection