<div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
    @if($prev)
    <a href="{{ route('formation.module', $prev['slug']) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> {{ $prev['titre'] }}
    </a>
    @else
    <a href="{{ route('formation.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-house me-1"></i> Accueil formation
    </a>
    @endif

    @if($next)
    <a href="{{ route('formation.module', $next['slug']) }}" class="btn btn-success">
        {{ $next['titre'] }} <i class="bi bi-arrow-right ms-1"></i>
    </a>
    @else
    <a href="{{ route('formation.index') }}" class="btn btn-success">
        <i class="bi bi-check-circle me-1"></i> Terminer la formation
    </a>
    @endif
</div>
