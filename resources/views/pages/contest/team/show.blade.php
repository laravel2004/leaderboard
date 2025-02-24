@extends('layouts.master')

@section('title', 'Participant Contest')
@section('meta-tag')
    <meta name="description" content="Participant Contest">
@endsection

@section('content')
    <section class="section">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title">{{ $contest->name }}</h4>
            <div>
                @if($contest->type === "individual" && $teams->count() < 1 )
                    <button data-id="{{ $contest->id }}" class="btn btn-primary btn-generate-individu">Generate Member</button>
                @endif
                @if($contest->type === "team" )
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEdit">Add Team Member</button>
                @endif
                <button data-id="{{ $contest->id }}" class="btn btn-danger btn-reset">Reset</button>
            </div>
        </div>
        <div class="row mt-3">
            @if($teams->count() > 0)
                @foreach($teams as $team)
                    <div class="col-6 col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title d-flex justify-content-between">
                                    <h4>{{ $team->name }}</h4>
                                    <h4>{{ $team->score }}</h4>
                                </div>
                                <hr />
                            </div>
                            <div class="card-body">
                                @foreach($team->userMembers as $member)
                                    <p>{{ $member->participant->name }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <h3>No Participants</h3>
                    <p>There are no participants for this contest.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Add Program Modal -->
    <div class="modal fade" id="addEdit" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditModalLabel">Add New Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Team Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter team name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Participants</label>
                            <div id="participantList">
                                <div class="input-group mb-2 participant-item">
                                    <select name="participants[]" class="form-select">
                                        <option value="">-- Select Participant --</option>
                                        @foreach($participants as $participant)
                                            <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-danger removeParticipant">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="addParticipant">+ Add Participant</button>
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

            $('#addParticipant').click(function () {
                let participantInput = `
                <div class="input-group mb-2 participant-item">
                    <select name="participants[]" class="form-select">
                        <option value="">-- Select Participant --</option>
                        @foreach($participants as $participant)
                <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                        @endforeach
                </select>
                <button type="button" class="btn btn-danger removeParticipant">Remove</button>
            </div>`;
                $('#participantList').append(participantInput);
            });

            $(document).on('click', '.removeParticipant', function () {
                $(this).closest('.participant-item').remove();
            });

            $('.btn-generate-individu').on('click', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: '{{ route('admin.team-contest.generate-individual-contest', ['id' => ':id']) }}'.replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
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
            });

            $('#addForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let contestId = '{{ $contest->id }}';

                $.ajax({
                    url: '{{ route('admin.team-contest.store', ['id' => ':id']) }}'.replace(':id', contestId),
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
                            window.location.reload();
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

            $('.btn-reset').on('click', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.team-contest.reset-score') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        contest_id: id
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
            });

        });
    </script>
@endpush

