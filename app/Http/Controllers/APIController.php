<?php

namespace App\Http\Controllers;


use App\Models\Competition;
use App\Models\User;
use App\Models\Participation;
use App\Models\UserPicture;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class APIController extends Controller
{

    // API function to create a competition, returns the competition id
    public function createCompetition(Request $request) {

        $id = 1; // TODO test data

        // check if the request has the valid id
        if ($request->id != $id) {
            return response()->json(['error' => 'Invalid id'], 401);
        }

        try {
            // set the validation error lang to english
            app()->setLocale('en');
            $validatedData = $request->validate([
                'name' => 'required|max:20',
                'description' => 'max:500',
                'category' => 'required',
                'minimum_amount_participants' => 'required|numeric|gt:0',
                'maximum_amount_participants' => 'numeric|gt:0|gt:minimum_amount_participants',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'date|after_or_equal:start_date',
                'minimum_amount_submissions' => 'required|numeric|gt:0',
                'maximum_amount_submissions' => 'numeric|gt:0|gte:minimum_amount_submissions',
            ]);

            // Validation passed, create the competition object and assign values
            $competition = new Competition();
            $competition->name = $validatedData['name'];
            $competition->description = $validatedData['description'];
            $competition->category_id = $validatedData['category'];
            $competition->min_amount_competitors = $validatedData['minimum_amount_participants'];
            $competition->max_amount_competitors = $validatedData['maximum_amount_participants'];
            $competition->start_date = $validatedData['start_date'];
            $competition->end_date = $validatedData['end_date'];
            $competition->min_amount_pictures = $validatedData['minimum_amount_submissions'];
            $competition->max_amount_pictures = $validatedData['maximum_amount_submissions'];

            $competition->user_id = $id; // TODO get user id from session

            // Save the competition
            $competition->save();

            // Return a success response
            return response()->json(['message' => 'Competition created successfully', 'new_competition_id' => $competition->id], 201);
        } catch (ValidationException $e) {
            // Return a validation error response
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Return a general error response
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }



    public function getUserCompetition(Request $request)
    {
        // TODO get API key from request
        $id = 3;

        if (!$request->has('id')) {
            return response()->json([
                'error' => 'No id given'
            ]);
        }
        if ($request->id != $id) {
            return response()->json([
                'error' => 'Wrong id given'
            ]);
        }


        $user = User::find($id);

        //create query to get all competitions
        $competitions = $user->competitions()->get();

        return response()->json([
            'user' => $user,
            'competitions' => $competitions
        ]);
    }


     public function uploadImage(Request $request)
    {
        // TODO: Get API key from request or authentication header
        // Validate API key or perform authentication based on your implementation

        // Check if the required fields are present in the request
        if (!$request->hasFile('image')) {
            return response()->json([
                'error' => 'Incorrect image given'
            ], 400);
        }

        if(!request->had('competition_id')){
            return response()->json([
                'error' => 'Incorrect competition_id given'
            ], 400);
        }

        $competitionId = $request->input('competition_id');

        $competition = Competition::find($competitionId);

        if ($competition == null) {
            return response()->json([
                'error' => 'Non-existent competition'
            ], 404);
        }

        if (!$request->has('user_id')) {
            return response()->json([
                'error' => 'No user_id given'
            ]);
        }

        $user_id = $request->input('user_id');

        $user = User::find($user_id);

        if ($user == null) {
            return response()->json([
                'error' => 'Non-existent user'
            ], 404);
        }

        if($competition->end_date < Carbon::now()){
             return response()->json([
                'error' => 'Competition end date has been reached'
            ], 400); 
        }
        
        // Check if the user is participating in the competition
        $participation = Participation::where('user_id', $user_id)
            ->where('competition_id', $competitionId)
            ->first();

        if ($participation == null) {
            return response()->json([
                'error' => 'User is not participating in the competition'
            ], 403);
        }

        // Check if the user has already reached the max amount of submissions
        $userSubmittedTotal = UserPicture::where('competition_id', $request->has('competition_id'))
        ->where('userId', $request->input('user_id'))
        ->count();


        if ($userSubmittedTotal >= $competition->max_amount_pictures) {
            return response()->json([
                'error' => 'Maximum limit of photos reached'
            ], 403);
        }

        $image = $request->file('image');

        // Validate the image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $name = Str::uuid();
        $filename = $name . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('images/submissions/'), $filename);

        $userPicture = new UserPicture([
            'id' => $filename,
            'submission_date' => now(),
            'userId' => $user->id,
            'competition_id' => $competitionId,
            'participation_id' => $participation->participation_id,
        ]);

        // If it is the first submission, set the main_picture to true
        if ($userSubmittedTotal == 0) {
            $participation->main_photo_id = $filename;
            $participation->save();
        }


        $userPicture->save();


        return response()->json([
            'message' => 'Image uploaded successfully',
            'picture_id' => $userPicture->id,
        ], 200);
    }
}
