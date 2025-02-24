@extends('layouts.master')
@section('title', 'Contest')
@section('meta-tag')
    <meta name="description" content="Leaderboard">
@endsection
@section('subtitle', 'Your Available Contest')

@section('content')
    <section class="section">
        <div class="row justify-content-center">
            @forelse($teams as $team)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-light">
                        <img src="{{ asset('storage/' . $team->contest->image) }}" class="card-img-top" alt="Contest Image" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title text-primary fw-bold text-uppercase mb-2">{{ $team->contest->name }}</h5>
                            <div class="my-3">
                                <span class="badge bg-dark px-3 py-2 text-uppercase">{{ $team->contest->type }}</span>
                                <span class="badge bg-secondary px-3 py-2 text-uppercase">{{ $team->contest->status }}</span>
                            </div>
                            <p class="text-muted small mb-3 fw-medium">{{ \Carbon\Carbon::parse($team->contest->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($team->contest->end_date)->format('M d, Y') }}</p>
                            <a href="{{ route('participant.contest.show', ['id' => $team->contest->id]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-warning text-center fw-bold p-3 rounded-3" role="alert">
                        No contest available.
                    </div>
                </div>
            @endforelse
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        console.log('Leaderboard Loaded');
    </script>
@endsection
