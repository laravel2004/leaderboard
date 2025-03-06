<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\MemberTeam;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardParticipantController extends Controller
{
    public function index()
    {
        $contests = Contest::with('teams')->get();
        return view('pages.user.dashboard.index', compact('contests'));
    }

    public function show($id)
    {
        $contest = Contest::where('id', $id)->with('teams')->first();
        return view('pages.user.dashboard.show', compact('contest'));
    }
}
