@extends('layouts.main-board')
@section('title', 'Gestion des logiciels')

@section('content')

<header class="my-4">
  <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center">
    Gestion des Logiciels 
  </h1>
  <p class="text-muted mb-0">
    Liste des logiciels • disponibles
  </p>
</header>
    @include('layouts.licence-stat-card')
 <!-------- Table Section -->
 <div class="table-responsive">

       <div class="uk-width-1-1">
        @if($licences->isEmpty())
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
                    @foreach ($licences as $licence)
                    <tr>
                        <td>{{$licence->designation_licence}}</td>
                        <td>{{$licence->type_licence}}</td>
                        <td><i class="far fa-calendar-alt"></i> {{$licence->date_expiration}}</td>
                        <td>{{$licence->cle_licence}}</td>
                        <td>{{$licence->environnement}}</td>
                        <td>{{$licence->langage_version}}</td>
                        <td>{{$licence->sgbd_version}}</td>
                        <td class="d-flex align-item-center gap-2">
                            <div class="item"><a class="text-primary" href="/modifier_logiciel/{{$licence->id}}" title="Modifier"><ion-icon name="create-outline"></ion-icon></a></div>
                            <div class="item"><a class="text-danger" href="/delete_logiciel/{{$licence->id}}" OnClick="return confirm('Voulez-vous vraiment supprimer ce logiciel ?')"><ion-icon name="trash-outline"></ion-icon></a></div>
                            <div class="item"><a href="/logiciel/{{$licence->id}}"><ion-icon name="information-circle-outline"></ion-icon></a></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
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