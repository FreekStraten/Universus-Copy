<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Models\UserPicture;
use App\Models\UserPictureRating;
use Illuminate\Http\Request;

class UserPictureRatingController extends Controller
{

    public function getPlayerStarRatingList(Request $request)
    {
        // validate request
        $this->validate($request, [
            'user_id' => 'required',
        ]);

        $userId = $request->input('user_id');

        $userPictureRating = UserPictureRating::where('user_id', $userId)->get();

        return response()->json($userPictureRating);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'picture_id' => 'required',
            'star_rating' => 'required',
        ]);
        $userId =  $request->input('user_id');
        $pictureId =  $request->input('picture_id');
        $starRating =  $request->input('star_rating');

        $partId = UserPicture::where('id', $pictureId)->first()->participation_id;

        $userPictureRating = UserPictureRating::where('user_id', $userId)->where('participation_id', $partId)->first();

        if ($userPictureRating === null) {
            $userPictureRating = new UserPictureRating();
            $userPictureRating->user_id = $userId;
            $userPictureRating->picture_id = $pictureId;
            $userPictureRating->star_rating = $starRating;
            $userPictureRating->participation_id = $partId;
        } else {
            $userPictureRating->star_rating = $starRating;
        }
        $userPictureRating->save();
        return response()->json($userPictureRating);
    }

    public function postFeedback(Request $request){

        $this->validate($request, [
            'picture_id' => 'required',
            'feedback' => 'required|max:1000',
            'participation_id' => 'required',
            'star_rating' => 'required',
        ]);

        $userId = auth()->user()->id;
        $pictureId =  $request->input('picture_id');
        $feedback =  $request->input('feedback');
        $starRating =  $request->input('star_rating');

        $participationId =  $request->input('participation_id');
        $userPictureRating = UserPictureRating::where('user_id', $userId)->where('participation_id', $participationId)->first();
        $participation = Participation::where('participation_id', $participationId)->first();
        // get the competition and check if the user can still give feedback
        $competition = $participation->competition;
        if ($competition->hasEnded()){
            return back()->withErrors(['feedback' => __('CompetitionDetails.CompetitionEndedReviewsClosed')]);
        }

        // if the user posting the feedback owns the submission
        if ($participation->user_id == $userId){
            return back()->withErrors(['feedback' => __('CompetitionDetails.CannotReviewOwnSubmission')]);
        }

        if ($userPictureRating === null) {
            $userPictureRating = new UserPictureRating();
            $userPictureRating->user_id = $userId;
            $userPictureRating->picture_id = $pictureId;
            $userPictureRating->feedback = $feedback;
            $userPictureRating->participation_id = $participationId;
            $userPictureRating->star_rating = $starRating;
        } else {
            $userPictureRating->feedback = $feedback;
        }
        $userPictureRating->save();
        // just return back to the page
        return back();
    }
}
