<?php

namespace App\Http\Controllers;

use App\Http\Livewire\CompetitionDetails;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Participation;
use App\Models\Setting;
use App\Models\Competition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use function PHPUnit\Framework\throwException;
use function Psy\debug;
use App\Models\UserPicture;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades;


class CompetitionController extends Controller
{
    public function index()
    {
        // create a new list of competitions
        $categoriesList = array();
        // get all categories with their competitions from the database
        $categories = Category::with(['competitions' => function ($query) {
            $query->whereNull('archived_at');
        }])->get();

        // loop through all categories
        foreach ($categories as $category) {
            // create a new category

            $cat = new Category();
            // set the name and description of the category
            $cat->name = $category->name;
            $cat->description = $category->description;
            // loop through all competitions of the category
            foreach ($category->competitions as $competition) {
                // create a new competition

                if($competition->winner != null){
                    continue;
                }

                $comp = new Competition();
                // set the name and description of the competition
                $comp->id = $competition->id;
                $comp->name = $competition->name;
                $comp->description = $competition->description;
                $comp->start_date = $competition->start_date;
                $comp->end_date = $competition->end_date;
                $comp->status = $competition->status;
                $comp->winner = $competition->winner;
                $comp->competitorsAmount = Participation::where('competition_id', $competition->id)->count();

                $comp->isFull = $comp->competitorsAmount >= $competition->max_amount_competitors;
                // check if the user is participating in the competition
                if (auth()->user() == null){
                    $comp->userIsParticipating = false;
                }else {
                    if (Participation::where('user_id', auth()->user()->id)->where('competition_id', $competition->id)->first() != null)
                    {
                        $comp->userIsParticipating = true;
                    }else {
                        $comp->userIsParticipating = false;
                    }
                }

                // add the competition to the category
                $cat->competitions[] = $comp;
            }
            // add the category to the list of categories
            $categoriesList[] = $cat;
        }
        // sort the list of categories by name alphabetically
        usort($categoriesList, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        // return the view with the list of categories
        return view('competitions.CompetitionList', ["categories" => $categoriesList]);
    }

    public function participatingList(Request $request) : View
    {
        // validate the request
        $request->validate([
            'sort' => 'nullable|in:Closed,Finished,Ongoing,All',
            'ownedSort' => 'nullable|in:Owned,Unowned,All',
        ]);

        $sort = $request->input('sort');
        $ownedSort = $request->input('ownedSort');

        #region retrieve competitions from db
        // Get all competitions the user is participating in
        $participations = Participation::where('user_id', auth()->user()->id)->get();
        $competitions = array();
        foreach ($participations as $participation) {
            $competition = Competition::where('id', $participation->competition_id)->first();
            if ($competition != null) {
                $competitions[] = $competition;
            }
        }

        // add to competitions array all competitions that the user created, prevent duplicates if the user is both participating and created the competition
        $userCompetitions = Competition::where('user_id', auth()->user()->id)->get();
        foreach ($userCompetitions as $userCompetition) {
            $exists = false;
            foreach ($competitions as $competition) {
                if ($competition->id == $userCompetition->id) {
                    $exists = true;
                }
            }
            if (!$exists) {
                $competitions[] = $userCompetition;
            }
        }

        #endregion

        #region filter competitions

        // filter competitions according to filters
        $competitions = array_filter($competitions, function ($competition) use ($sort, $ownedSort) {
            if ($ownedSort === "Owned" && $competition->user_id != auth()->user()->id) {
                return false;
            }
            if ($ownedSort === "Unowned" && $competition->user_id == auth()->user()->id) {
                return false;
            }

            if ($sort === "Finished" && $competition->end_date < Carbon::now() && $competition->winner != null) {
                return true;
            }
            if ($sort === "Closed" && $competition->end_date < Carbon::now()) {
                return true;
            }
            if ($sort === "Ongoing" && $competition->end_date > Carbon::now() && $competition->winner == null) {
                return true;
            }
            if ($sort === "All") {
                return true;
            }
            if ($sort === null) {
                return true;
            }
            return false;
        });

        #endregion

        #region add data to competitions

        // Get all categories with their competitions from the database, only with competitions the user is participating in. Do not include categories without competitions.
        $allCategories = Category::with(['competitions' => function ($query) use ($competitions) {
            $query->whereIn('id', array_map(function ($competition) {
                return $competition->id;
            }, $competitions));
        }])->get();

        $categories = array();

        foreach ($allCategories as $category) {
            foreach ($category->competitions as $competition) {
                if ($competition->winner) {
                    $winnerName = User::find($competition->winner)->name;
                    $competition->winner_name = $winnerName;
                }
                $competition->competitorsAmount = Participation::where('competition_id', $competition->id)->count();
                $competition->hasSubmission = UserPicture::where('competition_id', $competition->id)->where('userId', auth()->user()->id)->first() != null;
                // if the user owns the competition
                if ($competition->user_id == auth()->user()->id) {
                    $competition->userIsOwner = true;
                } else {
                    $competition->userIsOwner = false;
                }
            }
            if (count($category->competitions) > 0) {
                $categories[] = $category;
            }
            $category->competitions = $category->competitions->sortBy('name');
        }

        #endregion
        return view('competitions.JoinedCompetitionList',
            [
                "categories" => $categories,
                "ownedSort" => $ownedSort,
                "sort" => $sort
            ]);
    }

    public function create()
    {
        $categories = Category::whereNull('archived_at')->get();
        $categories = $categories->sortBy('name');
        $url = route('competitions.store');
        $settings = Setting::first();
        $maxPhotos = $settings->max_amount_photos ?? 0;

        return view('competitions.CompetitionCreate', compact('url', 'maxPhotos'), ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'naam' => 'required|max:20',
            'omschrijving' => 'max:500',
            'categorie' => 'required',
            'minimum_aantal_deelnemers' => 'required|numeric|gt:0',
            'maximum_aantal_deelnemers' => 'numeric|gt:0|gt:minimum_aantal_deelnemers',
            'start_datum' => 'required|date|after_or_equal:today',
            'eind_datum' => 'date|after_or_equal:start_datum',
            'minimum_aantal_fotos' => 'required|numeric|gt:0',
            'maximum_aantal_fotos' => 'numeric|gt:0|gte:minimum_aantal_fotos',
        ]);

        // Get the max_amount_photos value from the settings table
        $settings = Setting::first();
        $maxPhotos = $settings->max_amount_photos ?? 0;

        // Check if the maximum_aantal_fotos is higher than maxPhotos
        if ($request->maximum_aantal_fotos > $maxPhotos) {
            return redirect()->back()->withErrors(['errorMaxPhoto' => __('competition.MaxPhotosSuperAdmin', ['maxPhotos' => $maxPhotos])]);

            return back()->withErrors(['message', $errorMessage]);
        }


        $competition = new Competition();
        $competition->name = $request->naam;
        $competition->description = $request->omschrijving;

        // get category, if it does not exist then create category
        $category = Category::where('id', $request->categorie)->first();
        if ($category == null) {
            $category = new Category();
            $category->name = $request->categorie;
            $category->description = "This is a test category. Description";
            $category->save();
        }
        $competition->category_id = $category->id;
        $competition->min_amount_competitors = $request->minimum_aantal_deelnemers;
        $competition->max_amount_competitors = $request->maximum_aantal_deelnemers;
        $competition->min_amount_pictures = $request->minimum_aantal_fotos;
        $competition->max_amount_pictures = $request->maximum_aantal_fotos;
        $competition->start_date = $request->start_datum;
        $competition->end_date = $request->eind_datum;
        $competition->user_id = auth()->user()->id;
        $competition->save();

        // redirect to competition list
        return redirect()->route('competitions.index');
    }

    public function participate($competitionId)
    {
        // check if the competition has already too many participants
        $competition = Competition::where('id', $competitionId)->first();
        $participations = Participation::where('competition_id', $competitionId)->count();
        if ($participations >= $competition->max_amount_competitors){
            // redirect to competition list
            return redirect()->route('wedstrijden', $competitionId);
        }

        // Check if the user isn't already participating in the competition
        $participation = Participation::where('user_id', auth()->user()->id)->where('competition_id', $competitionId)->first();
        if ($participation == null){
            // User is not participating, create participation
            $participation = new Participation();
            $participation->user_id = auth()->user()->id;
            $participation->competition_id = $competitionId;
            $participation->save();

        }

        // redirect to competition list
        return redirect()->route('wedstrijden', $competitionId);
    }

    public function delete(Request $request)

    {
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('competitions.index');
        }

        $validateData = $request->validate([
            'competition_id' => 'required|numeric',
            'message' => 'required|max:255',
        ]);

        $competition = Competition::where('id', $request->competition_id)->first();
        if ($competition == null) {
            return redirect()->route('competitions.index');
        }

        // notify the users who participated in the competition
        $participations = Participation::where('competition_id', $request->competition_id)->get();
        foreach ($participations as $participation) {
            if ($participation->user_id == $competition->user_id) {
                // since the creator doesn't always participate, we need to give the notification seperately
                continue;
            }
            $user = User::where('id', $participation->user_id)->first();
            $notification = new Notification(
                [
                    'user_id' => $user->id,
                    'title' => 'notification.CompetitionYouParticipateInHasBeenDeletedTitle',
                    'body' => 'notification.CompetitionYouParticipateInHasBeenDeletedBody',
                    'competition_id' => $competition->id,
                    'participation_id' => $participation->participation_id,
                ]
            );
            $notification->save();
        }
        // notify the creator
        $reason = $request->input('message');
        $user = User::where('id', $competition->user_id)->first();
        $notification = new Notification(
            [
                'user_id' => $user->id,
                'title' => 'notification.CompetitionYouParticipateInHasBeenDeletedTitle',
                'body' => 'notification.CompetitionYouParticipateInHasBeenDeletedForReasonBody',
                'competition_id' => $competition->id,
                'participation_id' => null,
                'message' => $reason,
            ]
        );
        $notification->save();

        $competition->archived_at = Carbon::now();
        $competition->save();


        return redirect()->back();
    }


    public function makeWinner($main_photo_id)
    {
        $participation = Participation::where('main_photo_id', $main_photo_id)->first();

        $competition_id = $participation->competition_id;
        $user_id = $participation->user_id;

        $competition = Competition::where('id', $competition_id)->first();

        //if the competition already has a winner return
        if($competition->winner != null){
            return redirect()->back();
        }

        $competition->winner = $user_id;
        $competition->save();


        $winnerName = User::find($user_id)->name;
        $winnerParticipation = Participation::where('user_id', $user_id)->where('competition_id', $competition_id)->first();

        // create a new notification for every participant of the competition
        $participations = Participation::where('competition_id', $competition_id)->get();
        foreach ($participations as $participation) {
            $userHasWon = $participation->user_id == $user_id;
            $notification = new Notification(
                [
                    'title' => $userHasWon ? 'notification.CompetitionYouParticipateInYouWonTitle' : 'notification.CompetitionYouParticipateInHasAWinnerTitle',
                    'body' => $userHasWon ? 'notification.CompetitionYouParticipateInYouWonBody' : 'notification.CompetitionYouParticipateInHasAWinnerBody',
                    'competition_id' => $competition_id,
                    'participation_id' => $winnerParticipation->participation_id,
                    'user_id' => $participation->user_id,
            ]
            );
            $notification->save();
        }

        return redirect()->back();
    }


    public function endCompetition($competitionId) {
        // check that the user is the owner of the competition
        if (auth()->user() == null ) {
            return redirect()->route('competitions.index');
        }
        $userid = auth()->user()->id;
        $competition = Competition::where('id', $competitionId)->first();
        if ($competition->user_id != $userid) {
            return redirect()->route('competitions.index');
        }
        $competition->end_date = Carbon::now();

        // send notification to the owner
        $competitionHasBeenNotified = Notification::where('competition_id', $competition->id)->where('user_id', $userid)->where('title', 'notification.CompetitionYouParticipateInHasEndedTitle')->exists();
        if (!$competitionHasBeenNotified) {
            $notification = new Notification(
                [
                    'user_id' => $userid,
                    'title' => 'notification.CompetitionYouParticipateInHasEndedTitle',
                    'body' => 'notification.CompetitionYouOwnHasEndedEarlyBody',
                    'competition_id' => $competition->id,
                    'participation_id' => null,
                ]
            );
            $notification->save();
        }


        // send notification to all participants, if they don't already have one
        $participations = Participation::where('competition_id', $competitionId)->get();
        foreach ($participations as $participation) {
            $competitionHasBeenNotified = Notification::where('competition_id', $competition->id)->where('user_id', $participation->user_id)->where('title', 'notification.CompetitionYouParticipateInHasEndedTitle')->exists();
            if ($competitionHasBeenNotified) {
                continue;
            }
            $notification = new Notification(
                [
                    'user_id' => $participation->user_id,
                    'title' => 'notification.CompetitionYouParticipateInHasEndedTitle',
                    'body' => 'notification.CompetitionYouParticipateInHasEndedEarlyBody',
                    'competition_id' => $competition->id,
                    'participation_id' => $participation->participation_id,
                ]
            );
            $notification->save();
        }


        $competition->save();

        return redirect()->back();
    }

}
