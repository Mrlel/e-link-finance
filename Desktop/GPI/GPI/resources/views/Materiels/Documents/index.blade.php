@extends('layouts.main-board')

@section('content')

    <h2>Liste des Documents</h2>
    <a href="{{ route('documents.create') }}" class="btn btn-primary mb-3">Ajouter un document</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Équipement</th>
                    <th>Ajouté par</th>
                    <th>Date d'ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->titre }}</td>
                    <td>{{ $document->type_document }}</td>
                    <td>{{ $document->equipement->nom ?? 'N/A' }}</td>
                    <td>{{ $document->user->nom ?? 'N/A' }}</td>
                    <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-info">Voir</a>
                        <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection