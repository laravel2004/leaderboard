<?php

namespace App\Http\Controllers\Admin\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\ParticipantStoreRequest;
use App\Http\Requests\Participant\ParticipantUpdateRequest;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParticipantController extends Controller
{
    private Participant $participant;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    public function index()
    {
        $participants = $this->participant->query()
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            })
            ->select('id', 'name', 'email')
            ->paginate(10);

        return view('pages.participant.index', compact('participants'));
    }

    public function store(ParticipantStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            $this->participant->create($validated);

            return $this->successResponse(null, 'Participant created successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(ParticipantUpdateRequest $request, $id)
    {;
        try {
            $participant = $this->participant->findOrFail($id);
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            $participant->update($validated);

            return $this->successResponse(null, 'Participant updated successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $participant = $this->participant->findOrFail($id);
            $participant->delete();

            return $this->successResponse(null, 'Participant deleted successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
