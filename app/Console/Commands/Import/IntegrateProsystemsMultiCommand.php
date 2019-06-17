<?php

namespace App\Console\Commands\Import;

use App\Models\Mall;
use Illuminate\Console\Command;
use App\Jobs\ImportChequeProsystem;
use App\Integration\Mall\Prosystems;
use App\Models\MallIntegrationSystem;

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
     * @var \App\Integration\Mall\Prosystems
     */
    protected $integration;

    /**
     * @var \App\Models\Mall
     */
    protected $mall;


    /**
     * @return void
     * @throws \SoapFault
     */
    public function handle(): void
    {
        $this->mall = Mall::find(Mall::KERUEN_CITY);

        $this->integration = Prosystems::init(
            $this->mall->getIntegration(MallIntegrationSystem::PROSYSTEMS)
        );

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
                    $this->info("Adding {$item->UniqueId}");

                    ImportChequeProsystem::dispatch($this->mall, $item);
                }

                if ($this->integration->authorize()) {
                    $this->integration->confirmData();
                } else {
                    $this->error('Unauthorized');
                }

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
