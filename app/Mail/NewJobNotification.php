<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewJobNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $jobDetails;
    /**
     * Create a new message instance.
     */
    public function __construct($jobDetails)
    {
        $this->jobDetails = $jobDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Notification',
        );
    }

    /**
     * Get the message content definition.
     */
     

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject('New Job Notification') // Email subject
                    ->view('email.new_job_notification') // Blade view for the email content
                    ->with(['jobDetails' => $this->jobDetails]); // Pass data to the view
    }
}
