<?php

namespace App\Jobs;

use Pusher\Pusher;

trait PusherTrait
{
	public function startPush()
	{
		$pusher = new Pusher(
			config('broadcasting.connections.pusher.key'),
        	config('broadcasting.connections.pusher.secret'),
        	config('broadcasting.connections.pusher.app_id'),
			[
				'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            	'useTLS' => true
			]
		);

		$pusher->trigger('downloads', 'download.completed', []);
	}

	public function completedPush($data = null)
	{
        $pusher = new Pusher(
			config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
			[
				'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            	'useTLS' => true
			]
        );

		$data = json_encode([
			'id' => $this->download->id,
			'message' => 'Hello world',
		], JSON_UNESCAPED_UNICODE);

        $pusher->trigger(
			'downloads', 
			'download.completed', 
			$data,
			[],
			true
		);
	}
}