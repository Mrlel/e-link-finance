@php
    $dirId = Auth::user()->direction_id;
    $vDisponibles  = $vehicules->where('statut', 'disponible')->count();
    $vAffecter     = $vehicules->where('statut', 'affecté')->count();
    $vMaintenance  = $vehicules->where('statut', 'en maintenance')->count();
    $vTotal        = $vehicules->count();
@endphp

<!-- Stat Cards Véhicules -->
<div class="row g-4 mb-4 pt-4">

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 bg-light shadow-sm p-4 position-relative stat-card text-decoration-none text-dark d-block">
            <div class="position-absolute top-0 start-0 end-0" style="height:4px;background:linear-gradient(90deg,#198754,#20c997);border-radius:4px 4px 0 0;"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-semibold text-muted mb-1 small">Total véhicules</p>
                    <span class="fs-2 fw-bold text-dark">{{ $vTotal }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-3" style="background:linear-gradient(135deg,#198754,#20c997);width:52px;height:52px;">
                    <i class="bi bi-car-front-fill fs-4 text-white"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-25" style="width:50px; height:50px;">
                <i class="bi bi-car-front-fill" style="font-size:50px;"></i>
            </div>
</div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 bg-light shadow-sm p-4 position-relative stat-card text-decoration-none text-dark d-block">
            <div class="position-absolute top-0 start-0 end-0" style="height:4px;background:linear-gradient(90deg,#0d6efd,#6ea8fe);border-radius:4px 4px 0 0;"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-semibold text-muted mb-1 small">Disponibles</p>
                    <span class="fs-2 fw-bold text-dark">{{ $vDisponibles }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-3" style="background:linear-gradient(135deg,#0d6efd,#6ea8fe);width:52px;height:52px;">
                    <i class="bi bi-check-circle-fill fs-4 text-white"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-25" style="width:50px; height:50px;">
                <i class="bi bi-check-circle-fill" style="font-size:50px;"></i>
            </div>
</div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 bg-light shadow-sm p-4 position-relative stat-card text-decoration-none text-dark d-block">
            <div class="position-absolute top-0 start-0 end-0" style="height:4px;background:linear-gradient(90deg,#ff7f50,#ffb347);border-radius:4px 4px 0 0;"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-semibold text-muted mb-1 small">Affectés</p>
                    <span class="fs-2 fw-bold text-dark">{{ $vAffecter }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-3" style="background:linear-gradient(135deg,#ff7f50,#ffb347);width:52px;height:52px;">
                    <i class="bi bi-person-fill-check fs-4 text-white"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-25" style="width:50px; height:50px;">
                <i class="bi bi-person-fill-check" style="font-size:50px;"></i>
            </div>
</div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 bg-light shadow-sm p-4 position-relative stat-card text-decoration-none text-dark d-block">
            <div class="position-absolute top-0 start-0 end-0" style="height:4px;background:linear-gradient(90deg,#ffc107,#fd7e14);border-radius:4px 4px 0 0;"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-semibold text-muted mb-1 small">En maintenance</p>
                    <span class="fs-2 fw-bold text-dark">{{ $vMaintenance }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-3" style="background:linear-gradient(135deg,#ffc107,#fd7e14);width:52px;height:52px;">
                    <i class="bi bi-gear-fill fs-4 text-white"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 opacity-25" style="width:50px; height:50px;">
                <i class="bi bi-gear-fill" style="font-size:50px;"></i>
            </div>
</div>
    </div>

</div>
