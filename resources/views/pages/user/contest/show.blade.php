@extends('layouts.master')

@section('title', 'Detail Contest')
@section('meta-tag')
    <meta name="description" content="Leaderboard">
@endsection

@section('subtitle', 'Detail Contest')

@section('content')
    <section class="section">
        <div class="card shadow-sm border-0 rounded-4 bg-white">
            <div class="card-header text-center bg-white border-0 py-4">
                <h4 class="card-title text-primary fw-bold text-uppercase">{{ $contest->name }}</h4>
            </div>
            <div class="card-body mt-3">
                <div class="row">
                    <!-- List of Members -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-4 bg-light p-3">
                            <h5 class="text-primary fw-bold text-uppercase">Team : {{ $team->name }}</h5>
                            <h5 class="text-primary fw-bold text-uppercase">Score : {{ $team->score }}</h5>
                        </div>
                        <div class="card shadow-sm border-0 rounded-4 bg-light p-3">
                            <h5 class="text-primary fw-bold text-uppercase">Members</h5>
                            <ul class="list-group list-group-flush">
                                @foreach($members as $member)
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                        <span class="fw-medium">{{ $member->participant->name }}</span>
                                        <span class="badge bg-secondary">{{ $member->participant->email }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Form to Submit Activities -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 bg-light p-4">
                            <h5 class="text-primary fw-bold text-uppercase">Submit Activity</h5>
                            <form id="addApproveRequest" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="contest_id" value="{{ $contest->id }}" />
                                <input type="hidden" name="team_id" value="{{ $team->id }}" />
                                <div class="mb-3">
                                    <label for="score" class="form-label fw-medium">Score</label>
                                    <input type="number" class="form-control" id="score" name="score" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-medium">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header text-center">
                <h4 class="card-title">History</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Score</th>
                            <th scope="col">Approve</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($approveRequests as $approve)
                            <tr>
                                <th scope="row"></th>
                                <td>{{ $approve->score }}</td>
                                <td>
                                    {{ $approve->is_approve ? 'Approved' : 'Pending' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No history available.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#addApproveRequest').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('participant.contest.submit') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            });
        })
    </script>
@endpush
