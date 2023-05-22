<?php

namespace App\Providers;

use App\Http\Controllers\CompetitionController;
use App\Models\Competition;
use App\Models\Navigation\NavBarDropdown;
use App\Models\Notification;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        view()->composer('*', function ($view)
        {

            // get the nav bar items
            // if the user is not logged in, only get the nav bar items that have 0 as the user_role
            if (!auth()->check()) {
                // use nav bar roles table and nav bar table to get the nav bar items
                $navBarItems = \App\Models\Navigation\NavBarUserRole::where('user_role', 0)->join('nav_bar', 'nav_bar_user_role.nav_bar_id', '=', 'nav_bar.id')->orderBy('order')->distinct()->get();
                $navBarItems = $navBarItems->unique('nav_bar_id');
                // get all the drop down items, if there are any
                foreach ($navBarItems as $navBarItem) {
                    // only get the nav bar dropdown items that have 0 as the user_role
                    $navBarItem->dropdown = NavBarDropdown::where('nav_bar_id', $navBarItem->id)->where('user_role', 0)->orderBy('order')->distinct()->get();
                    if (count($navBarItem->dropdown) > 0) {
                        $navBarItem->dropdownVisible = true;
                    }
                }
            } else {
                // if the user is logged in, get the nav bar items that have 0 as the user_role, and the user's role
                $navBarItems = \App\Models\Navigation\NavBarUserRole::where('user_role', 0)->orWhere('user_role', auth()->user()->user_role)->join('nav_bar', 'nav_bar_user_role.nav_bar_id', '=', 'nav_bar.id')->orderBy('order')->distinct()->get();
                $navBarItems = $navBarItems->unique('nav_bar_id');

                // get all the drop down items, if there are any
                foreach ($navBarItems as $navBarItem) {
                    // only get the nav bar dropdown items that have 0 as the user_role, and the user's role
                    $dropdownitems = NavBarDropdown::where('nav_bar_id', $navBarItem->nav_bar_id)->orderBy('order')->get();
                    $navBarItem->dropdown = $dropdownitems->where('user_role', 0)->merge($dropdownitems->where('user_role', auth()->user()->user_role));

                    // if the item has a dropdown, set the dropdown to be visible
                    if (count($navBarItem->dropdown) > 0) {
                        $navBarItem->dropdownVisible = true;
                    } else {
                        $navBarItem->dropdownVisible = false;
                    }
                }
            }

            // get the notifications for the user
            if (auth()->check()) {
                #region check for competitions that have ended


                // check if there have been any competitions that have ended, and if so, send a notification to the user
                // loop through all competitions that the user owns:
                $competitions = Competition::where('user_id', auth()->user()->id)->get();
                foreach ($competitions as $competition) {
                    // check if there is a 'notification.competitionYouOwnHasEndedTitle' notification for the competition
                    $competitionHasBeenNotified = Notification::where('competition_id', $competition->id)->where('user_id', auth()->user()->id)->where('title', 'notification.CompetitionYouParticipateInHasEndedTitle')->exists();

                    // if the competition has ended, and the user has not been notified yet, send a notification
                    if ($competition->end_date < date('Y-m-d H:i:s') && !$competitionHasBeenNotified) {
                        $notification = new \App\Models\Notification();
                        $notification->user_id = auth()->user()->id;
                        $notification->title = 'notification.CompetitionYouParticipateInHasEndedTitle';
                        $notification->body = 'notification.CompetitionYouOwnHasEndedBody';
                        $notification->competition_id = $competition->id;
                        $notification->created_at = $competition->end_date;
                        $notification->save();
                    }
                }

                // loop through all the competitions that the user participates in or owns
                $participations = Participation::where('user_id', auth()->user()->id)->get();
                foreach ($participations as $participation) {
                    $competition = Competition::find($participation->competition_id);

                    // check if there is a 'notification.competitionYouParticipateInHasEndedTitle' notification for the competition
                    $competitionHasBeenNotified = Notification::where('competition_id', $competition->id)->where('user_id', auth()->user()->id)->where('title', 'notification.CompetitionYouParticipateInHasEndedTitle')->exists();

                    // if the competition has ended, and the user has not been notified yet, send a notification
                    if ($competition->end_date < date('Y-m-d H:i:s') && !$competitionHasBeenNotified) {
                        $notification = new \App\Models\Notification();
                        $notification->user_id = auth()->user()->id;
                        $notification->title = 'notification.CompetitionYouParticipateInHasEndedTitle';
                        $notification->body = 'notification.CompetitionYouParticipateInHasEndedBody';
                        $notification->competition_id = $competition->id;
                        $notification->participation_id = $participation->participation_id;
                        $notification->created_at = $competition->end_date;
                        $notification->save();
                    }
                }
                #endregion

                $amountOfNotificationsToGet = 4;
                $notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->limit($amountOfNotificationsToGet)->get();
                $notifications->amountUnread = \App\Models\Notification::where('user_id', auth()->user()->id)->where('read', false)->count();
                // if higher than 9, set the notification count to 9+
                if ($notifications->amountUnread > 9) {
                    $notifications->amountUnread = '9+';
                }

                // for each notification, pass the title and body through the trans with all the variables
                foreach ($notifications as $notification) {
                    $participation = Participation::find($notification->participation_id);
                    $competition = Competition::find($notification->competition_id);
                    if ($competition != null){
                        $competitionName = $competition->name;
                    }else{
                        $competitionName = __('notification.CouldNotFind');
                    }
                    if ($participation != null){
                        $participatingUserName = User::find($participation->user_id)->name;
                    }else{
                        $participatingUserName = __('notification.CouldNotFind');
                    }

                    $notification->title = __($notification->title, ['competitionName' => $competitionName, 'userName' => $participatingUserName, 'reason' => $notification->message]);
                    $notification->body = __($notification->body, ['competitionName' => $competitionName, 'userName' => $participatingUserName, 'reason' => $notification->message]);
                }



                $view->with('notifications', $notifications);
            } else {
                $view->with('notifications', null);
            }


            $view->with('navbar', $navBarItems);
        });



    }
}
