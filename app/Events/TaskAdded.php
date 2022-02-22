<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TaskAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users_all;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($users_all)
    {
        $this->users_all = $users_all;
    }

    /**
     * Get the channels the event should broadcast on.
     * イベントが放送するチャンネルを取得します。
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new PrivateChannel('task-added', $this->users_all);
    }
}
