@extends('layouts.master')

@section('title', 'Approve Score')
@section('meta-tag')
    <meta name="description" content="Approve Score">
@endsection

@section('title', 'Approve Score')
@section('subtitle', 'Approve Request Score')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Approve</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
{{--                            <th>Name</th>--}}
                            <th>Team/Name</th>
                            <th>Contest</th>
                            <th>Score</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($requestScores as $requestScore)
                            <tr data-code-id="{{ $requestScore->id }}">
                                <td>{{ $loop->iteration + $requestScores->firstItem() - 1 }}</td>
{{--                                <td>{{ $requestScore->participant->name }}</td>--}}
                                <td>{{ $requestScore->team->name }}</td>
                                <td>{{ $requestScore->contest->name }}</td>
                                <td>{{ $requestScore->score }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $requestScore->image) }}" alt="{{ $requestScore->team->name }}" class="img-fluid" style="width: 100px; height: 100px;">
                                </td>
                                <td>
                                    {!! $requestScore->is_approve
                                        ? '<span class="text-success fw-bold">Approved</span>'
                                        : '<button class="btn btn-sm btn-success btn-approve" data-id="' . $requestScore->id . '">Approve</button>'
                                    !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No approve request found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            {{ $requestScores->appends(['search' => request('search')])->links() }}
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-approve').on('click', function () {
                const id = $(this).data('id');
                const tr = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to approve this score?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.score.approve', ['id' => ':id']) }}.".replace(':id', id),
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                }).then(() => {
                                    window.location.reload();
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
                    }
                });
            });
        });
    </script>
@endpush
