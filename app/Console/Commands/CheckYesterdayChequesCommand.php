<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;
use App\Repositories\ChequeRepository;
use App\Mail\StoreYesterdayCheqeusErrorMail;

class CheckYesterdayChequesCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:cheques-check-yesterday';

    /**
     * @var string
     */
    protected $description = 'Check Yesterdays Cheques exists';


    /**
     * @return void
     */
    public function handle(): void
    {
        $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
        $stores = Store::query()->get();

        if (count($stores)) {
            /** @var Store $store */
            foreach ($stores as $store) {
                $this->info("Working with Store #{$store->id}");

                $store->update([
                    'is_errors_yesterday' => ! ChequeRepository::isExistsForDate($store->id, $yesterdayDate),
                ]);

                if ( ! app()->isLocal()) {
                    \Mail::to(config('mallmonitor.mails.error_cheques'))->send(new StoreYesterdayCheqeusErrorMail($store, $yesterdayDate));
                }
            }
        } else {
            $this->error('No available Stores');
        }
    }

}
