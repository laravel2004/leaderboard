<?php

namespace App\Http\Controllers\Admin\Contest;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\MemberTeam;
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamContestController extends Controller
{
    private Contest $contest;
    private MemberTeam $memberTeam;
    private Team $team;
    private Participant $participant;

    public function __construct(Contest $contest, MemberTeam $memberTeam, Team $team, Participant $participant)
    {
        $this->contest = $contest;
        $this->memberTeam = $memberTeam;
        $this->team = $team;
        $this->participant = $participant;
    }

    public function index()
    {
        $contests = $this->contest->all();

        return view('pages.contest.team.index', compact('contests'));
    }

    public function show($id)
    {
        $teams = $this->team->query()
            ->where('contest_id', $id)
            ->with('userMembers')
            ->get();

        $contest = $this->contest->find($id);
        $participants = $this->participant->all();

        return view('pages.contest.team.show', compact('teams', 'contest', 'participants'));
    }

    public function generateIndividualContest($id)
    {
        try {
            DB::beginTransaction();
            $participant = $this->participant->all();
            foreach ($participant as $p) {
                $team = $this->team->create([
                    'contest_id' => $id,
                    'name' => $p->name,
                ]);

                $this->memberTeam->create([
                    'team_id' => $team->id,
                    'participant_id' => $p->id,
                ]);
            }

            DB::commit();

            return $this->successResponse(null, 'Team generated successfully');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'participants' => 'required|array',
            ]);

            DB::beginTransaction();

            $team = $this->team->create([
                'contest_id' => $id,
                'name' => $validated['name'],
            ]);

            foreach ($validated['participants'] as $participant) {
                $this->memberTeam->create([
                    'team_id' => $team->id,
                    'participant_id' => $participant,
                ]);
            }

            DB::commit();

            return $this->successResponse(null, 'Team created successfully');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function resetScore()
    {
        try {
            $validated = request()->validate([
                'contest_id' => 'required',
            ]);

            $teams = Team::where('contest_id', $validated['contest_id'])->get();
            foreach ($teams as $team) {
                $team->update([
                    'score' => 0,
                ]);
            }

            return $this->successResponse(null, 'Score reset successfully');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
