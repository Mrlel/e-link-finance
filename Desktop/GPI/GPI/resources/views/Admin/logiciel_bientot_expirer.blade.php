@extends('layouts.main-board')
@section('title', 'Gestion des logiciels')

@section('content')

<header class="my-4">
  <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center">
    Gestion des Logiciels Bientôt Expirés 
  </h1>
  <p class="text-muted mb-0">
    Liste des logiciels • à suiveiller
  </p>
</header>
    @include('layouts.licence-stat-card')
 <!-------- Table Section -->
 <div class="table-responsive">

       <div class="uk-width-1-1">
        @if($licencesBientotExpirees->isEmpty())
        <div class="text-center py-3 mt-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucun logiciel enregistré</p>
        </div>
        @else
       <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Date d'expiration</th>
                        <th>Clé de licence</th>
                        <th>Environnement</th>
                        <th>Langage et version</th>
                        <th>SGBD et version</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($licencesBientotExpirees as $licencesBientotExpiree)
                    <tr>
                        <td>{{$licencesBientotExpiree->designation_licence}}</td>
                        <td>{{$licencesBientotExpiree->type_licence}}</td>
                        <td><i class="far fa-calendar-alt"></i> {{$licencesBientotExpiree->date_expiration}}</td>
                        <td>{{$licencesBientotExpiree->cle_licence}}</td>
                        <td>{{$licencesBientotExpiree->environnement}}</td>
                        <td>{{$licencesBientotExpiree->langage_version}}</td>
                        <td>{{$licencesBientotExpiree->sgbd_version}}</td>
              <td class="d-flex align-item-center gap-2">
                            <div class="item"><a class="text-danger" href="/delete_logiciel/{{$licencesBientotExpiree->id}}"><ion-icon name="trash-outline"></ion-icon></a></div>
                            <div class="item"><a href="/logiciel/{{$licencesBientotExpiree->id}}" onClick="return confirm('Voulez-vous vraiment supprimer ce logiciel ?')"><ion-icon name="information-circle-outline"></ion-icon></a></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

<!-- Modal pour assigner un logiciel -->
<div class="modal fade" id="assignerModal" tabindex="-1" aria-labelledby="assignerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignerModalLabel">Assigner un logiciel</h5>
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
                                @foreach($licencesBientotExpirees as $licencesBientotExpiree)
                                    <div class="item" data-value="{{ $licencesBientotExpiree->id }}">
                                        <i class="cube icon"></i>
                                        {{ $licencesBientotExpiree->designation_licence }}
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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