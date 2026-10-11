<style>
:root {
    --sa-orange-start: #FF9500; --sa-orange-end: #FF6B00;
    --sa-green-start:  #009E60; --sa-green-end:  #00C778;
    --sa-blue-start:   #007AFF; --sa-blue-end:   #00A3FF;
    --sa-purple-start: #5856D6; --sa-purple-end: #8B89FF;
    --sa-gray-start:   #6C757D; --sa-gray-end:   #98A2AE;
}

.main-content { padding: 1rem; min-height: 100vh; }

/* Nav — dégradé de la vraie couleur de marque (pas le bg-success générique de Bootstrap) */
.sa-nav {
    background: linear-gradient(90deg, #046A38 0%, var(--sa-green-start) 55%, #046A38 100%);
}
.sa-nav-link {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem 1.1rem;
    border-radius: 999px;
    color: rgba(255,255,255,.78);
    font-size: .925rem;
    text-decoration: none;
    position: relative;
    transition: background-color .18s ease, color .18s ease;
}
.sa-nav-link:hover { background: rgba(255,255,255,.12); color: #fff; }
.sa-nav-link.active { background: rgba(255,255,255,.16); color: #fff; font-weight: 600; }
.sa-nav-link.active::after {
    content: '';
    position: absolute;
    left: 16px; right: 16px; bottom: 4px;
    height: 2px; border-radius: 2px;
    background: linear-gradient(90deg, var(--sa-orange-start) 0%, #ffffff 50%, var(--sa-green-end) 100%);
}

/* Stat cards — même patron que layouts/user-stat-card.blade.php (page admin) */
.sa-stat-card { transition: transform .2s ease, box-shadow .2s ease; }
a.sa-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }

/* Chart cards */
.chart-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
.chart-title { font-size: 15px; font-weight: 700; color: #1C1C1E; margin-bottom: 16px; }
.chart-container { position: relative; height: 260px; width: 100%; }

/* Bloc "Nécessite votre attention" */
.attention-item { border-left: 3px solid var(--bs-warning); background: #fff8ec; border-radius: 8px; padding: 12px 16px; }
.attention-item.danger { border-left-color: var(--bs-danger); background: #fdf1f1; }
.attention-item.ok     { border-left-color: var(--bs-success); background: #f0faf5; }
</style>
