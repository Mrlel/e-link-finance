@extends('layouts.main-board')
@section('title', 'Gestion des Équipements Informatiques')
@section('content')
<style>
:root {
    --primary-color: #FF9900;
    --secondary-color: #009E60;
    --light-bg: #f8f9fa;
    --border-radius: 10px;
    --box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-container {
    padding: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--box-shadow);
    transition: transform 0.3s ease;
    border-left: 4px solid var(--primary-color);
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.success {
    border-left-color: var(--secondary-color);
}

.stat-card.warning {
    border-left-color: var(--primary-color);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 15px;
    background: rgba(255, 153, 0, 0.1);
    color: var(--primary-color);
}

.stat-card.success .stat-icon {
    background: rgba(0, 158, 96, 0.1);
    color: var(--secondary-color);
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 14px;
}

.main-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.equipment-list {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--box-shadow);
}

.equipment-list h2 {
    margin-bottom: 20px;
    color: #333;
}

.equipment-table {
    width: 100%;
    border-collapse: collapse;
}

.equipment-table th,
.equipment-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.equipment-table th {
    background: var(--light-bg);
    font-weight: 600;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: rgba(0, 158, 96, 0.1);
    color: var(--secondary-color);
}

.status-badge.maintenance {
    background: rgba(255, 153, 0, 0.1);
    color: var(--primary-color);
}

.quick-actions {
    background: white;
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: var(--box-shadow);
}

.action-button {
    display: block;
    width: 100%;
    padding: 15px;
    margin-bottom: 10px;
    border: none;
    border-radius: var(--border-radius);
    background: var(--light-bg);
    color: #333;
    text-align: left;
    transition: all 0.3s ease;
}

.action-button:hover {
    background: var(--primary-color);
    color: white;
}

.search-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 14px;
}

.filter-button {
    padding: 10px 20px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: background 0.3s ease;
}

.filter-button:hover {
    background: darken(var(--primary-color), 10%);
}
</style>

<div class="dashboard-container">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-desktop"></i>
            </div>
            <div class="stat-value">{{$materielsEnStock->count()}}</div>
            <div class="stat-label">Équipements en Stock</div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{$materielsEnService->count()}}</div>
            <div class="stat-label">En Service</div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stat-value">{{$materielsEnMaintenance->count()}}</div>
            <div class="stat-label">En Maintenance</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{$materielsHorsService->count()}}</div>
            <div class="stat-label">Hors Service</div>
        </div>
    </div>

    <div class="main-content">
        <div class="equipment-list">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un équipement...">
                <button class="filter-button">Filtrer</button>
            </div>
            
            <h2>Liste des Équipements</h2>
            <table class="equipment-table">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Catégorie</th>
                        <th>Numéro de Série</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materielsEnStock as $materiel)
                    <tr>
                        <td>{{$materiel->des_equipement}}</td>
                        <td>{{$materiel->categorie}}</td>
                        <td>{{$materiel->numero_serie}}</td>
                        <td>
                            <span class="status-badge {{$materiel->etat == 'en service' ? 'active' : 'maintenance'}}">
                                {{$materiel->etat}}
                            </span>
                        </td>
                        <td>
                            <button class="action-button">Détails</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="quick-actions">
            <h2>Actions Rapides</h2>
            <button class="action-button">
                <i class="fas fa-plus-circle"></i> Ajouter un équipement
            </button>
            <button class="action-button">
                <i class="fas fa-sync-alt"></i> Demander une maintenance
            </button>
            <button class="action-button">
                <i class="fas fa-file-export"></i> Exporter le rapport
            </button>
            <button class="action-button">
                <i class="fas fa-chart-bar"></i> Voir les statistiques
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche en temps réel
    const searchInput = document.querySelector('.search-input');
    const tableRows = document.querySelectorAll('.equipment-table tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Animation des cartes statistiques
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-10px)';
        });

        card.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection