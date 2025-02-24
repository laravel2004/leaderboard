<?php

use App\Http\Controllers\Admin\Contest\ContestController;
use App\Http\Controllers\Admin\Contest\TeamContestController;
use App\Http\Controllers\Admin\Participant\ParticipantController;
use App\Http\Controllers\Admin\Score\ScoreApproveController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Participant\AuthParticipantController;
use App\Http\Controllers\Participant\DashboardParticipantController;
use App\Http\Controllers\Participant\ParticipantContestController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ParticipantAuthMiddleware;
use App\Models\Contest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('participant.login');
});

Route::prefix('/')->group(function () {

    Route::prefix('/')->group(function () {
        Route::get('login', [AuthParticipantController::class, 'viewLogin'])->name('participant.login');
        Route::post('login', [AuthParticipantController::class, 'login'])->name('participant.login.post');
        Route::post('logout', [AuthParticipantController::class, 'logout'])->name('participant.logout');

        Route::middleware([ParticipantAuthMiddleware::class])->group(function () {
            Route::get('dashboard', [DashboardParticipantController::class, 'index'])->name('participant.dashboard');

            // Contest
            Route::prefix('contest')->group(function () {
                Route::get('/', [ParticipantContestController::class, 'index'])->name('participant.contest.index');
                Route::get('/{id}', [ParticipantContestController::class, 'show'])->name('participant.contest.show');
                Route::post('/submit', [ScoreApproveController::class, 'store'])->name('participant.contest.submit');
            });
        });
    });

    Route::prefix('auth')->group(function () {
        // Auth
        Route::get('login', [AuthController::class, 'viewLogin'])->name('admin.login');
        Route::post('login', [AuthController::class, 'login'])->name('admin.login.post');
        Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    });

    Route::middleware([AuthMiddleware::class])->group(function () {
        Route::get('admin/dashboard', function () {
            $contests = Contest::with('teams')->get();
            return view('pages.dashboard.index', compact('contests'));
        })->name('admin.dashboard');

        Route::prefix('panel')->group(function () {
            // Participant
            Route::prefix('participant')->group(function () {
                Route::get('/', [ParticipantController::class, 'index'])->name('admin.participant.index');
                Route::post('/', [ParticipantController::class, 'store'])->name('admin.participant.store');
                Route::put('/{id}', [ParticipantController::class, 'update'])->name('admin.participant.update');
                Route::delete('/{id}', [ParticipantController::class, 'destroy'])->name('admin.participant.destroy');
            });

            // Contest
            Route::prefix('contest')->group(function () {
                Route::get('/', [ContestController::class, 'index'])->name('admin.contest.index');
                Route::get('/create', [ContestController::class, 'create'])->name('admin.contest.create');
                Route::post('/', [ContestController::class, 'store'])->name('admin.contest.store');
                Route::get('/{id}/edit', [ContestController::class, 'edit'])->name('admin.contest.edit');
                Route::put('/{id}', [ContestController::class, 'update'])->name('admin.contest.update');
                Route::delete('/{id}', [ContestController::class, 'destroy'])->name('admin.contest.destroy');
            });

            // Team Contest
            Route::prefix('team-contest')->group(function () {
                Route::get('/', [TeamContestController::class, 'index'])->name('admin.team-contest.index');
                Route::get('/{id}', [TeamContestController::class, 'show'])->name('admin.team-contest.show');
                Route::post('/{id}/generate-individual-contest', [TeamContestController::class, 'generateIndividualContest'])->name('admin.team-contest.generate-individual-contest');
                Route::post('/{id}/store', [TeamContestController::class, 'store'])->name('admin.team-contest.store');
                Route::post('/reset-score', [TeamContestController::class, 'resetScore'])->name('admin.team-contest.reset-score');
            });

            // Score
            Route::prefix('score')->group(function () {
                Route::get('/', [ScoreApproveController::class, 'index'])->name('admin.score.index');
                Route::post('/approve/{id}', [ScoreApproveController::class, 'approve'])->name('admin.score.approve');
            });
        });
    });

});
