@extends('layouts.master')

@section('title', 'Leaderboard')
@section('meta-tag')
    <meta name="description" content="Leaderboard">
@endsection

@section('subtitle', 'Leaderboard Participant')

@section('content')
    <section class="section">
        @forelse($contests as $contest)
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title">Leaderboard {{ $contest->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Score</th>
                                <th scope="col">Rank</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                // Urutkan berdasarkan skor tertinggi
                                $sortedTeams = $contest->teams->sortByDesc('score');

                                // Inisialisasi rank dan kategori medali
                                $rank = 0;
                                $prevScore = null;
                                $medals = ['Gold', 'Silver', 'Bronze']; // Urutan medali
                            @endphp

                            @forelse($sortedTeams as $team)
                                @php
                                    // Jika skor berbeda dari sebelumnya, tingkatkan ranking
                                    if ($team->score !== $prevScore) {
                                        $rank++;
                                    }
                                    $prevScore = $team->score;

                                    // Tentukan medali berdasarkan ranking
                                    $medal = $rank <= 3 ? $medals[$rank - 1] : 'Participant';

                                    // Pilih warna berdasarkan medali
                                    $badgeClass = match($medal) {
                                        'Gold' => 'bg-success',
                                        'Silver' => 'bg-secondary',
                                        'Bronze' => 'bg-warning',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <tr>
                                    <th scope="row">{{ $rank }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->score }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}">{{ $medal }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No participant found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
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
