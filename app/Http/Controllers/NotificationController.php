<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // mark all notifications as read
    public function markAsRead()
    {
        if (auth()->user() == null) {
            return redirect()->back();
        }
        $unreadNotifications = Notification::where('user_id', auth()->user()->id)->where('read', false)->get();
        foreach ($unreadNotifications as $notification) {
            $notification->read = true;
            $notification->save();
        }
        return redirect()->back();
    }




}
