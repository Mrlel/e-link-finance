@extends('layouts.main-board')
@section('content')
<style>
    
        /* Equipment Grid */
        .equipment-section {
            padding: 32px 0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .equipment-item {
            padding: 20px;
            border: 1px solidrgb(253, 253, 253);
            border-radius: 8px;
            transition: all 0.2s ease;
            background: #fafbfc;
        }

        .equipment-item:hover {
            transition: all 0.2s ease;
            background: white;
            cursor: pointer;
        }

        .equipment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .equipment-name {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .equipment-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }

        .equipment-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }

        .equipment-count {
            font-size: 24px;
            font-weight: 700;
            color: #52c41a;
        }

        .equipment-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Color themes for equipment types */
        .ordinateur .equipment-icon, .ordinateur .stat-icon { background: #3498db; }
        .imprimante .equipment-icon, .imprimante .stat-icon { background: #e74c3c; }
        .scanner .equipment-icon, .scanner .stat-icon { background: #f39c12; }
        .photocopieuse .equipment-icon, .photocopieuse .stat-icon { background: #f39c12; }
        .stockage .equipment-icon, .stockage .stat-icon { background: #f39c12; }
        .visio .equipment-icon, .visio .stat-icon { background: #f39ac1; }
        .accessoire .equipment-icon, .accessoire .stat-icon { background: #f39c12; }
        .autre .equipment-icon, .autre .stat-icon { background: #f39c12; }
        .serveur .equipment-icon, .serveur .stat-icon { background: #9b59b6; }
        .routeur .equipment-icon, .routeur .stat-icon { background: #1abc9c; }
        .switch .equipment-icon, .switch .stat-icon { background: #34495e; }
        .onduleur .equipment-icon, .onduleur .stat-icon { background: #e67e22; }
        .projecteur .equipment-icon, .projecteur .stat-icon { background: #2ecc71; }
        .telephone .equipment-icon, .telephone .stat-icon { background: #8e44ad; }
        .accessoire .equipment-icon, .accessoire .stat-icon { background: #95a5a6; }
        .souris .equipment-icon, .souris .stat-icon { background: #95a5a6; }
        .clavier .equipment-icon, .clavier .stat-icon { background: #95a5a6; }
        .autre .equipment-icon, .autre .stat-icon { background: #7f8c8d; }
        .parfeu .equipment-icon, .parfeu .stat-icon { background: #7f8c8d; }

        /* Summary section */
        .summary-section {
            margin-top: 32px;
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e9ecef;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .summary-item {
            text-align: center;
            padding: 16px;
        }

        .summary-number {
            font-size: 24px;
            font-weight: 700;
            color: #52c41a;
            margin-bottom: 4px;
        }

        .summary-label {
            font-size: 13px;
            color: #7f8c8d;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }
            
            .equipment-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        /* Loading animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .equipment-item {
            animation: fadeIn 0.3s ease-out;
        }

        .equipment-item:nth-child(1) { animation-delay: 0.05s; }
        .equipment-item:nth-child(2) { animation-delay: 0.1s; }
        .equipment-item:nth-child(3) { animation-delay: 0.15s; }
        .equipment-item:nth-child(4) { animation-delay: 0.2s; }
        .equipment-item:nth-child(5) { animation-delay: 0.25s; }
        .equipment-item:nth-child(6) { animation-delay: 0.3s; }


              .title {
            text-align: center;
            font-size: 32px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 60px;
        }

        .stats-grid {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-between;
          
        }

        .stat-card {
            background: white;
            border-radius: 9px;
            padding: 15px 20px;
            height: 180px;
            width: 330px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.purple {
            background-color: #e8e2ff;
            color: #6366f1;
        }

        .stat-icon.green {
            background-color: #dcfce7;
            color: #22c55e;
        }

        .stat-icon.orange {
            background-color:rgba(44, 62, 80, 0.26);
            color: #2C3E50;
        }

        .stat-icon.blue {
            background-color: #dbeafe;
            color: #3b82f6;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
            line-height: 1;
        }

        .stat-label {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .stat-card {
                padding: 30px 20px;
                margin: auto;
            }
            
            .title {
                font-size: 28px;
                margin-bottom: 40px;
            }
        }
    </style>
</style>
             <div class="stats-grid d-flex flex-column flex-md-row gap-3">
            <a href="/userlist" class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{$Users->count()}}</div>
                <div class="stat-label">Total Agents</div>
            </a>

            <a href="/stock_materiel" class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-computer"></i>
                </div>
                <div class="stat-number">{{ $equipements->count() }}</div>
                <div class="stat-label">Total Equipements</div>
            </a>

            <a href="/list_logiciel" class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-cloud"></i>
                </div>
                <div class="stat-number">{{ $logiciels->count() }}</div>
                <div class="stat-label">Total Logiciels</div>
            </a>
        </div>



<!-- Equipment Section -->
<div class="equipment-section">
            <div class="d-flex align-items-end mb-3">
               <a href="{{ route('admin.dashboard.export-pdf') }}" class="btn btn-success p-2">
                        <i class="fas fa-download"></i> Exporter les données en PDF
                    </a>
            </div>
            
            <div class="equipment-grid">
                <!-- Ordinateurs portables -->
                @if($ordinateurs_portables->count() > 0)
                <div class="equipment-item ordinateur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <span class="equipment-title">Ordinateurs portables</span>
                        </div>
                        <div class="equipment-count">{{ count($ordinateurs_portables) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Postes mobiles</span>
                    </div>
                </div>
                @endif
                <!-- Ordinateurs All-in-one -->
                @if($ordinateurs_allinone->count() > 0)
                <div class="equipment-item ordinateur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <span class="equipment-title">Ordinateurs All-in-one</span>
                        </div>
                        <div class="equipment-count">{{ count($ordinateurs_allinone) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Tout-en-un</span>
                    </div>
                </div>
                @endif
                <!-- Unités centrales -->
                @if($unites_centrales->count() > 0)
                <div class="equipment-item ordinateur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <span class="equipment-title">Unités centrales</span>
                        </div>
                        <div class="equipment-count">{{ count($unites_centrales) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>PC fixes</span>
                    </div>
                </div>
                @endif
                <!-- Imprimantes -->
                @if($imprimantes->count() > 0)
                <div class="equipment-item imprimante">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-print"></i>
                            </div>
                            <span class="equipment-title">Imprimantes</span>
                        </div>
                        <div class="equipment-count">{{ count($imprimantes) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Impression documents</span>
                    </div>
                </div>
                @endif
                <!-- Ordinateurs portables -->
                @if($ecrans->count() > 0)
                <div class="equipment-item ordinateur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <span class="equipment-title">Ecrans</span>
                        </div>
                        <div class="equipment-count">{{ count($ecrans) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Moniteur</span>
                    </div>
                </div>
                @endif
                <!-- Claviers -->
                @if($claviers->count() > 0)
                <div class="equipment-item clavier">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-keyboard"></i>
                            </div>
                            <span class="equipment-title">Claviers</span>
                        </div>
                        <div class="equipment-count">{{ count($claviers) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Entrée clavier</span>
                    </div>
                </div>
                @endif
                <!-- Souris -->
                @if($souris->count() > 0)
                <div class="equipment-item souris">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-mouse"></i>
                            </div>
                            <span class="equipment-title">Souris</span>
                        </div>
                        <div class="equipment-count">{{ count($souris) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Entrée souris</span>
                    </div>
                </div>
                @endif
                <!-- Scanners -->
                @if($scanners->count() > 0)
                <div class="equipment-item scanner">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-print"></i>
                            </div>
                            <span class="equipment-title">Scanners</span>
                        </div>
                        <div class="equipment-count">{{ count($scanners) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Numérisation</span>
                    </div>
                </div>
                @endif
                <!-- Serveurs -->
                @if($serveurs->count() > 0)
                <div class="equipment-item serveur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <span class="equipment-title">Serveurs</span>
                        </div>
                        <div class="equipment-count">{{ count($serveurs) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Infrastructure IT</span>
                    </div>
                </div>
                @endif
                <!-- Routeurs -->
                @if($routeurs->count() > 0)
                <div class="equipment-item routeur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <span class="equipment-title">Routeurs</span>
                        </div>
                        <div class="equipment-count">{{ count($routeurs) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Réseau sans fil</span>
                    </div>
                </div>
                @endif
                <!-- Switchs -->
                @if($switchs->count() > 0)
                <div class="equipment-item switch">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <span class="equipment-title">Switchs</span>
                        </div>
                        <div class="equipment-count">{{ count($switchs) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Réseau filaire</span>
                    </div>
                </div>
                @endif
                <!-- Onduleurs -->
                @if($onduleurs->count() > 0)
                <div class="equipment-item onduleur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-battery-three-quarters"></i>
                            </div>
                            <span class="equipment-title">Onduleurs</span>
                        </div>
                        <div class="equipment-count">{{ count($onduleurs) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Protection électrique</span>
                    </div>
                </div>
                @endif
                <!-- Projecteurs -->
                @if($projecteurs->count() > 0)
                <div class="equipment-item projecteur">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <span class="equipment-title">Projecteurs</span>
                        </div>
                        <div class="equipment-count">{{ count($projecteurs) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Projection vidéo</span>
                    </div>
                </div>
                @endif
                <!-- Téléphones IP -->
                @if($telephones_ip->count() > 0)
                <div class="equipment-item telephone">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <span class="equipment-title">Téléphones IP</span>
                        </div>
                        <div class="equipment-count">{{ count($telephones_ip) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Téléphonie IP</span>
                    </div>
                </div>
                @endif

                <!--parefeux -->
                @if($parefeux->count() > 0)
                <div class="equipment-item parefeu">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-shield"></i>
                            </div>
                            <span class="equipment-title">Pare-feux</span>
                        </div>
                        <div class="equipment-count">{{ count($parefeux) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Protection contre les attaques</span>
                    </div>
                </div>
                @endif
                <!--photocopieuses -->
                @if($photocopieuses->count() > 0)
                <div class="equipment-item photocopieuse">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-print"></i>
                            </div>
                            <span class="equipment-title">Photocopieuses</span>
                        </div>
                        <div class="equipment-count">{{ count($photocopieuses) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Numérisation</span>
                    </div>
                </div>
                @endif
                <!--stockages -->
                @if($stockages->count() > 0)
                <div class="equipment-item stockage">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <span class="equipment-title">Stockages</span>
                        </div>
                        <div class="equipment-count">{{ count($stockages) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Stockage de données</span>
                    </div>
                </div>
                @endif
                <!--visio -->
                @if($visio->count() > 0)
                <div class="equipment-item visio">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <span class="equipment-title">Visioconference</span>
                        </div>
                        <div class="equipment-count">{{ count($visio) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Communication vidéo</span>
                    </div>
                </div>
                @endif
                <!--accessoires electriques -->
                @if($accessoires_electriques->count() > 0)
                <div class="equipment-item accessoire">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <span class="equipment-title">Accessoires electriques</span>
                        </div>
                        <div class="equipment-count">{{ count($accessoires_electriques) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Périphériques divers</span>
                    </div>
                </div>
                @endif
                
                <!--accessoires reseau -->
                @if($accessoires_reseau->count() > 0)
                <div class="equipment-item accessoire">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-cube"></i>
                            </div>
                            <span class="equipment-title">Accessoires reseau</span>
                        </div>
                        <div class="equipment-count">{{ count($accessoires_reseau) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Équipements divers</span>
                    </div>
                </div>
                @endif
                <!--accessoires securite -->
                @if($accessoires_securite->count() > 0)
                <div class="equipment-item accessoire">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span class="equipment-title">Accessoires securite</span>
                        </div>
                        <div class="equipment-count">{{ count($accessoires_securite) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Protection contre les attaques</span>
                    </div>
                </div>
                @endif
                <!--autres -->
                @if($autres->count() > 0)
                <div class="equipment-item autre">
                    <div class="equipment-header">
                        <div class="equipment-name">
                            <div class="equipment-icon">
                                <i class="fas fa-cube"></i>
                            </div>
                            <span class="equipment-title">Autres</span>
                        </div>
                        <div class="equipment-count">{{ count($autres) }}</div>
                    </div>
                    <div class="equipment-meta">
                        <span>Équipements divers</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Summary -->
        <div class="mt-4">  
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
    </div>

    



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Calculate totals and animate counters
        const counts = [
            {{ count($ordinateurs_portables) }},
            {{ count($ordinateurs_allinone) }},
            {{ count($unites_centrales) }},
            {{ count($outillages_techniques) }},
            {{ count($imprimantes) }},
            {{ count($scanners) }},
            {{ count($serveurs) }},
            {{ count($routeurs) }},
            {{ count($switchs) }},
            {{ count($onduleurs) }},
            {{ count($projecteurs) }},
            {{ count($telephones_ip) }},
            {{ count($accessoires_electriques) }},
            {{ count($accessoires_reseau) }},
            {{ count($accessoires_securite) }},
            {{ count($photocopieuses) }},
            {{ count($stockages) }},
            {{ count($visio) }},
            {{ count($parefeux) }},
            {{ count($autres) }}
        ];

        const categories = [
            'Ordinateurs', 'Ordinateurs all-in-one', 'Unites centrales', 'Outillages techniques', 'Imprimantes', 'Ecrans', 'Claviers', 'Souris', 'Scanners', 'Serveurs',
            'Routeurs', 'Switchs', 'Onduleurs', 'Projecteurs',
            'Téléphones IP', 'Accessoires', 'Accessoires reseau', 'Accessoires securite', 'Photocopieuses', 'Stockages', 'Visioconference', 'Pare-feux', 'Autres'
        ];

        const total = counts.reduce((sum, count) => sum + count, 0);
        const maxIndex = counts.indexOf(Math.max(...counts));

        // Animate counter function
        function animateCounter(element, target, duration = 1000) {
            let start = 0;
            const startTime = Date.now();
            
            function update() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(start + (target - start) * progress);
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            update();
        }

        // Initialize counters
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                animateCounter(document.getElementById('totalCount'), total);
                animateCounter(document.getElementById('activeCount'), total);
                animateCounter(document.getElementById('summaryTotal'), total);
                
                if (total > 0) {
                    document.getElementById('mostUsed').textContent = categories[maxIndex];
                }
            }, 300);
        });
    </script>
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