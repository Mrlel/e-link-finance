@extends('layouts.main-board')
@section('title', 'Gestion des assignation')
@section('content')

    <div class="row mt-4">
        <div class="col">
            <div class="mb-4 p-2  rounded d-flex justify-content-between">
                <h3>Historique des Assignations Logiciels</h3>
                <div class="btn-add d-flex justify-content-between gap-4">
                    <div class="col mr-2 d-flex justify-content-end">
                        <a href="#" id="myBtn" class="btn bg-light" data-bs-toggle="modal" data-bs-target="#attributionModal">Assigner un Logiciel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

     <!-- Table -->
     <div class="container uk-border-rounded">
        <table class="uk-table uk-table-divider">
            <thead>
            <tr>
                    <th>ID</th>
                    <th>Equipement</th>
                    <th>Remetteur</th>
                    <th>Logiciels</th>
                    <th>Date d'attribution</th>
                    <th>Heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attributions as $attribution)
                    <tr>
                        <td>{{ $attribution->id }}</td>
                        <td>{{ $attribution->equipement ? $attribution->equipement->des_equipement : 'Utilisateur supprimé' }}</td>
                        <td>{{ $attribution->licenceAssignedBy ? $attribution->logicielAssignedBy->nom : 'Non spécifié' }}</td>
                        <td>{{ $attribution->licence ? $attribution->licence->designation_licence : 'Licence supprimée' }}</td>
                        <td>{{ $attribution->assigned_at ? \Carbon\Carbon::parse($attribution->assigned_at)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $attribution->assigned_at ? \Carbon\Carbon::parse($attribution->assigned_at)->format('H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('retirer', $attribution->id) }}" 
                               style="color: red;font-size:14px;border: 1px solid red;padding: 2px 5px;"
                               onclick="return confirm('Êtes-vous sûr de vouloir retirer cette licence ?')">
                                Retirer
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Attribution Modal -->
    <div class="modal fade" id="attributionModal" tabindex="-1" aria-labelledby="attributionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attributionModalLabel">Attribuer un Logiciel à un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ui form" action="{{ route('assigner_logiciels.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label>Equipements</label>
                        <div class="ui fluid search selection dropdown">
                            <input type="hidden" name="equipement_id">
                            <i class="dropdown icon"></i>
                            <div class="default text">Sélectionner un Equipement</div>
                            <div class="menu">
                                @foreach($equipements as $equipement)
                                    <div class="item" data-value="{{ $equipement->id }}">
                                        <i class="computer icon"></i>
                                        {{ $equipement->des_equipement }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Logiciel</label>
                        <div class="ui fluid search selection dropdown">
                            <input type="hidden" name="licence_id">
                            <i class="dropdown icon"></i>
                            <div class="default text">Sélectionner un logiciel</div>
                            <div class="menu">
                                @foreach($licences as $licence)
                                    <div class="item" data-value="{{ $licence->id }}">
                                        <i class="cube icon"></i>
                                        {{ $licence->designation_licence }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button class="ui primary button fluid" type="submit">
                        <i class="user plus icon"></i>
                        Assigner
                    </button>
                </form> 
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll(".moreBtn").forEach(function(btn) {
    btn.addEventListener("click", function(event) {
        var content = this.nextElementSibling;
        document.querySelectorAll(".moreBtn-content").forEach(function(c) {
            if (c !== content) {
                c.classList.remove('show');
                setTimeout(function() { c.style.display = "none"; }, 300);
            }
        });
        if (content.classList.contains('show')) {
            content.classList.remove('show');
            setTimeout(function() { content.style.display = "none"; }, 300);
        } else {
            content.style.display = "block";
            setTimeout(function() { content.classList.add('show'); }, 5); // slight delay to ensure display:block is applied
        }
        event.stopPropagation();
    });
});

    const toggleSidebar = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    toggleSidebar.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');  // Ajoute ou supprime la classe 'collapsed'
      content.classList.toggle('collapsed');  // Ajoute ou supprime le décalage du contenu
    });
    </script>
    @endsection