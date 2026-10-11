
<div class="row g-4 mb-4">
  <!-- Total logiciels -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a href="/list_logiciel" class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <!-- Decorative gradient bar -->
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(135deg,#28a745,#20c997);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <!-- Texte -->
        <div>
          <h4 class="fw-bold text-muted mb-2">Total logiciels</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $licences->count() }}</span>
            <span class="small text-muted">Installés</span>
          </div>
        </div>

        <!-- Icône -->
        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#28a745,#20c997); width:64px; height:64px;">
          <i class="bi bi-cloud-download fs-3 text-white"></i>
        </div>
      </div>

      <!-- Pattern -->
      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-cloud-download" style="font-size:100px;"></i>
      </div>
    </a>
  </div>

  <!-- Logiciels bientôt expirés -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a href="/logiciel_bientot_expire" class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#ffc107,#fd7e14);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Bientôt expirés</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $licencesBientotExpirees->count() }}</span>
            <span class="small text-muted">À surveiller</span>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#ffc107,#fd7e14); width:64px; height:64px;">
          <i class="bi bi-hourglass-split fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-hourglass-split" style="font-size:100px;"></i>
      </div>
    </a>
  </div>

  <!-- Logiciels expirés -->
  <div class="col-12 col-sm-6 col-lg-4">
    <a href="/logiciel_expire" class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(135deg,#28a745,#20c997);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Expirés</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $licencesExpirees->count() }}</span>
            <span class="small text-muted">Non valides</span>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#28a745,#20c997); width:64px; height:64px;">
          <i class="bi bi-exclamation-triangle fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-exclamation-triangle" style="font-size:100px;"></i>
      </div>
    </a>
  </div>
</div>
<div class="d-flex justify-content-between align-items-center flex-wrap mt-4 gap-3">

    <!-- 🔍 Barre de recherche -->
    <div class="ui action input flex-grow-1" style="max-width: 400px;">
        <input type="text" id="searchLogiciel" placeholder="Rechercher un logiciel...">
        <button class="ui icon button">
            <i class="search icon"></i>
        </button>
    </div>

    <!-- 🟦 Boutons -->
    <div class="d-flex align-items-center gap-2 mb-4">

        <!-- 🟩 Nouveau logiciel -->
        <a href="{{ route('logiciel.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Nouveau logiciel
        </a>

        <!-- 🔽 Dropdown Options -->
        <div class="dropdown">
            <button class="btn border dropdown-toggle" type="button" id="dropdownOptions" data-bs-toggle="dropdown" aria-expanded="false">
                Options
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownOptions">

                <!-- 
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignerModal">
                        <i class="bi bi-pc-display"></i> Assigner logiciel
                    </a>
                </li>-->

                <!-- Importer -->
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload"></i> Importer
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <!-- Export PDF -->
                <li>
                    <form action="{{ Route('export.logiciels.pdf') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded-2">
                            <i class="bi bi-file-earmark-pdf-fill fs-5 text-danger"></i>
                            <span>Exporter en PDF</span>
                        </button>
                    </form>
                </li>

                <!-- Export Excel -->
                <li>
                    <form action="{{ route('export.logiciels.excel') }}" method="post" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded-2">
                            <i class="bi bi-file-earmark-excel-fill fs-5 text-success"></i>
                            <span>Exporter en Excel</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>

</div>


    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h4>Importer des logiciels</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <div class="import-section">
                        <form action="{{ route('import.logiciels') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="fichier">Fichier Excel (.xlsx, .xls)</label>
                                <input type="file" name="fichier" class="form-control" accept=".xlsx,.xls" required>
                            </div>
                            <button type="submit" class="btn btn-success mt-4 w-100"><i class="fas fa-download me-2"></i>Importer</button>
                        </form>
                    </div>
                </div>
            </div>
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

<script>
    // Fonction de recherche en temps réel
    function initializeSearch() {
        const searchInput = document.getElementById("searchLogiciel");
        
        if (!searchInput) {
            console.warn("Élément searchLogiciel non trouvé");
            return;
        }
        
        searchInput.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase().trim();
            const tables = document.querySelectorAll("table tbody");
            
            tables.forEach(table => {
                const rows = table.querySelectorAll("tr");
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    
                    if (searchTerm === "" || text.includes(searchTerm)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    }
    
    // Exécuter quand le DOM est prêt
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initializeSearch);
    } else {
        initializeSearch();
    }
</script>