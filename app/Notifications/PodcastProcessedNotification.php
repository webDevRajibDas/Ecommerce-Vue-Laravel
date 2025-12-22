<?php

namespace App\Notifications;

use App\Models\Podcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PodcastProcessedNotification extends Notification
{
    use Queueable;

    public Podcast $podcast;

   

    /**
     * Create a new notification instance.
     */
     public function __construct(Podcast $podcast)
    {
        $this->podcast = $podcast;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
         return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Podcast Has Been Processed')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your podcast **{$this->podcast->title}** has been successfully processed.")
            ->line("Duration: " . ($this->podcast->metadata['duration'] ?? 'Unknown') . " seconds")
            ->line("Optimized File: Available")
            ->action('View Podcast', url('/podcasts/' . $this->podcast->id))
            ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'podcast_id'   => $this->podcast->id,
            'title'        => $this->podcast->title,
            'waveform'     => $this->podcast->waveform_path,
            'processed'    => $this->podcast->processed_path,
            'metadata'     => $this->podcast->metadata,
            'message'      => "Your podcast '{$this->podcast->title}' has been processed.",
        ];
    }
}
