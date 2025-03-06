@extends('layouts.master')

@section('title', 'Leaderboard')
@section('meta-tag')
    <meta name="description" content="Leaderboard">
@endsection

@section('subtitle', 'Leaderboard Participant')

@section('content')
    <section class="section row mb-3">
        @forelse($contests as $contest)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-light">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-primary fw-bold text-uppercase mb-2">{{ $contest->name }}</h5>
                        <div class="my-3">
                            <span class="badge bg-dark px-3 py-2 text-uppercase">{{ $contest->type }}</span>
                            <span class="badge bg-secondary px-3 py-2 text-uppercase">{{ $contest->status }}</span>
                        </div>
                        <p class="text-muted small mb-3 fw-medium">{{ \Carbon\Carbon::parse($contest->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($contest->end_date)->format('M d, Y') }}</p>
                        <a href="{{ route('participant.dashboard.show', ['id' => $contest->id]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm">View Leaderboard</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center fw-bold p-3 rounded-3" role="alert">
                No contest available.
            </div>
        @endforelse
    </section>
@endsection

@section('scripts')
    <script>
        console.log('Leaderboard Loaded');
    </script>
@endsection
