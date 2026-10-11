@extends('layouts.main-board')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ajouter un Document') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="titre" class="col-md-4 col-form-label text-md-right">{{ __('Titre') }}</label>
                            <div class="col-md-6">
                                <input id="titre" type="text" class="form-control @error('titre') is-invalid @enderror" name="titre" value="{{ old('titre') }}" required autofocus>
                                @error('titre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="type_document" class="col-md-4 col-form-label text-md-right">{{ __('Type de Document') }}</label>
                            <div class="col-md-6">
                                <select id="type_document" class="form-control @error('type_document') is-invalid @enderror" name="type_document" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="manuel">Manuel d'utilisation</option>
                                    <option value="facture">Facture</option>
                                    <option value="garantie">Garantie</option>
                                    <option value="autre">Autre</option>
                                </select>
                                @error('type_document')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="document" class="col-md-4 col-form-label text-md-right">{{ __('Document') }}</label>
                            <div class="col-md-6">
                                <input id="document" type="file" class="form-control @error('document') is-invalid @enderror" name="document" required>
                                @error('document')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="equipement_id" class="col-md-4 col-form-label text-md-right">{{ __('Équipement') }}</label>
                            <div class="col-md-6">
                                <select id="equipement_id" class="form-control @error('equipement_id') is-invalid @enderror" name="equipement_id" required>
                                    <option value="">Sélectionnez un équipement</option>
                                    @foreach($equipements as $equipement)
                                        <option value="{{ $equipement->id }}">{{ $equipement->numero_serie }}-{{ $equipement->modele }} </option>
                                    @endforeach
                                </select>
                                @error('equipement_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enregistrer') }}
                                </button>
                                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                    {{ __('Annuler') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection