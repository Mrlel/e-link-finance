<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
 public function index()
{
    $user = auth()->user();
    $directionId = $user->direction_id;

    $notifications = $user->notifications()
    ->where('data->direction_id', $directionId)
    ->get();


    return view('admin.notifications.index', compact('notifications'));
}


    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }


    //:::: les fonctions liée à la maintenance


    
    public function markAllAsReadMaintenance(Request $request)
    {
        auth()->user()->unreadNotifications()->where('type', 'App\Notifications\DemandeMaintenanceNotification')->markAsRead();
        return redirect()->back()->with('success', 'Toutes les notifications de maintenance ont été marquées comme lues');
    }

    public function markAsReadMaintenance($id)
    {
        $MaintenanceNotif = auth()->user()->notifications()->findOrFail($id);
        $MaintenanceNotif->markAsRead();
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }
    








   public function userNotifications()
   {
       $notificationsMaterielAssignation = auth()->user()->unreadNotifications()->get()
               ->where('type', 'App\Notifications\EquipementAssignedNotification');
   
       $notificationsLogicielAssignation = auth()->user()->unreadNotifications()->get()
               ->where('type', 'App\Notifications\LogicielAssignedNotification');            
   
       $notificationsMaterielRetour = auth()->user()->unreadNotifications()->get()
               ->where('type', 'App\Notifications\EquipementReturnedNotification');
    
       return view('Users.notifications.index', compact(
           'notificationsMaterielAssignation',
           'notificationsLogicielAssignation',
           'notificationsMaterielRetour'
       ));
   }
}
