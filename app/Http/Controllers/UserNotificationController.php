<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function markAsRead($notification_id)
    {
        auth()->user()->notifications()->find($notification_id)->markAsRead();
    }
}
