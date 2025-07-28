<?php

namespace App\Observers;

use App\Models\Tool;
use App\Services\ToolRadarDatasetBridge;

class ToolObserver
{
    /**
     * Handle the Tool "created" event.
     */
    public function created(Tool $tool): void
    {
        //
    }

    /**
     * Handle the Tool "updated" event.
     */
    public function updated(Tool $tool): void
    {
        //
    }

    /**
     * Handle the Tool "deleted" event.
     */
    public function deleted(Tool $tool): void
    {
   		app('log')->debug('Deleted tool', [
			'feature' => 'tool',
			'tool_id' => $tool->id
		]);
	 }

	/**
     * Handle the Tool "deleting" event.
     */
    public function deleting(Tool $tool): void
	{
		app('log')->debug('Deleting tool', [
			'feature' => 'tool',
			'tool_id' => $tool->id
		]);
		$tool->removeFile(); // storage and RADAR
		if($tool->radar_id)
		{
			if($tool->radar_status < 2)
			{
				// remove from RADAR too
				$radar = new ToolRadarDatasetBridge($tool);
				if(!$radar->delete())
				{
					app('log')->warning('Failed whilst deleting tool due to RADAR error', [
						'feature' => 'tool',
						'tool_id' => $tool->id
					]);
					return;
				}
			}
			else
			{
				app('log')->error('Deleting tool which has radar_status >= 2', [
					'feature' => 'tool-radar-dataset',
					'tool_id' => $tool->id,
					'radar_id' => $tool->radar_id,
					'radar_status' => $tool->radar_status
				]);
				return;
			}

		}
    }

    /**
     * Handle the Tool "restored" event.
     */
    public function restored(Tool $tool): void
    {
        //
    }

    /**
     * Handle the Tool "force deleted" event.
     */
    public function forceDeleted(Tool $tool): void
    {
        //
    }
}
