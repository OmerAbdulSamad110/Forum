<?php

namespace App\Http\View\Composers;

use App\Channel;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ChannelsComposer
{
    protected $channel;
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function compose(View $view)
    {
        $channel = $this->channel->orderBy('name', 'ASC')->get();
        $view->with('channels', $channel);
    }
}
