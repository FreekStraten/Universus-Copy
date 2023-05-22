<?php

namespace App\Http\Controllers;
use App\Models\Competition;
use App\Models\Participation;
use App\Models\UserPicture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    // Returns the upload page with the competition information
    public function uploadIndex($competitionId) {
        // get the competition
        $comp = Competition::where('id', $competitionId)->first();

        if ($comp == null) {
            return redirect()->route('notfound', ['msg' => 'nonExistentCompetition']);
        }

        // check if the user is participating in the competition
        if (auth()->user() == null){
            return redirect()->route('accessdenied', ['msg' => 'nonExistentUser']);
        }
        // check if the user is the owner, if so redirect to the detail page
        if ($comp->user_id == auth()->user()->id) {
            return redirect()->route('wedstrijden', ['wedstrijdId' => $competitionId]);
        }
        if (Participation::where('user_id', auth()->user()->id)->where('competition_id', $competitionId)->first() == null)
        {
            return redirect()->route('accessdenied', ['msg' => 'nonParticipatingUser']);
        }
        $userSubmittedImages = UserPicture::where('competition_id', $competitionId)->where('userId', Auth::user()->id)->get();

        $userSubmittedTotal = $userSubmittedImages->count();

        $mainImageId = Participation::where('user_id', Auth::user()->id)->where('competition_id', $competitionId)->first()->main_photo_id;

        return view('competitions.upload', [
            'competition' => $comp,
            'id' => $competitionId,
            'userSubmittedTotal' => $userSubmittedTotal,
            'userPictures' => $userSubmittedImages,
            'userUploadedMaxReached' => $userSubmittedTotal >= $comp->max_amount_pictures,
            'mainImageId' => $mainImageId,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $comp = Competition::where('id', $id)->first();
        if ($comp == null)
        {
            // return not found error.
            return redirect()->route('notfound', ['msg' => 'nonExistentCompetition']);
        }
        // Check if the user isn't already participating in the competition
        $participation = Participation::where('user_id', auth()->user()->id)->where('competition_id', $id)->first();
        if ($participation == null){
            return redirect()->route('accessdenied', ['msg' => 'nonParticipatingUser']);
        }
        // Check if the user has already reached the max amount of submissions
        $userSubmittedTotal = UserPicture::where('competition_id', $id)->where('userId', Auth::user()->id)->count();
        if ($userSubmittedTotal >= $comp->max_amount_pictures){
            return redirect()->route('accessdenied', ['msg' => 'maxLimitPhotosReached']);
        }

        $request->validate(['image' => 'required|image|mimes:jpeg|max:2048']);

        $image = $request->file('image');
        $name = Str::uuid();
        $filename = $name . '.jpeg';
        $fileextension = $image->extension();
        $image->move(public_path('images/submissions/'), $filename);

        $id = $request->id;
        $value = substr($id, 0, strlen($id) - 1);
        $userpic = new UserPicture([
            'id' => $filename,
            'submission_date' => now(),
            'userId' => auth()->user()->id,
            'competition_id' => $id,
            'participation_id' => $participation->participation_id,
         ]);

         // if it is the first submission, set the main_picture to true
            if ($userSubmittedTotal == 0){
                $participation->main_photo_id = $filename;
                $participation->save();
            }
         $userpic->save();


        return redirect()->back()->with('message', "SuccessfulUpload");
    }

    public function setMainImage(Request $request){

        $userPicture = UserPicture::where('id', $request->image_id)->first();

        // check if the picture exists
        if ($userPicture == null){
            // return error
            return redirect()->route('notfound', ['msg' => 'nonExistentPicture']);
        }

        // check if the picture is from the current user
        if ($userPicture->userId != auth()->user()->id){
            // return error
            return redirect()->route('accessdenied', ['msg' => 'nonExistentPicture']);
        }

        // Get the competition and check if it has ended
        $competition = Competition::where('id', $userPicture->competition_id)->first();
        if ($competition->hasEnded()){
            // return error
            return redirect()->route('accessdenied', ['msg' => 'competitionHasEnded']);
        }

        // get the participation of the user in the competition
        $participation = Participation::where('user_id', auth()->user()->id)->where('competition_id', $userPicture->competition_id)->first();
        $participation->main_photo_id = $request->image_id;
        $participation->save();

        // redirect back to page
        return redirect()->back();
    }
}
