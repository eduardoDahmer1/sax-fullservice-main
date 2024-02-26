<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\Generalsetting;

class BackInStock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $product;
    private $storeSettings;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Product $product, Generalsetting $storeSettings)
    {
        $this->product = $product; 
        $this->storeSettings = $storeSettings;
    }

    public function product()
    {
        return $this->product;
    }

    public function storeSettings()
    {
        return $this->storeSettings;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
