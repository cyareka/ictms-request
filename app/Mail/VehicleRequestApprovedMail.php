<?php

namespace App\Mail;

use App\Models\VehicleRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VehicleRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vehicleRequest;

    /**
     * Create a new message instance.
     *
     * @param VehicleRequest $vehicleRequest
     * @return void
     */
    public function __construct(VehicleRequest $vehicleRequest)
    {
        $this->vehicleRequest = $vehicleRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Vehicle Request Has Been Approved')
                    ->view('emails.vehicle-request-approved');
    }
}