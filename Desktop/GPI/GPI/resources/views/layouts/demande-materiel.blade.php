<header class="my-4">
  <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center">
    Gestion des Demandes de Matériels
  </h1>
  <p class="text-muted mb-0">
    Liste des demandes d'equipements • informatiques
  </p>
</header>


<div class="row g-4 mb-4">
  <!-- Demandes approuvées -->
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <!-- Gradient bar -->
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#28a745,#20c997);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Approuvées & Traitées</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $demandeApprouvées }}</span>
            <span class="small text-muted">Validées</span>
          </div>
        </div>

        <!-- Icône -->
        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#28a745,#20c997); width:64px; height:64px;">
          <i class="bi bi-check-square fs-3 text-white"></i>
        </div>
      </div>

      <!-- Pattern -->
      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-check-square" style="font-size:100px;"></i>
      </div>
    </div>
  </div>

  <!-- Demandes en attente -->
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#ffc107,#fd7e14);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">En attente</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $demandeEnAttente }}</span>
            <span class="small text-muted">En cours</span>
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
    </div>
  </div>

  <!-- Demandes rejetées -->
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card border-0 bg-light shadow-sm p-5 position-relative stat-card text-decoration-none text-dark">
      <div class="position-absolute top-0 start-0 end-0"
           style="height:5px; background:linear-gradient(90deg,#dc3545,#ff6b6b);"></div>

      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h4 class="fw-bold text-muted mb-2">Rejetées</h4>
          <div class="d-flex align-items-baseline gap-2">
            <span class="fs-1 fw-bold text-dark">{{ $demandeRefusées }}</span>
            <span class="small text-muted">Non validées</span>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-center rounded p-3"
             style="background:linear-gradient(135deg,#dc3545,#ff6b6b); width:64px; height:64px;">
          <i class="bi bi-slash-circle fs-3 text-white"></i>
        </div>
      </div>

      <div class="position-absolute bottom-0 end-0 opacity-25" style="width:100px; height:100px;">
        <i class="bi bi-slash-circle" style="font-size:100px;"></i>
      </div>
    </div>
  </div>
</div>
