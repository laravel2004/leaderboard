<?php

namespace App\Http\Controllers\Admin\Score;

use App\Http\Controllers\Controller;
use App\Models\RequestScore;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreApproveController extends Controller
{
    private RequestScore $requestScore;

    public function __construct(RequestScore $requestScore)
    {
        $this->requestScore = $requestScore;
    }

    public function index()
    {
        $requestScores = $this->requestScore->query()
            ->with('team', 'contest')
            ->where('is_approve', false)
            ->paginate(10);

        return view('pages.approval-score.index', compact('requestScores'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'team_id' => 'required|exists:teams,id',
                'score' => 'required|numeric',
                'contest_id' => 'required|exists:contests,id',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('scores', 'public');
            }

            $this->requestScore->create([
                'team_id' => $request->team_id,
                'score' => $request->score,
                'contest_id' => $request->contest_id,
                'image' => $validated['image'],
                'is_approved' => false,
            ]);

            return $this->successResponse(null, 'Score request submitted successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $requestScore = $this->requestScore->find($id);
            if ($requestScore->is_approved) {
                return $this->errorResponse('Score already approved');
            }

            DB::beginTransaction();
            $requestScore->update([
                'is_approve' => true,
            ]);

            $team = Team::find($requestScore->team_id);
            $team->update([
                'score' => $team->score + $requestScore->score,
            ]);

            DB::commit();

            return $this->successResponse(null, 'Score approved successfully');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
