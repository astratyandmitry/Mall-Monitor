<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class StoreYesterdayCheqeusErrorMail extends Mailable
{

    /**
     * @var \App\Models\Store
     */
    public $store;

    /**
     * @var string
     */
    public $date;


    /**
     * @param \App\Mail\Store $store
     * @param string          $date
     *
     * @return void
     */
    public function __construct(Store $store, string $date)
    {
        $this->store = $store;
        $this->date = $date;
    }


    /**
     * @return StoreYesterdayCheqeusErrorMail
     */
    public function build(): StoreYesterdayCheqeusErrorMail
    {
        return $this
            ->subject("{$this->store->mall->name}: {$this->store->name} отсуствутют данные за {$this->date}")
            ->markdown('mails.store-yesterday-cheques-error');
    }

}
