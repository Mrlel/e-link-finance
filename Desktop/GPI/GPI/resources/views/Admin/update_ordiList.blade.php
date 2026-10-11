@extends('layouts.main-board')

@section('content')
@include('layouts.message')
    <!-- En-tête stylisé -->
    <div style="text-align: center; margin-bottom: 1rem;">
            <h1> Modifier ordinateur</h1>
        </div>
    
    <form class="ui form" action="/update_equipemnt/traitement" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $equipement->id }}">
        
        <!-- Informations générales -->
        <h4 class="p-2" style="border-left: 4px solid orange; background-color:rgb(252, 236, 226);">Informations générals</h4>
        <div class="three fields">
            <div class="field">
                <label>Nom</label>
                <div class="ui left icon input">
                    <input type="text" name="des_equipement" placeholder="Nom de l'équipement" value="{{ $equipement->des_equipement ?? '' }}">
                    <i class="tag icon" style="color: #009E60;"></i>
                </div>
            </div>
            <div class="field">
                <label>Catégorie</label>
                <div class="ui left icon input">
                    <input type="text" name="categorie" placeholder="Catégorie" value="{{ $equipement->categorie ?? '' }}">
                    <i class="archive icon" style="color: #009E60;"></i>
                </div>
            </div>
            <div class="field">
                <label>Marque</label>
                <div class="ui left icon input">
                    <input type="text" name="marque" placeholder="Marque" value="{{ $equipement->marque ?? '' }}">
                    <i class="factory icon" style="color: #009E60;"></i>
                </div>
            </div>
        </div>

        <!-- Spécifications techniques -->
        <h4 class="p-2" style="border-left: 4px solid orange; background-color:rgb(252, 236, 226);">Spécifications techniques</h4>
        <div class="three fields">
            <div class="field">
                <label>Numéro de Série</label>
                <div class="ui left icon input">
                    <input type="text" name="numero_serie" placeholder="Numéro de série" value="{{ $equipement->numero_serie ?? '' }}">
                    <i class="barcode icon" style="color: #009E60;"></i>
                </div>
            </div>
            <div class="field">
                <label>Modèle</label>
                <div class="ui left icon input">
                    <input type="text" name="modele" placeholder="Modèle" value="{{ $equipement->modele ?? '' }}">
                    <i class="cube icon" style="color: #009E60;"></i>
                </div>
            </div>
            <div class="field">
                <label>État</label>
                <div class="ui selection dropdown" style="border-color: #009E60;">
                    <input type="hidden" name="etat">
                    <i class="dropdown icon"></i>
                    <div class="default text">Sélectionner l'état</div>
                    <div class="menu">
                        <div class="item" data-value="en stock" {{ $equipement->etat == 'en stock' ? 'selected' : '' }}>
                            <i class="box icon" style="color: #FF8200;"></i> En stock
                        </div>
                        <div class="item" data-value="en service" {{ $equipement->etat == 'en service' ? 'selected' : '' }}>
                            <i class="play circle icon" style="color: #009E60;"></i> En service
                        </div>
                        <div class="item" data-value="en maintenance" {{ $equipement->etat == 'en maintenance' ? 'selected' : '' }}>
                            <i class="wrench icon" style="color: #FF8200;"></i> En maintenance
                        </div>
                        <div class="item" data-value="hors service" {{ $equipement->etat == 'hors service' ? 'selected' : '' }}>
                            <i class="ban icon" style="color: #FF0000;"></i> Hors service
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Caractéristiques additionnelles -->
        <h4 class="p-2" style="border-left: 4px solid orange; background-color:rgb(252, 236, 226);">Caractéristiques additionnelles</h4>
        <div class="three fields">
            <div class="field">
                <label>Date d'Acquisition</label>
                <div class="ui left icon input">
                    <input type="date" name="date_acquis" value="{{ $equipement->date_acquis ?? '' }}">
                    <i class="calendar alternate icon" style="color: #009E60;"></i>
                </div>
            </div>
            <div class="field">
                <label>Stockage (Go)</label>
                <div class="ui left icon input">
                    <input type="number" name="capacite" placeholder="Capacité de stockage" value="{{ $equipement->capacite ?? '' }}">
                    <i class="hdd outline icon" style="color: #009E60;"></i>
                </div>
            </div>
            <div class="field">
                <label>RAM (Go)</label>
                <div class="ui left icon input">
                    <input type="number" name="ram" placeholder="Mémoire RAM" value="{{ $equipement->ram ?? '' }}">
                    <i class="microchip icon" style="color: #009E60;"></i>
                </div>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <div style="margin-top: 2.5em; text-align: center;">
            <button type="submit" class="ui labeled icon button" style="background-color: #009E60; color: white;">
                <i class="save icon"></i>
                Enregistrer les modifications
            </button>
        </div>
    </form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
<script>
    // Initialiser les dropdowns
    $('.ui.dropdown').dropdown();

    // Définir la valeur actuelle de l'état
    $('.ui.dropdown').dropdown('set selected', '{{ $equipement->etat }}');

    // Initialiser les checkboxes
    $('.ui.checkbox').checkbox();
    
    // Animation au survol du bouton
    $('.ui.button').hover(
        function() {
            $(this).css('background-color', '#009E60');
        },
        function() {
            $(this).css('background-color', '#FF8200');
        }
    );
</script>

@endsection