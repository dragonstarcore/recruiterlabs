<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterestJobNotification extends Mailable
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
            subject: 'Interest Job Notification',
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
        return $this->subject('Your Job Interested') // Email subject
                    ->view('email.job_interest_notification') // Blade view for the email content
                    ->with(['jobDetails' => $this->jobDetails]); // Pass data to the view
    }
}
