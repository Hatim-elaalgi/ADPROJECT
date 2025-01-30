@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-lightbulb me-2"></i>Proposer un nouveau thème</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('subscriber.themes.propose') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Titre du thème</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    En proposant un thème, vous deviendrez responsable de ce thème une fois qu'il sera accepté par l'administrateur.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Soumettre la proposition
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 