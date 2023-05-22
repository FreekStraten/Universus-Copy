<?php

namespace App\Http\Livewire;

use App\Models\Competition;
use App\Models\HomepageBannerPicture;
use App\Models\Participation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Homepage extends Component
{
    private $competition1;
    private $competition2;
    private $competition3;
    private $competitorsAmount1;
    private $competitorsAmount2;
    private $competitorsAmount3;
    private $userParticipating1;
    private $userParticipating2;
    private $userParticipating3;
    private $bannerId;
    private $competitionsExisting1;
    private $competitionsExisting2;
    private $competitionsExisting3;

    public function mount()
    {
        $results = Participation::select('participation.competition_id', DB::raw('COUNT(*) as amount_participants'))
            ->join('competitions', 'competitions.id', '=', 'participation.competition_id')
            ->whereNull('competitions.archived_at')
            ->groupBy('participation.competition_id')
            ->orderByDesc('amount_participants')
            ->limit(3)
            ->get();
        
        foreach ($results as $key => $result) {
            if ($key == 0) {
                $this->competition1 = Competition::find($result->competition_id);
                if (!is_null(auth()->user())) {
                    $this->userParticipating1 = $this->checkUserParticipating(auth()->user()->id, $this->competition1->id);
                }
                if($results->isEmpty()){
                    $this->competitionsExisting1 = false;
                } else {
                    $this->competitionsExisting1 = true;
                }
            } elseif ($key == 1) {
                $this->competition2 = Competition::find($result->competition_id);
                if (!is_null(auth()->user())) {
                    $this->userParticipating2 = $this->checkUserParticipating(auth()->user()->id, $this->competition2->id);
                }
                if($results->isEmpty()){
                    $this->competitionsExisting2 = false;
                } else {
                    $this->competitionsExisting2 = true;
                }
            } elseif ($key == 2) {
                $this->competition3 = Competition::find($result->competition_id);
                if (!is_null(auth()->user())) {
                    $this->userParticipating3 = $this->checkUserParticipating(auth()->user()->id, $this->competition3->id);
                }
                if($results->isEmpty()){
                    $this->competitionsExisting3 = false;
                } else {
                    $this->competitionsExisting3 = true;
                }
            }
        }

        if ($this->competitionsExisting1) {
        $this->competitorsAmount1 = Participation::where('competition_id', $this->competition1->id)->count();

        }
        if ($this->competitionsExisting2) {
            $this->competitorsAmount2 = Participation::where('competition_id', $this->competition2->id)->count();
        }
        if ($this->competitionsExisting3) {
            $this->competitorsAmount3 = Participation::where('competition_id', $this->competition3->id)->count();
        }

        $this->bannerId = $this->getCurrentBanner()->first();
    }

    public function checkUserParticipating($userId, $competitionId)
    {
        if (Participation::where('user_id', $userId)->where('competition_id', $competitionId)->first() != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getCurrentBanner()
    {
        return HomepageBannerPicture::orderBy('id','DESC')->limit(1)->get()->pluck('banner_id');
    }

    public function navigateToBannerUpload()
    {
        return redirect()->route('uploadBanner.index');
    }

    public function render()
    {
        return view('livewire.homepage', [
            'competition1' => $this->competition1,
            'competition2' => $this->competition2,
            'competition3' => $this->competition3,
            'competitorsAmount1' => $this->competitorsAmount1,
            'competitorsAmount2' => $this->competitorsAmount2,
            'competitorsAmount3' => $this->competitorsAmount3,
            'userIsParticipating1' => $this->userParticipating1,
            'userIsParticipating2' => $this->userParticipating2,
            'userIsParticipating3' => $this->userParticipating3,
            'competitionsExisting1' => $this->competitionsExisting1,
            'competitionsExisting2' => $this->competitionsExisting2,
            'competitionsExisting3' => $this->competitionsExisting3,
            'bannerId' => $this->bannerId,
        ]);
    }
}
