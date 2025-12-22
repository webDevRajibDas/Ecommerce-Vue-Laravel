<?php

namespace App\Jobs;

use App\Models\Podcast;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PodcastProcessedNotification;

class ProcessPodcast implements ShouldQueue
{
    use Batchable, InteractsWithQueue, Queueable, SerializesModels;

    public $podcastId;

    public function __construct($podcastId)
    {
        $this->podcastId = $podcastId;
    }

    public function handle()
    {
        // Stop if batch is cancelled
        if ($this->batch()?->cancelled()) {
            return;
        }

        Log::info("Processing Podcast: {$this->podcastId}");

        //1. Fetch Podcast
        $podcast = Podcast::findOrFail($this->podcastId);


         //2. Download audio file
        $rawPath = $podcast->audio_path;
        $audioFile = Storage::disk('local')->get('public/sample-12s.mp3');


         // 3. Extract metadata (duration, bitrate…)
         // You can use getID3 library if needed.
        
        $metadata = [
            'duration' => rand(180, 900), // demo
            'bitrate'  => '128kbps',
            'format'   => 'mp3',
        ];

      
         //4. Generate waveform image (simulation)
       
        $waveformPath = "waveforms/podcast_{$this->podcastId}.png";
        Storage::disk('public')->put($waveformPath, 'WAVEFORM_IMAGE_BINARY');

        
        //5. Convert audio to optimized format (simulation)
        $optimizedPath = "processed/podcast_{$this->podcastId}.mp3";
        Storage::disk('public')->put($optimizedPath, $audioFile);

        
         // 6. Update Database
       
        $podcast->update([
            'metadata'      => json_encode($metadata),
            'waveform_path' => $waveformPath,
            'processed_path'=> $optimizedPath,
            'status'        => 'processed'
        ]);

     
         //7. Notify Author
        
        $podcast->author->notify(new PodcastProcessedNotification($podcast));

        Log::info("Podcast {$this->podcastId} processed successfully.");
    }
}
