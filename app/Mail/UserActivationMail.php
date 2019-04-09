<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class UserActivationMail extends Mailable
{

    /**
     * @var \App\Models\User
     */
    public $user;


    /**
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * @return UserActivationMail
     */
    public function build(): UserActivationMail
    {
        return $this->subject('Активация аккаунта')->markdown('mails.user-activation');
    }

}
