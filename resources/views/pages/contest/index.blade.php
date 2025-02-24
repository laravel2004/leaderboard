@extends('layouts.master')

@section('title', 'Contest')
@section('meta-tag')
    <meta name="description" content="Contest Management">
@endsection

@section('title', 'Contest')
@section('subtitle', 'Contest Management')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Contest</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Filter Form -->
                    <form action="{{ route('admin.contest.index') }}" method="GET" class="flex-grow-1 me-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or description"
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </form>
                    <a class="btn btn-success btn-add" href="{{ route('admin.contest.create') }}">Add</a>
                </div>
                <!-- Table Data -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($contests as $contest)
                            <tr data-code-id="{{ $contest->id }}">
                                <td>{{ $loop->iteration + $contests->firstItem() - 1 }}</td>
                                <td>{{ $contest->name }}</td>
                                <td>{{ $contest->description }}</td>
                                <td>{{ \Carbon\Carbon::parse($contest->start_date)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contest->end_date)->format('Y-m-d') }}</td>
                                <td>{{ $contest->status }}</td>
                                <td>{{ $contest->type }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $contest->id }}">Delete</button>
                                    <a class="btn btn-sm btn-warning btn-edit" href="{{ route('admin.contest.edit', ['id' => $contest->id]) }}">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Contest found.</td>
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
        console.log('Test');
        $(document).ready(function () {

            // Delete Button
            $(".btn-delete").click(function () {
                const id = $(this).closest('tr').data('code-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.contest.destroy', ':id') }}".replace(':id', id),
                            type: 'DELETE',
                            data: {
                                '_token': '{{ csrf_token() }}'
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
                            error: function (xhr, status, error) {
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
