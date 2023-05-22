<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Models\User;
use App\Models\UserPicture;
use App\Models\UserPictureRating;
use Illuminate\Http\Request;

class SubmissionDetailsController extends Controller
{
    //

    public function index($competitionId, $submissionId){
        // get the submission photo
        $submission = UserPicture::where('id', $submissionId)->first();

        // check if the submission exists
        if ($submission == null){
            // return error
            return redirect()->route('notfound', ['msg' => 'nonExistentPicture']);
        }

        // the picture should be in the competition
        if ($submission->competition_id != $competitionId){
            // return error
            return redirect()->route('notfound', ['msg' => 'nonExistentPicture']);
        }

        // get the user
        $user = $submission->user;

        // get the competition
        $competition = $submission->competition;
        $submission = UserPicture::all()->where('competition_id', $competitionId)->where('userId', $submission->userId)->sortBy('submission_date');
        $participation = Participation::where('user_id', $user->id)->where('competition_id', $competitionId)->first();


        $feedbackratings = UserPictureRating::where('participation_id', $participation->participation_id)->get();
        foreach ($feedbackratings as $feedbackrating){
            $feedbackrating->feedbackgiver = User::where('id', $feedbackrating->user_id)->first()->name;
            $feedbackrating->date = $feedbackrating->created_at->format('d-m-Y');
        }

        // sort the feedbackratings on date, newest first. Exception is that the feedback of the user logged in is always on top
        $feedbackratings = $feedbackratings->sortByDesc(function ($feedbackrating, $key) {
            if (auth()->user() == null){
                return $feedbackrating->created_at->timestamp;
            }else {
                if ($feedbackrating->user_id == auth()->user()->id){
                    return 999999999999999999999; // this is the highest number possible which forces the feedback of the user logged in to be on top
                } else {
                    return $feedbackrating->created_at->timestamp;
                }
            }
        });

        $userHasGivenFeedback = false;
        if (auth()->user() != null){
            if (UserPictureRating::where('participation_id', $participation->participation_id)->where('user_id', auth()->user()->id)->first() != null){
                $userHasGivenFeedback = true;
            }
        }

        // check if the user is the owner of the submission
        $userIsOwner = false;
        if (auth()->user() != null){
            if ($user->id == auth()->user()->id){
                $userIsOwner = true;
            }
        }

        return view('competitions.submissionDetails', [
            'submission' => $submission,
            'user' => $user,
            'competition' => $competition,
            'competitionId' => $competitionId,
            'mainPhotoSubmissionId' => $submissionId,
            'participation' => $participation,
            'feedbackratings' => $feedbackratings,
            'userHasGivenFeedback' => $userHasGivenFeedback,
            'userIsOwner' => $userIsOwner,
        ]);
    }
}
