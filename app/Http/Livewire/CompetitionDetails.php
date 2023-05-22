<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Notification;
use App\Models\Participation;
use App\Models\User;
use App\Models\UserPicture;
use App\Models\UserPictureRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CompetitionDetails extends Component
{
    private $userPictures;
    private $competitionId;
    private $competition;
    private $amountOfParticipating;
    private $category;
    private $categoryId;
    private $winnerName;

    public function mount($wedstrijdId)
    {
        $this->userPictures = UserPicture::all()->where('competition_id', $wedstrijdId)->sortBy('submission_date');
        $this->competitionId = $wedstrijdId;
        $this->competition = Competition::find($wedstrijdId);
        $this->amountOfParticipating = Participation::where('competition_id', $wedstrijdId)->count();
        if ($this->competition->winner) {
            $winnerName = User::find($this->competition->winner)->name;
            $this->competition->winner_name = $winnerName;
        }
    }

    public function boot()
    {
        $this->userPictures = UserPicture::all()->where('competition_id', request()->wedstrijd)->sortBy('submission_date');
        $this->competition = Competition::find('competition_id');
        $this->amountOfParticipating = Participation::where('competition_id', request()->wedstrijd)->count();

    }

    public function render()
    {
        // check if the current user logged in is participating in the competition
        $loggedInUserIsParticipating = false;
        $loggedInUserIsCompOwner = false;
        if (auth()->user() != null){
            if (Participation::where('user_id', auth()->user()->id)->where('competition_id', $this->competitionId)->first() != null)
            {
                $loggedInUserIsParticipating = true;
            }
            if (auth()->user()->id == $this->competition->user_id){
                $loggedInUserIsCompOwner = true;
            }
        }

        $mainPictures = Participation::where('competition_id', $this->competitionId)->whereNotNull('main_photo_id')->get();

        // for each main picture, get the submission and calculate the average rating.
        foreach ($mainPictures as $mainPicture){
            $submission = Participation::where('competition_id', $this->competitionId)->where('user_id', $mainPicture->user_id)->first();
            $mainPicture->amount_of_photos = UserPicture::where('participation_id', $submission->participation_id)->count();
            $mainPicture->amount_of_reviews = UserPictureRating::where('participation_id', $submission->participation_id)->count();

            // if the submission has no reviews yet, set the average score to -1
            // The average score is set to -1, to clearly indicate a difference between 0 average and no reviews yet.
            // This is used in the view for the stars.
            if (UserPictureRating::where('participation_id', $submission->participation_id)->count() == 0){
                $mainPicture->average_score = -1;
            }
            else
            {
                $mainPicture->average_score = round(UserPictureRating::where('participation_id', $submission->participation_id)->avg('star_rating'), 1);
            }


        }

        return view('livewire.competitionDetails', [
            'userPictures' => $this->userPictures,
            'competitionId' => $this->competitionId,
            'competition' => $this->competition,
            'amountOfParticipating' => $this->amountOfParticipating,
            'loggedInUserIsParticipating' => $loggedInUserIsParticipating,
            'competitionIsFull' => $this->amountOfParticipating >= $this->competition->max_amount_competitors,
            'mainPictures' => $mainPictures,
            'loggedInUserIsCompetitionOwner' => $loggedInUserIsCompOwner,
        ]);
    }

    public function deleteSendInWork(Request $request, $imageId){
        $request->validate([
            'message' => 'required|max:255',
        ]);

        $pic = UserPicture::where('id', $imageId)->first();

        // if the image doesn't exist, return error image not found
        if ($pic == null){
            return redirect()->route('notfound', ['msg' => 'nonExistentPicture']);
        }

        // delete the entire submission, so get the participation that belongs to the image and delete all images related to the participation
        $participation = Participation::where('participation_id', $pic->participation_id)->first();

        // check if the participation exists
        if ($participation == null){
            return redirect()->route('notfound', ['msg' => 'nonExistentPicture']);
        }

        $reason = $request->input('message');
        $comp = Competition::find($participation->competition_id);


        // notify the user that his submission has been deleted and why
        $notification = new Notification(
            [
                'user_id' => $participation->user_id,
                'title' => 'notification.YourSubmissionHasBeenDeletedTitle',
                'body' => 'notification.YourSubmissionHasBeenDeletedBody',
                'competition_id' => $participation->competition_id,
                'participation_id' => $participation->participation_id,
                'message' => $reason,
            ]
        );
        $notification->save();

        // also notify the competition owner that a submission has been deleted
        $notification = new Notification(
            [
                'user_id' => $comp->user_id,
                'title' => 'notification.SubmissionInYourCompetitionDeletedTitle',
                'body' => 'notification.SubmissionInYourCompetitionDeletedBody',
                'competition_id' => $participation->competition_id,
                'message' => $reason,
                'participation_id' => $participation->participation_id,
            ]
        );
        $notification->save();

        // delete all images related to the participation
        $images = UserPicture::where('participation_id', $participation->participation_id)->get();
        foreach ($images as $image){
            $localImage = public_path('images/submissions/' . $image->id);
            if (file_exists($localImage)) {
                unlink($localImage);
            }
            $image->delete();
        }

        // delete the main image in the participation
        $participation->main_photo_id = null;
        $participation->save();




        // return back
        return redirect()->back();
        }
}
