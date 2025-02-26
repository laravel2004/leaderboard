@extends('layouts.master')

@section('title', 'Create Contest')
@section('meta-tag')
    <meta name="description" content="Test">
@endsection

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create the contest available</h4>
            </div>
            <div class="card-body">
                <form id="createForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="individual">Individual</option>
                                <option value="team">Team</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status">Status (Internal / External)</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Select Status</option>
                            <option value="open">External</option>
                            <option value="closed">Internal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" class="form-control mt-3">
                    </div>
                    <div class="mb-3">
                        <textarea name="description" id="description" class="form-control mt-3" style="height: 100px" placeholder="Enter Description"></textarea>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-full">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#createForm').submit(function (e) {
                e.preventDefault();
                console.log('Test');

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('admin.contest.store') }}',
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
