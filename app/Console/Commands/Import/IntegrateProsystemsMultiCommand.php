<?php

namespace App\Console\Commands\Import;

use App\Models\Mall;
use App\Integration\Prosystems;
use Illuminate\Console\Command;
use App\Models\IntegrationSystem;
use App\Jobs\ImportChequeProsystem;

class IntegrateProsystemsMultiCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:integrate-prosystems-multi';

    /**
     * @var string
     */
    protected $description = 'Integrate with Prosystems Multi Queries';

    /**
     * @var \App\Integration\Prosystems
     */
    protected $integration;

    /**
     * @var \App\Models\Mall
     */
    protected $mall;


    public function __construct()
    {
        $this->mall = Mall::find(Mall::KERUEN_CITY);

        $this->integration = Prosystems::init(
            $this->mall->getIntegration(IntegrationSystem::PROSYSTEMS)
        );

        parent::__construct();
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        do {
            $status = $this->work();
        } while ($status == true);
    }


    /**
     * @return bool
     */
    protected function work(): bool
    {
        if ($this->integration->authorize()) {
            if ($this->integration->provoidData()) {
                foreach ($this->integration->getData() as $item) {
                    // Only selling
//                    if ( ! isset($item->Type) || ! in_array($item->Type, ['Sell', 'SellReturn'])) {
//                        $this->error("Skip because of type {$item->UniqueId}");
//
//                        continue;
//                    }

                    // Only card and cash
//                    if (
//                        ! isset($item->Payments) || ! isset($item->Payments->Payment) || ! isset($item->Payments->Payment->Type) ||
//                        ! in_array($item->Payments->Payment->Type, ['Cash', 'Card'])
//                    ) {
//                        $this->error("Skip because of paymet {$item->UniqueId}");
//
//                        continue;
//                    }

                    $this->info("Adding {$item->UniqueId}");

                    ImportChequeProsystem::dispatch($this->mall, $item);
                }

                $this->integration->confirmData();

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}