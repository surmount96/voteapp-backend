<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoteCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $passcode;
    public function __construct($user,$passcode)
    {
        $this->user = $user;
        $this->passcode = $passcode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject('VOTE CODE')
        ->markdown('vote_code_email');
    }
}
