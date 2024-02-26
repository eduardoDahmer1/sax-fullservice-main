<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
    parent::__construct();
  }

  public function count()
  {
    $count = Notification::notRead()->count();
    return response()->json($count);
  }

  public function show()
  {
    Notification::where('sent', true)->update(['sent' => false]);
    $notifications = Notification::orderBy('created_at', 'DESC')->paginate(8);
    return view('admin.notification.popup', compact('notifications'));
  }

  public function notification()
  {
    $firstEightNotifications = Notification::orderBy('created_at', 'DESC')
    ->where('sent', 0)
    ->take(8)
    ->pluck('id');

    $notifications = Notification::orderBy('created_at', 'DESC')
    ->where('sent', 0)
    ->whereNotIn('id', $firstEightNotifications)
    ->with('order')
    ->with('user')
    ->with('product')
    ->with('conversation')
    ->paginate(10);

    Notification::whereIn('id', $notifications->pluck('id'))->update(['sent' => true]);
    return response()->json($notifications);
  }


  public function markAllAsRead()
  {
    Notification::notRead()->update(['is_read' => 1]);
  }

  public function clear($notification_id)
  {
    Notification::find($notification_id)->delete();
  }
}
