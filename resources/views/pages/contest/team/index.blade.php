@extends('layouts.master')

@section('title', 'Member of Team')
@section('meta-tag')
    <meta name="description" content="Member of team">
@endsection

@section('title', 'Member of Team')
@section('subtitle', 'List Member of Team')

@section('content')
    <section class="section">
        <div class="row">
            @foreach($contests as $contest)
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $contest->name }}</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <img src="{{ asset('storage/' . $contest->image) }}" alt="{{ $contest->name }}" class="img-fluid">
                            </div>
                            <div class="text-center">
                                <span class="badge mt-2 rounded-pill text-bg-dark">{{ $contest->status }}</span>
                                <span class="badge mt-2 rounded-pill text-bg-light">{{ $contest->type }}</span>
                                <br />
                                <span class="badge mt-2 rounded-pill text-bg-secondary">{{ \Carbon\Carbon::parse($contest->start_date)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($contest->end_date)->format('Y-m-d') }}</span>
                            </div>
                            <div class="mt-3">
                                <p>{{ $contest->description }}</p>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('admin.team-contest.show', ['id' => $contest->id]) }}" class="btn btn-primary w-100">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        console.log('Test');
    </script>
@endpush
