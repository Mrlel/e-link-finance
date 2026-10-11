@extends('layouts.main-board')

@section('title', 'Gestion des utilisateurs')

@section('content')
@include('layouts.user-stat-card')

            @if($usersPage->total() > 0)
            <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Emploi</th>
                        <th>Fonction</th>
                        <th>Grade</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($usersPage as $user)
                <tr>
                    
                    <td>
                    {{$user->nom}}
                    </td>
                    <td>{{$user->prenom ?? 'non spécifié'}}</td>
                    <td>{{$user->matricule ?? 'non spécifié'}}</td>
                    <td><i class="mail icon"></i> {{$user->email}}</td>
                    <td>{{$user->contact}}</td>
                    <td>{{$user->emploie}}</td>
                    <td>{{ $user->fonction_id ? $user->fonction->fonction : '' }}</td>
                    <td>{{$user->grade}}</td>
                    <td>
                        @if($user->role == 'user')
                            <span class="">Agent</span>
                        @else
                            <span class="">{{$user->role}}</span>
                         @endif
                    </td>
                    <td class="text-center">
    <div class="dropdown">
        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $user->id }}">
            
            <li>
                <a class="dropdown-item" href="/update_user/{{$user->id}}">
                    <i class="bi bi-pencil text-primary me-2 fs-4"></i> Modifier
                </a>
            </li>

            <li>
                <a class="dropdown-item" href="/users/{{$user->id}}/all-equipements">
                   <i class="bi bi-at me-2 fs-4"></i> Équipements assignés
                </a>
            </li>

            <li>
                <form action="{{ route('directions.reset-password-admin', $user->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment réinitialiser le mot de passe de ce utilisateur ?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="dropdown-item">
                        <i class="bi bi-key me-2 fs-4"></i> Réinitialiser le mot de passe
                    </button>
                </form>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
                <a href="/user_Delete/{{$user->id}}" onclick="return confirm('Voulez-vous vraiment supprimer ce utilisateur ?')" class="dropdown-item text-danger " style="color: #e74c3c;"><i class="bi bi-trash"></i> Supprimer</a>
            </li>

        </ul>
    </div>
</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="small text-muted mb-0">
                {{ $usersPage->firstItem() }}–{{ $usersPage->lastItem() }} sur {{ $usersPage->total() }} utilisateur(s)
            </p>
            {{ $usersPage->links('pagination::bootstrap-5') }}
        </div>
        @else
            <div class="text-center py-3 mt-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun utilisateur</p>
            </div>
        @endif

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
</script>
</body>
</html>
@endsection