<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\MemberTeam;
use App\Models\RequestScore;
use App\Models\Team;
use Illuminate\Http\Request;

class ParticipantContestController extends Controller
{

    private Contest $contest;

    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
    }

    public function index()
    {
        $participant = auth('participant')->user();
        $members = MemberTeam::where('participant_id', $participant->id)->get();
        $teams = Team::whereIn('id', $members->pluck('team_id'))
            ->whereHas('contest', function ($query) {
                $query->where('status', 'open');
            })
            ->with('contest')
            ->get();
        return view('pages.user.contest.index', compact('teams'));
    }

    public function show($id)
    {
        $contest = $this->contest->findOrFail($id);
        $participant = auth('participant')->user();
        $team = Team::where('contest_id', $contest->id)->whereHas('members', function ($query) use ($participant) {
            $query->where('participant_id', $participant->id);
        })->first();
        $members = MemberTeam::where('team_id', $team->id)->with('participant')->get();

        $approveRequests = RequestScore::where([
            'team_id' => $team->id,
            'contest_id' => $contest->id,
        ])->get();

        return view('pages.user.contest.show', compact('contest', 'members', 'team', 'approveRequests'));
    }
}
