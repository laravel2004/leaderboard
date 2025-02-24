@extends('layouts.master')

@section('title', 'Edit Contest')
@section('meta-tag')
    <meta name="description" content="Edit Contest">
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Contest</h4>
            </div>
            <div class="card-body">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="{{ $contest->name }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="type">Type</label>
                            <input type="hidden" value="{{ $contest->type }}" name="type" />
                            <select disabled id="type" name="type" class="form-select">
                                <option value="individual" {{ $contest->type == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="team" {{ $contest->type == 'team' ? 'selected' : '' }}>Team</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($contest->start_date)->format('Y-m-d') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($contest->end_date)->format('Y-m-d') }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="open" {{ $contest->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ $contest->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" class="form-control mt-3">
                        @if($contest->image)
                            <img src="{{ asset('storage/' . $contest->image) }}" alt="Image" width="150" height="150" class="img-fluid mt-3">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control mt-3" style="height: 100px">{{ $contest->description }}</textarea>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-full">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#editForm').submit(function (e) {
                e.preventDefault();
                console.log('Submitting edit form...');

                let formData = new FormData(this);
                let contestId = '{{ $contest->id }}';

                $.ajax({
                    url: '{{ route('admin.contest.update', ':id') }}'.replace(':id', contestId),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            window.location.href = '{{ route('admin.contest.index') }}';
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors).flat().join('<br>');

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message,
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
