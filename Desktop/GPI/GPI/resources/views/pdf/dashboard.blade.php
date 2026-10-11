<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tableau de Bord - Export PDF</title>
</head>
<body>
<div class="document-container">
        <!-- En-tête en deux colonnes via tableau -->
        <table class="official-header">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div class="ministry-title">MINISTÈRE</div>
                    <div class="ministry-title">DU PLAN ET DU DÉVELOPPEMENT</div>
                    <div class="mini-space"></div>
                    <h2 class="nom-direction" style="text-transform:uppercase;text-align:center">{{ Auth::user()->direction->code_direction}}</h2>
                </td>

                </td>
                <td style="width: 50%; text-align: center;">
                    <div class="republic-info">
                        <div class="republic-title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
                        <div class="republic-motto">Union - Discipline - Travail</div>
                        <div class="mini-space"></div>
                        <div class="flag-colors">
                            <div class="flag-color flag-orange"></div>
                            <div class="flag-color flag-white"></div>
                            <div class="flag-color flag-green"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="space"></div>
        <!-- Titre -->
        <h2 style="text-decoration: underline;font-size: 18px;">Tableau de bord</h2>

        <!-- 
        <div class="additional-info">
            <p><strong>Date :</strong> {{ now()->format('d F Y à H:i') }}</p>
        </div>-->
        <div class="space"></div>

    <div class="section">
        <h2>État des Équipements</h2>
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-label">En stock</div>
                <div class="stat-number">{{ $equipementsEnStock }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">En service</div>
                <div class="stat-number">{{ $equipementsEnService }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">En maintenance</div>
                <div class="stat-number">{{ $equipementsEnMaintenance }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Hors service</div>
                <div class="stat-number">{{ $equipementsHorsService }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Statut des Licences</h2>
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-label">Actives</div>
                <div class="stat-number">{{ $licencesActives }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Bientôt expirées</div>
                <div class="stat-number">{{ $licencesBientotExpirees }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Expirées</div>
                <div class="stat-number">{{ $licencesExpirees }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Utilisateurs</h2>
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-label">Total utilisateurs</div>
                <div class="stat-number">{{ $usersEquipes + $usersNonEquipes }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Équipés</div>
                <div class="stat-number">{{ $usersEquipes }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Non équipés</div>
                <div class="stat-number">{{ $usersNonEquipes }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Répartition des Équipements par Catégorie</h2>
        <div class="equipment-grid">
            @foreach($statsEquipements as $categorie => $nombre)
                @if($nombre > 0)
                    <div class="equipment-item">
                        <div class="equipment-category">{{ ucfirst($categorie) }}</div>
                        <div class="equipment-count">{{ $nombre }}</div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="footer">
        Document généré le {{ $dateGeneration }}
    </div>
</body>
</html>
