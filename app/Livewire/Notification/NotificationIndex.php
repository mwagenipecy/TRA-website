<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class NotificationIndex extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, unread, read
    
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        session()->flash('message', 'All notifications marked as read.');
    }
    
    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }
    
    public function render()
    {
        $query = Auth::user()->notifications();
        
        if ($this->filter === 'unread') {
            $query = Auth::user()->unreadNotifications();
        } elseif ($this->filter === 'read') {
            $query = Auth::user()->readNotifications();
        }
        
        $notifications = $query->paginate(15);
        
        return view('livewire.notification.notification-index', [
            'notifications' => $notifications
        ]);
    }
}