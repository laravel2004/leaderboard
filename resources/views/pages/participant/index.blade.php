@extends('layouts.master')

@section('title', 'Participant Management')
@section('meta-tag')
    <meta name="description" content="Admin Backoffice for Participant Management">
@endsection

@section('content')
    <div class="container">
        <!-- Card Container -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Participant List</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Filter Form -->
                    <form action="{{ route('admin.participant.index') }}" method="GET" class="flex-grow-1 me-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </form>
                    <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#addEdit">Add</button>
                </div>
                <!-- Table Data -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($participants as $participant)
                            <tr data-code-id="{{ $participant->id }}">
                                <td>{{ $loop->iteration + $participants->firstItem() - 1 }}</td>
                                <td>{{ $participant->name }}</td>
                                <td>{{ $participant->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-delete">Delete</button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-bs-toggle="modal" data-bs-target="#addEdit" data-id="{{ $participant->id }}" data-name="{{ $participant->name }}" data-email="{{ $participant->email }}">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No programs found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $participants->appends(['search' => request('search')])->links() }}
        </div>
    </div>

    <!-- Add Program Modal -->
    <div class="modal fade" id="addEdit" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditModalLabel">Add New Participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input placeholder="Enter name" type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input placeholder="Enter email" type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input placeholder="Enter password" type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitAddEdit">Submit</button>
                    </div>
                </form>
                <form id="editForm">
                    <div class="modal-body">
                        @csrf
                        <input id="id" name="id" type="hidden" />
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input placeholder="Enter name" type="text" name="name" id="nameEdit" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input placeholder="Enter email" type="email" name="email" id="emailEdit" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password<small class="text-danger">*edit if change</small></label>
                            <input placeholder="Enter password" type="password" name="password" id="passwordEdit" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitAddEdit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            // Add Button
            $(".btn-add").click(function () {
                const addForm = $('#addForm');
                addForm.trigger('reset');
                addForm.show()
                $('#editForm').hide();
                $('#addEditModalLabel').text('Add New Participant');
            });

            // Edit Button
            $(".btn-edit").click(function () {
                const id = $(this).data('id');
                const editForm = $('#editForm');
                editForm.trigger('reset');
                $('#id').val(id);
                $('#nameEdit').val($(this).data('name'));
                $('#emailEdit').val($(this).data('email'));
                editForm.show();
                $('#addForm').hide();
                $('#addEditModalLabel').text('Edit Participant');
            });

            // Add Form
            $('#addForm').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.participant.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (response) {
                        console.log(response);
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
            });

            // Edit form
            $('#editForm').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('admin.participant.update', ':id') }}".replace(':id', formData.get('id')),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function (response) {
                        console.log(response);
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
            });

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
                            url: "{{ route('admin.participant.destroy', ':id') }}".replace(':id', id),
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
