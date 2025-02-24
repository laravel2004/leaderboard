<?php

namespace App\Http\Controllers\Admin\Contest;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    private Contest $contest;

    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
    }

    public function index()
    {
        $contests = $this->contest->query()
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->select('id', 'name', 'description', 'start_date', 'end_date', 'status', 'type')
            ->paginate(10);

        return view('pages.contest.index', compact('contests'));
    }

    public function create()
    {
        return view('pages.contest.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|string',
                'type' => 'required|string',
                'image' => 'required|image',
            ]);

            $validated['image'] = $request->file('image')->store('contests', 'public');

            $this->contest->create($validated);

            return $this->successResponse(null, 'Contest created successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function edit($id)
    {
        $contest = $this->contest->findOrFail($id);

        return view('pages.contest.edit', compact('contest'));
    }

    public function update(Request $request, $id)
    {
        try {
            $contest = $this->contest->findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|string',
                'type' => 'required|string',
                'image' => 'nullable|image',
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('contests', 'public');
            }

            $contest->update($validated);

            return $this->successResponse(null, 'Contest updated successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $contest = $this->contest->findOrFail($id);
            $contest->delete();

            return $this->successResponse(null, 'Contest deleted successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
