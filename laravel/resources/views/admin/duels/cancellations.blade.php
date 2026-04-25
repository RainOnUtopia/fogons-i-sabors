@extends('layouts.admin')

@section('title', 'Moderació de Duels')

@section('content')
<div class="users-container">
    <div class="users-header">
        <div class="users-header-content">
            <h1 class="users-page-title">Peticions de Cancel·lació de Duels</h1>
        </div>
        <div class="users-header-actions">
            <a href="{{ route('admin.dashboard') }}" class="users-dashboard-btn">
                <i class="bi bi-arrow-left"></i>
                Panell Admin
            </a>
        </div>
    </div>

    <!-- Missatges d'estat-->
    @if (session('success'))
        <div class="users-alert-container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="users-alert-container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="users-alert-container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="users-card">
        <div class="users-card-header">
            <h2 class="users-card-title">Llista de Peticions</h2>
        </div>

        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th class="users-table-header">DUEL ID</th>
                        <th class="users-table-header">PARTICIPANTS</th>
                        <th class="users-table-header">RECEPTES</th>
                        <th class="users-table-header">ESTAT</th>
                        <th class="users-table-header">ACCIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duels as $duel)
                        <tr class="users-table-row">
                            <td class="users-table-cell">#{{ $duel->id }}</td>
                            <td class="users-table-cell">
                                <div><strong>Reptador:</strong> {{ $duel->challengerName }}</div>
                                <div><strong>Reptat:</strong> {{ $duel->challengedName }}</div>
                            </td>
                            <td class="users-table-cell">
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $duel->challengerRecipeTitle }}">
                                    <i class="bi bi-journal-text"></i> {{ $duel->challengerRecipeTitle }}
                                </div>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $duel->challengedRecipeTitle }}">
                                    <i class="bi bi-journal-text"></i> {{ $duel->challengedRecipeTitle }}
                                </div>
                            </td>
                            <td class="users-table-cell">
                                <span class="users-badge users-badge-inactive">Pendent</span>
                            </td>
                            <td class="users-table-cell users-actions-cell">
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('duels.show', $duel->id) }}" target="_blank" class="btn btn-sm btn-info btn-detall text-white d-flex align-items-center gap-1">
                                        <i class="bi bi-eye"></i> Veure
                                    </a>
                                    
                                    <form action="{{ route('duels.status.update', $duel->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelat">
                                        <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1" onclick="return confirm('Estàs segur d\'aprovar la cancel·lació? El duel quedarà cancel·lat definitivament.')">
                                            <i class="bi bi-check-circle"></i> Aprovar
                                        </button>
                                    </form>

                                    <form action="{{ route('duels.status.update', $duel->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="iniciat">
                                        <button type="submit" class="btn btn-sm btn-success btn-detall d-flex align-items-center gap-1" onclick="return confirm('Estàs segur de rebutjar la petició? El duel tornarà a estar iniciat.')">
                                            <i class="bi bi-x-circle"></i> Rebutjar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="users-table-cell text-center py-4 text-muted">
                                No hi ha cap petició de cancel·lació pendent.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginació -->
        <div class="users-pagination-wrapper pagination-container mt-4">
            {{ $duels->links("pagination::bootstrap-5") }}
        </div>
    </div>
</div>
@endsection
