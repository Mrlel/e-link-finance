@extends('layouts.main-board')
@section('content')
<div class="stats-row">
                <div class="stat-card customers">
                    <a href="/userlist">
                    <div class="stat-sign user">
                        <div class="stat-card-title">Utilisateurs</div>
                        <div class="stat-card-value">{{$Users->count()}}</div>
                    </div>
                    <div class="stat-card-icon">
                    <ion-icon name="people-outline"></ion-icon>
                    </div>
                    </a>
                </div>
                
                <div class="stat-card revenue">
                    <a href="/stock_materiel">
                    <div class="stat-sign equip">
                        <div class="stat-card-title">Equipements</div>
                        <div class="stat-card-value">{{$equipements->count()}}</div>
                    </div>
                    <div class="stat-card-icon">
                        <ion-icon name="desktop-outline"></ion-icon>
                    </div>
                    </a>
                </div>
                
                <div class="stat-card profit">
                    <a href="/list_logiciel">
                    <div class="stat-sign soft">
                        <div class="stat-card-title">Logiciels</div>
                        <div class="stat-card-value">{{$logiciels->count()}}</div>
                    </div>
                    <div class="stat-card-icon">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                    </div>
                    </a>
                </div>
                
                <div class="stat-card invoices">
                    <a href="/demande-materiel">
                    <div class="stat-sign invoice">
                        <div class="stat-card-title">Demandes de matériel</div>
                        <div class="stat-card-value">{{$demandes->count()}}</div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    </a>
                </div>
            </div>
          
            <div class="charts-row">
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Statistiques des utilisateurs</div>
                    </div>
                    <canvas id="userChart" style="max-height: 200px;"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Statistiques des licences</div>
                    </div>
                    <canvas id="licenceChart" style="max-height: 200px;"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Statistiques des équipements</div>
                    </div>
                    <canvas id="equipementChart" style="max-height: 200px;"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Demandes de maintenance</div>
                    </div>
                    <canvas id="maintenanceChart" style="max-height: 200px;"></canvas>
                </div>
            </div>

         
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration globale pour un look ultra-moderne
    Chart.defaults.font.family = "'Montserrat', 'Roboto', sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.font.weight = '500';
    Chart.defaults.elements.arc.borderWidth = 0;
    Chart.defaults.elements.bar.borderRadius = 12;
    Chart.defaults.elements.line.tension = 0.5;
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(17, 24, 39, 0.85)';
    Chart.defaults.plugins.tooltip.titleColor = '#fff';
    Chart.defaults.plugins.tooltip.bodyColor = '#fff';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 20;
    Chart.defaults.plugins.tooltip.displayColors = false;

    // Palettes ultra-modernes avec dégradés
    const getGradient = (ctx, colorStart, colorEnd) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    };

    // Graphique des utilisateurs (Doughnut)
    const userCtx = document.getElementById('userChart').getContext('2d');
    const userGradients = [
        ['rgba(136, 69, 131, 0.75)', 'rgba(152, 97, 149, 0.98)'],
        ['rgb(122, 210, 182)', 'rgb(110, 184, 143)'],
        ['rgb(255, 82, 157)', 'rgba(244, 113, 196, 0.9)']
    ].map(([start, end]) => {
        const gradient = userCtx.createLinearGradient(0, 0, 1, 200);
        gradient.addColorStop(0, start);
        gradient.addColorStop(1, end);
        return gradient;
    });

    const userData = {
        labels: ['Total Utilisateurs', 'Utilisateurs Équipés', 'Utilisateurs Non Équipés'],
        datasets: [{
            label: 'Utilisateurs',
            data: [{{ $Users->count() }}, {{ $users_equipes->count() }}, {{ $users_non_equipes->count() }}],
            backgroundColor: userGradients,
            borderWidth: 0,
            cutout: '80%',
            borderRadius: 10
        }]
    };

    new Chart(userCtx, {
        type: 'doughnut',
        data: userData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        padding: 15
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });

    // Graphique des licences (Bar)
    const licenceCtx = document.getElementById('licenceChart').getContext('2d');
    const licenceGradient = getGradient(licenceCtx, 'rgba(13, 133, 246, 0.68)', 'rgba(253, 252, 252, 0.98)');
    const licenceData = {
        labels: @json($chartData['licences']['labels']),
        datasets: [{
            label: 'Licences',
            data: @json($chartData['licences']['values']),
            backgroundColor: licenceGradient,
            borderWidth: 0,
            barPercentage: 0.65,
            categoryPercentage: 0.8
        }]
    };

    new Chart(licenceCtx, {
        type: 'bar',
        data: licenceData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(203, 213, 225, 0.2)',
                        drawBorder: false
                    },
                    ticks: {
                        precision: 0,
                        color: 'rgba(100, 116, 139, 0.8)'
                    },
                    border: {
                        display: true
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: 'rgba(100, 116, 139, 0.8)'
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            }
        }
    });

    // Graphique des équipements (Bar)
    const equipementCtx = document.getElementById('equipementChart').getContext('2d');
    const equipementGradient = getGradient(equipementCtx, 'rgb(5, 152, 103)', 'rgba(244, 249, 247, 0.97)');
    const equipementData = {
        labels: @json($chartData['equipements']['labels']),
        datasets: [{
            label: 'Équipements',
            data: @json($chartData['equipements']['values']),
            backgroundColor: equipementGradient,
            borderWidth: 0,
            barPercentage: 0.65,
            categoryPercentage: 0.8
        }]
    };

    new Chart(equipementCtx, {
        type: 'bar',
        data: equipementData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(203, 213, 225, 0.2)',
                        drawBorder: false
                    },
                    ticks: {
                        precision: 0,
                        color: 'rgba(100, 116, 139, 0.8)'
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: 'rgba(100, 116, 139, 0.8)'
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            }
        }
    });

    // Graphique des demandes de maintenance (Line)
    const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
    const maintenanceGradient = getGradient(maintenanceCtx, 'rgba(249, 115, 22, 0.4)', 'rgba(249, 115, 22, 0.05)');
    const maintenanceData = {
        labels: @json($chartData['demandes']['labels']),
        datasets: [{
            label: 'Demandes',
            data: @json($chartData['demandes']['values']),
            backgroundColor: maintenanceGradient,
            borderColor: 'rgba(249, 115, 22, 0.8)',
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 7,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(249, 115, 22, 0.8)',
            pointBorderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };

    new Chart(maintenanceCtx, {
        type: 'line',
        data: maintenanceData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(203, 213, 225, 0.2)',
                        drawBorder: false
                    },
                    ticks: {
                        precision: 0,
                        color: 'rgba(100, 116, 139, 0.8)'
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: 'rgba(100, 116, 139, 0.8)'
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
</script>
@endsection